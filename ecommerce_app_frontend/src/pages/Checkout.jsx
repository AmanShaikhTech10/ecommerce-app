import { useContext, useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { CartContext } from '../context/CartContext';
import { AuthContext } from '../context/AuthContext';
import api from '../api/axios';

export default function Checkout() {
    const { cart, fetchCart } = useContext(CartContext);
    const { user } = useContext(AuthContext);
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [invoiceFormat, setInvoiceFormat] = useState(4); // Default to standard

    if (!cart.items || cart.items.length === 0) {
        return (
            <div style={{ textAlign: 'center', marginTop: '50px' }}>
                <h2>Your Cart is Empty</h2>
                <Link to="/products" style={{ color: '#007185' }}>Go to Products</Link>
            </div>
        );
    }

    const handlePlaceOrder = async () => {
        setLoading(true);
        setError('');

        try {
            const response = await api.post('/orders', {
                invoice_format: invoiceFormat
            });
            if (response.data.status) {
                await fetchCart();
                navigate('/order-success', { state: { orderId: response.data.data.order_id } });
            }
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to place order. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    //Payment checkout logic
    // import axios from "axios";

    async function handlePayment() {
        setLoading(true);
        setError('');

        try {
            const response = await api.post('/payment/checkout', {
                invoice_format: Number(invoiceFormat)
            });

            const paymentUrl =
                response.data.payment_url ??
                response.data.checkout_url;

            if (!paymentUrl) {
                setError('Payment URL is missing. Please try again.');
                return;
            }

            window.location.href = paymentUrl;
        } catch (error) {
            setError(
                error.response?.data?.message ??
                'Payment initialization failed. Please try again.'
            );

            console.error(
                'Payment initialization failed:',
                error.response?.data ?? error.message
            );
        } finally {
            setLoading(false);
        }
    }


    //logic end

    return (
        <div style={{ maxWidth: '800px', margin: 'auto' }}>
            <h2>Checkout</h2>

            {error && <div style={{ color: 'red', marginBottom: '15px' }}>{error}</div>}

            <div style={{ background: '#f8f8f8', padding: '20px', borderRadius: '8px', marginBottom: '20px' }}>
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {user?.name}</p>
                <p><strong>Email:</strong> {user?.email}</p>
            </div>

            <div style={{ background: '#f8f8f8', padding: '20px', borderRadius: '8px', marginBottom: '20px' }}>
                <h3>Invoice Preferences</h3>
                <label style={{ display: 'block', marginBottom: '10px', fontWeight: 'bold' }}>Select Invoice Format:</label>
                <select
                    value={invoiceFormat}
                    onChange={(e) => setInvoiceFormat(Number(e.target.value))}
                    style={{ width: '100%', padding: '10px', borderRadius: '4px', border: '1px solid #ccc' }}
                >
                    <option value={1}>Format 1: Modern (Owner & Customer Copy)</option>
                    <option value={2}>Format 2: Classy (SwiftCart Logo & GST)</option>
                    <option value={3}>Format 3: Royal (Gold/Blue with UPI QR)</option>
                    <option value={4}>Format 4: Standard (Clean with Info QR)</option>
                </select>
            </div>

            <div style={{ background: '#f8f8f8', padding: '20px', borderRadius: '8px' }}>
                <h3>Order Summary</h3>
                <hr style={{ borderTop: '1px solid #ddd', marginBottom: '15px' }} />

                {cart.items.map(item => (
                    <div key={item.id} style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                        <span>{item.product_title} (x{item.quantity})</span>
                        <span>${Number(item.subtotal).toFixed(2)}</span>
                    </div>
                ))}

                <hr style={{ borderTop: '1px solid #ddd', margin: '15px 0' }} />

                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.2em', fontWeight: 'bold' }}>
                    <span>Total Quantity:</span>
                    <span>{cart.total_quantity}</span>
                </div>

                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.5em', fontWeight: 'bold', color: '#B12704', marginTop: '10px' }}>
                    <span>Grand Total:</span>
                    <span>${Number(cart.total_price).toFixed(2)}</span>
                </div>
                {/* payment button */}

                <button
                    onClick={handlePayment}
                    disabled={loading}
                    style={{
                        width: '100%',
                        padding: '15px',
                        background: '#0070ba',
                        color: '#fff',
                        border: 'none',
                        borderRadius: '8px',
                        fontSize: '1.2em',
                        fontWeight: 'bold',
                        cursor: loading ? 'not-allowed' : 'pointer',
                        marginTop: '20px'
                    }}
                >
                    {loading ? 'Processing Payment...' : 'Pay Now'}
                </button>


                {/* <button
                    onClick={handlePlaceOrder}
                    disabled={loading}
                    style={{
                        width: '100%',
                        padding: '15px',
                        background: '#ffd814',
                        border: 'none',
                        borderRadius: '8px',
                        fontSize: '1.2em',
                        fontWeight: 'bold',
                        cursor: loading ? 'not-allowed' : 'pointer',
                        marginTop: '20px'
                    }}
                >
                    {loading ? 'Processing...' : 'Place Order'}
                </button> */}
            </div>
        </div>
    );
}