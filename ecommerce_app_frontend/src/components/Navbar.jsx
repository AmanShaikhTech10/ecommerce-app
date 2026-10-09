import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { CartContext } from '../context/CartContext';

export default function Navbar() {
    const { user, logout } = useContext(AuthContext);
    const { cart } = useContext(CartContext);
    const navigate = useNavigate();

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    return (
        <nav style={{ display: 'flex', gap: '1rem', padding: '1rem', background: '#232f3e', color: 'white', alignItems: 'center' }}>
            <Link to="/" style={{ color: 'white', textDecoration: 'none' }}>Home</Link>
            <Link to="/products" style={{ color: 'white', textDecoration: 'none' }}>Products</Link>
            <Link to="/cart" style={{ color: 'white', textDecoration: 'none', fontWeight: 'bold' }}>
                Cart ({cart.total_quantity || 0})
            </Link>

            <div style={{ flex: 1 }}></div>

            {user ? (
                <>
                    <span style={{ marginRight: '1rem' }}>Hello, {user.name}</span>
                    {user.role === 'admin' && (
                        <Link to="/admin" style={{ color: '#ff9900', textDecoration: 'none', marginRight: '1rem' }}>Admin Dashboard</Link>
                    )}
                    <button onClick={handleLogout} style={{ cursor: 'pointer', padding: '0.5rem 1rem' }}>Logout</button>
                </>
            ) : (
                <>
                    <Link to="/login" style={{ color: 'white', textDecoration: 'none' }}>Login</Link>
                    <Link to="/signup" style={{ color: 'white', textDecoration: 'none' }}>Signup</Link>
                </>
            )}
        </nav>
    );
}