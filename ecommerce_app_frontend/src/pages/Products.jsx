// EXACT CHANGE HERE: Added useContext to imports
import { useState, useEffect, useContext } from 'react';
// EXACT CHANGE HERE: Added useNavigate to imports
import { Link, useNavigate } from 'react-router-dom';
import api from '../api/axios';
// EXACT CHANGE HERE: Imported CartContext
import { CartContext } from '../context/CartContext';

export default function Products() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [skip, setSkip] = useState(0);
    const [total, setTotal] = useState(0);
    const [error, setError] = useState('');
    const limit = 12;

    // EXACT CHANGE HERE: Added CartContext and useNavigate hooks
    const { addToCart } = useContext(CartContext);
    const navigate = useNavigate();

    useEffect(() => {
        fetchProducts();
    }, [skip]);

    const fetchProducts = async () => {
        setLoading(true);
        setError('');
        try {
            const response = await api.get(`/products?limit=${limit}&skip=${skip}`);
            if (response.data.status) {
                setProducts(response.data.data.products);
                setTotal(response.data.data.total);
            } else {
                setError('Failed to load products');
            }
        } catch (err) {
            setError('Error fetching products from server');
        } finally {
            setLoading(false);
        }
    };

    const handleNext = () => {
        if (skip + limit < total) {
            setSkip(skip + limit);
        }
    };

    const handlePrev = () => {
        if (skip - limit >= 0) {
            setSkip(skip - limit);
        }
    };

    // EXACT CHANGE HERE: Use the CartContext function to update state instantly
    const handleAddToCart = async (product) => {
        const success = await addToCart(product);
        if (!success) {
            navigate('/login');
        }
    };

    return (
        <div>
            <h2>All Products</h2>
            {error && <div style={{ color: 'red' }}>{error}</div>}

            {loading ? (
                <div>Loading products...</div>
            ) : (
                <>
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))', gap: '20px' }}>
                        {products.map(product => (
                            <div key={product.id} style={{ border: '1px solid #ddd', padding: '15px', borderRadius: '8px', display: 'flex', flexDirection: 'column' }}>
                                <img
                                    src={product.thumbnail}
                                    alt={product.title}
                                    style={{ width: '100%', height: '200px', objectFit: 'contain', marginBottom: '10px' }}
                                />
                                <h3>{product.title}</h3>
                                <p style={{ color: '#555', fontSize: '0.9em' }}>{product.category} | Rating: {product.rating}</p>
                                <h2 style={{ margin: '10px 0', color: '#B12704' }}>${product.price.toFixed(2)}</h2>

                                <div style={{ marginTop: 'auto', display: 'flex', gap: '10px', flexDirection: 'column' }}>
                                    <Link
                                        to={`/products/${product.id}`}
                                        style={{ textAlign: 'center', padding: '8px', background: '#e3e6e6', color: '#000', textDecoration: 'none', borderRadius: '4px' }}
                                    >
                                        View Details
                                    </Link>
                                    <button
                                        onClick={() => handleAddToCart(product)}
                                        style={{ padding: '8px', background: '#ffd814', border: 'none', borderRadius: '4px', cursor: 'pointer' }}
                                    >
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', marginTop: '30px', gap: '20px' }}>
                        <button
                            onClick={handlePrev}
                            disabled={skip === 0}
                            style={{ padding: '10px 20px', cursor: skip === 0 ? 'not-allowed' : 'pointer' }}
                        >
                            Previous
                        </button>
                        <span>
                            Showing {skip + 1} to {Math.min(skip + limit, total)} of {total}
                        </span>
                        <button
                            onClick={handleNext}
                            disabled={skip + limit >= total}
                            style={{ padding: '10px 20px', cursor: skip + limit >= total ? 'not-allowed' : 'pointer' }}
                        >
                            Next
                        </button>
                    </div>
                </>
            )}
        </div>
    );
}