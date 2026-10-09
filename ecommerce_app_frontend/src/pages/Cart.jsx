import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { CartContext } from '../context/CartContext';

export default function Cart() {
    const { cart, updateQuantity, removeFromCart } = useContext(CartContext);
    const navigate = useNavigate();

    if (!cart.items || cart.items.length === 0) {
        return (
            <div style={{ textAlign: 'center', marginTop: '50px' }}>
                <h2>Your Shopping Cart is Empty</h2>
                <Link to="/products" style={{ color: '#007185' }}>Continue Shopping</Link>
            </div>
        );
    }

    return (
        <div style={{ maxWidth: '1000px', margin: 'auto' }}>
            <h2>Shopping Cart</h2>

            <table style={{ width: '100%', borderCollapse: 'collapse', marginBottom: '20px' }}>
                <thead>
                    <tr style={{ background: '#f8f8f8', borderBottom: '2px solid #ddd' }}>
                        <th style={{ padding: '10px', textAlign: 'left' }}>Product</th>
                        <th style={{ padding: '10px', textAlign: 'center' }}>Price</th>
                        <th style={{ padding: '10px', textAlign: 'center' }}>Quantity</th>
                        <th style={{ padding: '10px', textAlign: 'center' }}>Subtotal</th>
                        <th style={{ padding: '10px', textAlign: 'center' }}>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {cart.items.map(item => (
                        <tr key={item.id} style={{ borderBottom: '1px solid #ddd' }}>
                            <td style={{ padding: '10px', display: 'flex', alignItems: 'center', gap: '10px' }}>
                                <img src={item.product_image} alt={item.product_title} style={{ width: '50px', height: '50px', objectFit: 'contain' }} />
                                <span>{item.product_title}</span>
                            </td>
                            <td style={{ padding: '10px', textAlign: 'center' }}>${Number(item.product_price).toFixed(2)}</td>
                            <td style={{ padding: '10px', textAlign: 'center' }}>
                                <button
                                    onClick={() => updateQuantity(item.id, Number(item.quantity) - 1)}
                                    style={{ padding: '5px 10px', cursor: 'pointer' }}
                                    disabled={item.quantity <= 1}
                                >-</button>
                                <span style={{ margin: '0 10px', fontWeight: 'bold' }}>{item.quantity}</span>
                                <button
                                    onClick={() => updateQuantity(item.id, Number(item.quantity) + 1)}
                                    style={{ padding: '5px 10px', cursor: 'pointer' }}
                                >+</button>
                            </td>
                            <td style={{ padding: '10px', textAlign: 'center', fontWeight: 'bold' }}>
                                ${Number(item.subtotal).toFixed(2)}
                            </td>
                            <td style={{ padding: '10px', textAlign: 'center' }}>
                                <button
                                    onClick={() => removeFromCart(item.id)}
                                    style={{ padding: '5px 10px', background: '#dc3545', color: 'white', border: 'none', borderRadius: '4px', cursor: 'pointer' }}
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <div style={{ textAlign: 'right', padding: '20px', background: '#f8f8f8', borderRadius: '8px' }}>
                <h3>Total Quantity: {cart.total_quantity}</h3>
                <h2 style={{ color: '#B12704' }}>Grand Total: ${Number(cart.total_price).toFixed(2)}</h2>
                <button
                    onClick={() => navigate('/checkout')}
                    style={{ padding: '12px 24px', background: '#ffd814', border: 'none', borderRadius: '20px', cursor: 'pointer', fontSize: '1.1em', fontWeight: 'bold', marginTop: '10px' }}
                >
                    Proceed to Checkout
                </button>
            </div>
        </div>
    );
}