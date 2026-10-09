<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use CodeIgniter\RESTful\ResourceController;

class PaymentController extends ResourceController
{
    protected $format = 'json';

    public function checkout()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->respond([
                'success' => false,
                'message' => 'Please log in before checkout.'
            ], 401);
        }

        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $cart = $cartModel->where('user_id', $userId)->first();

        if (!$cart) {
            return $this->respond([
                'success' => false,
                'message' => 'Cart not found.'
            ], 404);
        }

        $items = $cartItemModel
            ->where('cart_id', $cart['id'])
            ->findAll();

        if (empty($items)) {
            return $this->respond([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['product_price']
                * (int) $item['quantity'];
        }

        // $json = $this->request->getJSON();
        $json = $this->request->getJSON(true) ?? [];
        //demo

        $reference = bin2hex(random_bytes(16));

        session()->set('mock_payment', [
            'reference' => $reference,
            'user_id' => $userId,
            'amount' => $total,
            // 'invoice_format' => (int) ($json->invoice_format ?? 4),
            'invoice_format' => in_array(
                (int) ($json['invoice_format'] ?? 4),
                [1, 2, 3, 4],
                true
            ) ? (int) ($json['invoice_format'] ?? 4) : 4,
            'status' => 'pending'
        ]);

        return $this->respond([
            'success' => true,
            'message' => 'Mock payment session created.',
            'payment_url' => 'http://localhost:5173/mock-payment?payment_id='
                . $reference,
            'payment_id' => $reference,
            'amount' => $total
        ]);
    }

    public function mockResult()
    {
        $userId = session()->get('user_id');
        $json = $this->request->getJSON(true) ?? [];

        $payment = session()->get('mock_payment');

        if (
            !$userId ||
            !$payment ||
            $payment['user_id'] != $userId ||
            !isset($json['payment_id']) ||
            !hash_equals(
                $payment['reference'],
                (string) $json['payment_id']
            ) ||
            $payment['status'] !== 'pending'
        ) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid or expired mock payment session.'
            ], 400);
        }

        $result = $json['result'] ?? '';

        if (!in_array($result, ['success', 'failed'], true)) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid mock payment result.'
            ], 400);
        }

        $payment['status'] = $result === 'success'
            ? 'verified'
            : 'failed';

        session()->set('mock_payment', $payment);

        return $this->respond([
            'success' => true,
            'payment_status' => $payment['status'],
            'message' => $result === 'success'
                ? 'Mock payment succeeded.'
                : 'Mock payment failed.'
        ]);
    }
}