import { createContext, useState, useEffect, useContext } from 'react';
import api from '../api/axios';
import { AuthContext } from './AuthContext';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
    const { user } = useContext(AuthContext);
    const [cart, setCart] = useState({ items: [], total_quantity: 0, total_price: 0 });

    useEffect(() => {
        if (user) {
            fetchCart();
        } else {
            setCart({ items: [], total_quantity: 0, total_price: 0 });
        }
    }, [user]);

    const fetchCart = async () => {
        try {
            const response = await api.get('/cart');
            if (response.data.status) {
                setCart(response.data.data);
            }
        } catch (error) {
            console.error("Failed to fetch cart", error);
        }
    };

    const addToCart = async (product) => {
        if (!user) {
            alert('Please login to add items to your cart.');
            return false;
        }
        try {
            const response = await api.post('/cart', {
                product_id: product.id,
                product_title: product.title,
                product_price: product.price,
                product_image: product.thumbnail,
                quantity: 1
            });
            if (response.data.status) {
                setCart(response.data.data);
                alert('Product added to cart!');
                return true;
            }
        } catch (error) {
            alert('Failed to add product to cart.');
            return false;
        }
    };

    const updateQuantity = async (id, quantity) => {
        if (quantity < 1) return;
        try {
            const response = await api.put(`/cart/${id}`, { quantity });
            if (response.data.status) {
                setCart(response.data.data);
            }
        } catch (error) {
            alert('Failed to update quantity.');
        }
    };

    const removeFromCart = async (id) => {
        try {
            const response = await api.delete(`/cart/${id}`);
            if (response.data.status) {
                setCart(response.data.data);
            }
        } catch (error) {
            alert('Failed to remove item.');
        }
    };

    return (
        <CartContext.Provider value={{ cart, fetchCart, addToCart, updateQuantity, removeFromCart, setCart }}>
            {children}
        </CartContext.Provider>
    );
};