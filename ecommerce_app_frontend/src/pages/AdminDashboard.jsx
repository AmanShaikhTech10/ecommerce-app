import { useState, useEffect } from 'react';
import api from '../api/axios';

export default function AdminDashboard() {
    const [stats, setStats] = useState({ total_users: 0, total_orders: 0, total_revenue: 0 });
    const [users, setUsers] = useState([]);
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [activeTab, setActiveTab] = useState('orders'); // 'orders' or 'users'

    useEffect(() => {
        fetchAdminData();
    }, []);

    const fetchAdminData = async () => {
        setLoading(true);
        try {
            const [statsRes, usersRes, ordersRes] = await Promise.all([
                api.get('/admin/stats'),
                api.get('/admin/users'),
                api.get('/admin/orders')
            ]);

            if (statsRes.data.status) setStats(statsRes.data.data);
            if (usersRes.data.status) setUsers(usersRes.data.data);
            if (ordersRes.data.status) setOrders(ordersRes.data.data);
        } catch (err) {
            setError('Failed to fetch dashboard data. Ensure you have admin privileges.');
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div>Loading Admin Dashboard...</div>;
    if (error) return <div style={{ color: 'red' }}>{error}</div>;

    return (
        <div style={{ maxWidth: '1200px', margin: 'auto' }}>
            <h1 style={{ color: '#232f3e' }}>Admin Dashboard</h1>

            {/* Stats Cards */}
            <div style={{ display: 'flex', gap: '20px', marginBottom: '30px' }}>
                <div style={{ flex: 1, background: '#f8f8f8', padding: '20px', borderRadius: '8px', borderLeft: '5px solid #ff9900' }}>
                    <h3 style={{ margin: 0, color: '#555' }}>Total Revenue</h3>
                    <h2 style={{ margin: '10px 0 0 0', color: '#B12704' }}>${Number(stats.total_revenue).toFixed(2)}</h2>
                </div>
                <div style={{ flex: 1, background: '#f8f8f8', padding: '20px', borderRadius: '8px', borderLeft: '5px solid #007185' }}>
                    <h3 style={{ margin: 0, color: '#555' }}>Total Orders</h3>
                    <h2 style={{ margin: '10px 0 0 0' }}>{stats.total_orders}</h2>
                </div>
                <div style={{ flex: 1, background: '#f8f8f8', padding: '20px', borderRadius: '8px', borderLeft: '5px solid #232f3e' }}>
                    <h3 style={{ margin: 0, color: '#555' }}>Total Users</h3>
                    <h2 style={{ margin: '10px 0 0 0' }}>{stats.total_users}</h2>
                </div>
            </div>

            {/* Tabs */}
            <div style={{ display: 'flex', gap: '10px', marginBottom: '20px' }}>
                <button
                    onClick={() => setActiveTab('orders')}
                    style={{ padding: '10px 20px', cursor: 'pointer', background: activeTab === 'orders' ? '#232f3e' : '#e3e6e6', color: activeTab === 'orders' ? '#fff' : '#000', border: 'none', borderRadius: '4px' }}
                >
                    Recent Orders
                </button>
                <button
                    onClick={() => setActiveTab('users')}
                    style={{ padding: '10px 20px', cursor: 'pointer', background: activeTab === 'users' ? '#232f3e' : '#e3e6e6', color: activeTab === 'users' ? '#fff' : '#000', border: 'none', borderRadius: '4px' }}
                >
                    User Management
                </button>
            </div>

            {/* Tab Content */}
            {activeTab === 'orders' && (
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead>
                        <tr style={{ background: '#f8f8f8', borderBottom: '2px solid #ddd' }}>
                            <th style={{ padding: '10px', textAlign: 'left' }}>Order ID</th>
                            <th style={{ padding: '10px', textAlign: 'left' }}>Customer</th>
                            <th style={{ padding: '10px', textAlign: 'left' }}>Email</th>
                            <th style={{ padding: '10px', textAlign: 'center' }}>Total</th>
                            <th style={{ padding: '10px', textAlign: 'center' }}>Status</th>
                            <th style={{ padding: '10px', textAlign: 'center' }}>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        {orders.map(order => (
                            <tr key={order.id} style={{ borderBottom: '1px solid #ddd' }}>
                                <td style={{ padding: '10px' }}>#{order.id}</td>
                                <td style={{ padding: '10px' }}>{order.customer_name}</td>
                                <td style={{ padding: '10px' }}>{order.customer_email}</td>
                                <td style={{ padding: '10px', textAlign: 'center', fontWeight: 'bold' }}>${Number(order.total_amount).toFixed(2)}</td>
                                <td style={{ padding: '10px', textAlign: 'center' }}>
                                    <span style={{ background: '#d4edda', color: '#155724', padding: '3px 8px', borderRadius: '12px', fontSize: '0.9em' }}>{order.status}</span>
                                </td>
                                <td style={{ padding: '10px', textAlign: 'center' }}>{new Date(order.created_at).toLocaleDateString()}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}

            {activeTab === 'users' && (
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead>
                        <tr style={{ background: '#f8f8f8', borderBottom: '2px solid #ddd' }}>
                            <th style={{ padding: '10px', textAlign: 'left' }}>ID</th>
                            <th style={{ padding: '10px', textAlign: 'left' }}>Name</th>
                            <th style={{ padding: '10px', textAlign: 'left' }}>Email</th>
                            <th style={{ padding: '10px', textAlign: 'center' }}>Role</th>
                            <th style={{ padding: '10px', textAlign: 'center' }}>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map(u => (
                            <tr key={u.id} style={{ borderBottom: '1px solid #ddd' }}>
                                <td style={{ padding: '10px' }}>{u.id}</td>
                                <td style={{ padding: '10px' }}>{u.name}</td>
                                <td style={{ padding: '10px' }}>{u.email}</td>
                                <td style={{ padding: '10px', textAlign: 'center' }}>
                                    <span style={{ background: u.role === 'admin' ? '#ffeeba' : '#e2e3e5', padding: '3px 8px', borderRadius: '12px', fontSize: '0.9em' }}>
                                        {u.role}
                                    </span>
                                </td>
                                <td style={{ padding: '10px', textAlign: 'center' }}>{new Date(u.created_at).toLocaleDateString()}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}
        </div>
    );
}