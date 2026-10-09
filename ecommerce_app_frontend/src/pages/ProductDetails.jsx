import { useState, useEffect, useContext } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import api from '../api/axios';
import { CartContext } from '../context/CartContext';

export default function ProductDetails() {
    const { id } = useParams();
    const navigate = useNavigate();
    const { addToCart } = useContext(CartContext);
    const [product, setProduct] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        fetchProduct();
    }, [id]);

    const fetchProduct = async () => {
        setLoading(true);
        try {
            const response = await api.get(`/products/${id}`);
            if (response.data.status) {
                setProduct(response.data.data);
            } else {
                setError('Product not found');
            }
        } catch (err) {
            setError('Error fetching product details');
        } finally {
            setLoading(false);
        }
    };

    // EXACT CHANGE HERE: Use the CartContext function to update state instantly
    const handleAddToCart = async () => {
        const success = await addToCart(product);
        if (!success) {
            navigate('/login');
        }
    };

    if (loading) return <div>Loading product details...</div>;
    if (error) return <div style={{ color: 'red' }}>{error}</div>;
    if (!product) return <div>Product not found</div>;

    return (
        <div style={{ display: 'flex', gap: '40px', maxWidth: '1000px', margin: 'auto', padding: '20px', flexWrap: 'wrap' }}>
            <div style={{ flex: '1', minWidth: '300px' }}>
                <img
                    src={product.thumbnail}
                    alt={product.title}
                    style={{ width: '100%', maxHeight: '400px', objectFit: 'contain', border: '1px solid #ddd', borderRadius: '8px', padding: '10px' }}
                />
                <div style={{ display: 'flex', gap: '10px', marginTop: '10px', overflowX: 'auto', paddingBottom: '10px' }}>
                    {product.images?.map((img, index) => (
                        <img
                            key={index}
                            src={img}
                            alt={`${product.title} ${index}`}
                            style={{ width: '80px', height: '80px', objectFit: 'cover', border: '1px solid #ddd', borderRadius: '4px' }}
                        />
                    ))}
                </div>
            </div>

            <div style={{ flex: '1', minWidth: '300px', display: 'flex', flexDirection: 'column', gap: '15px' }}>
                <Link to="/products" style={{ color: '#007185', textDecoration: 'none' }}>&larr; Back to Products</Link>

                <h2 style={{ padding: '10px' }}>{product.title}</h2>
                <p style={{ color: '#555', margin: '0' }}>Category: <span style={{ textTransform: 'capitalize' }}>{product.category}</span></p>

                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <span style={{ background: '#ffa41c', padding: '2px 8px', borderRadius: '4px', color: '#111', fontSize: '0.9em', fontWeight: 'bold' }}>
                        ★ {product.rating}
                    </span>
                    <span style={{ color: product.stock > 0 ? '#007185' : '#B12704' }}>
                        {product.stock > 0 ? 'In Stock' : 'Out of Stock'}
                    </span>
                </div>

                <hr style={{ border: '0', borderTop: '1px solid #ddd', width: '100%' }} />

                <h2 style={{ color: '#B12704', margin: '0', fontSize: '2em' }}>${product.price.toFixed(2)}</h2>

                <p style={{ lineHeight: '1.6', fontSize: '1.1em' }}>{product.description}</p>

                <div style={{ marginTop: '20px' }}>
                    <button
                        onClick={handleAddToCart}
                        style={{
                            padding: '12px 24px',
                            background: '#ffd814',
                            border: '1px solid #fcd200',
                            borderRadius: '20px',
                            cursor: 'pointer',
                            fontSize: '1.1em',
                            width: '100%',
                            maxWidth: '250px',
                            fontWeight: 'bold',
                            boxShadow: '0 2px 5px rgba(0,0,0,0.1)'
                        }}
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    );
}