import { Link } from 'react-router-dom';

export default function Home() {
    return (
        <div style={{ textAlign: 'center', marginTop: '50px' }}>
            <h1>Welcome to the E-Commerce Store</h1>
            <p>Find the best products at the best prices.</p>
            <Link to="/products" style={{
                display: 'inline-block',
                marginTop: '20px',
                padding: '10px 20px',
                background: '#ff9900',
                color: '#fff',
                textDecoration: 'none',
                borderRadius: '5px'
            }}>
                Shop Now
            </Link>
        </div>
    );
}