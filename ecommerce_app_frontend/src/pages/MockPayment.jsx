import { useContext, useState } from 'react';
// import { useSearchParams } from 'react-router-dom';
import { CartContext } from '../context/CartContext';
import { useNavigate, useSearchParams } from 'react-router-dom';
import api from '../api/axios';

export default function MockPayment() {
    const [searchParams] = useSearchParams();
    const paymentId = searchParams.get('payment_id');

    const { fetchCart } = useContext(CartContext);
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState('');
    const [order, setOrder] = useState(null);

    async function handlePayment(result) {
        if (!paymentId) {
            setMessage(
                'Payment reference is missing. Please restart checkout.'
            );
            return;
        }

        setLoading(true);
        setMessage('');
        setOrder(null);

        try {
            // Step 1: Submit the simulated payment result.
            const paymentResponse = await api.post('/payment/mock-result', {
                payment_id: paymentId,
                result,
            });

            if (paymentResponse.data.payment_status !== 'verified') {
                navigate('/cart', {
                    state: { message: 'Payment failed. Please try again.' }
                });
                return;
            }

            // Step 2: Create the order.
            const orderResponse = await api.post('/orders/create');

            if (!orderResponse.data.status) {
                setMessage(
                    orderResponse.data.message || 'Order creation failed.'
                );
                return;
            }

            // Step 3: Refresh cart state after order creation.
            await fetchCart();

            setOrder(orderResponse.data.data);
            setMessage(orderResponse.data.message);
        } catch (error) {
            setMessage(
                error.response?.data?.message ||
                'Something went wrong. Check the browser console and backend logs.'
            );

            console.error('Mock payment error:', error);
        } finally {
            setLoading(false);
        }
    }

    return (
        <div style={{ padding: '40px', textAlign: 'center' }}>
            <h1>Mock Payment Gateway</h1>
            <p>This is a simulated payment. </p>

            {!order && (
                <>
                    <button
                        onClick={() => handlePayment('success')}
                        disabled={loading}
                    >
                        {loading
                            ? 'Processing...'
                            : 'Simulate Successful Payment'}
                    </button>

                    <button
                        onClick={() => handlePayment('failed')}
                        disabled={loading}
                        style={{ marginLeft: '10px' }}
                    >
                        Simulate Failed Payment
                    </button>
                </>
            )}

            {message && <p role="status">{message}</p>}

            {order && (
                <>
                    <h2>Order Confirmed!</h2>
                    <p>Order ID: {order.order_id}</p>
                    <p>
                        Total: ${Number(order.total_amount).toFixed(2)}
                    </p>
                    <p>Invoice: {order.invoice_file}</p>
                    <p>
                        Email status: {order.email_sent ? 'Sent' : 'Not sent'}
                    </p>
                </>
            )}
        </div>
    );
}