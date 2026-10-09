import { Link } from 'react-router-dom';

export default function OrderSuccess() {
    return (
        <div style={{ textAlign: 'center', marginTop: '50px' }}>
            <h1 style={{ color: '#007185' }}>Order Placed Successfully!</h1>
            <p style={{ fontSize: '1.2em', marginBottom: '20px' }}>
                Thank you for your purchase. We are processing your order.
            </p>
            <Link
                to="/products"
                style={{
                    padding: '12px 24px',
                    background: '#ffd814',
                    color: '#000',
                    textDecoration: 'none',
                    borderRadius: '20px',
                    fontWeight: 'bold'
                }}
            >
                Continue Shopping
            </Link>
        </div>
    );
}