<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use CodeIgniter\RESTful\ResourceController;

class AdminController extends ResourceController
{
    protected $format = 'json';
    public function stats()
    {
        $userModel = new UserModel();$orderModel = new OrderModel();

        $totalUsers = $userModel->countAllResults();$totalOrders = $orderModel->countAllResults();$revenueRow = $orderModel->selectSum('total_amount')->get()->getRow();$totalRevenue = $revenueRow ? $revenueRow->total_amount : 0;

        return $this->respond([
            'status' => true,
            'data'   => [
                'total_users'   => $totalUsers,
                'total_orders'  => $totalOrders,
                'total_revenue' => $totalRevenue ?: 0
            ]
        ]);
    }

    public function users()
    {
        $userModel = new UserModel();
        $users =$userModel->select('id, name, email, role, created_at')->orderBy('created_at', 'DESC')->findAll();
        return $this->respond([
            'status' => true,
            'data'   => $users
        ]);
    }

    public function orders()
    {
        $db = \Config\Database::connect();$builder = $db->table('orders');$builder->select('orders.*, users.name as customer_name, users.email as customer_email');
        $builder->join('users', 'users.id = orders.user_id', 'left');$builder->orderBy('orders.created_at', 'DESC');
        $orders =$builder->get()->getResultArray();
        return $this->respond([
            'status' => true,
            'data'   => $orders
        ]);
    }
}