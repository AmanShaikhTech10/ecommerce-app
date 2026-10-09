<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use CodeIgniter\RESTful\ResourceController;

class CartController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $userId = session()->get('user_id');
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();
        $cart = $cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            return $this->respond([
                'status' => true,
                'message' => 'Cart retrieved',
                'data' => [
                    'items' => [],
                    'total_quantity' => 0,
                    'total_price' => 0
                ]
            ]);
        }
        $items = $cartItemModel->where('cart_id', $cart['id'])->findAll();
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($items as &$item) {
            $item['subtotal'] = $item['quantity'] * $item['product_price'];
            $totalQuantity += $item['quantity'];
            $totalPrice += $item['subtotal'];
        }

        return $this->respond([
            'status' => true,
            'message' => 'Cart retrieved',
            'data' => [
                'cart_id' => $cart['id'],
                'items' => $items,
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice
            ]
        ]);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $json = $this->request->getJSON();
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();
        $cart = $cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            $cartModel->insert(['user_id' => $userId]);
            $cartId = $cartModel->getInsertID();
        } else {
            $cartId = $cart['id'];
        }

        $productId = $json->product_id ?? null;
        if (!$productId) {
            return $this->failValidationErrors('Product ID is required');
        }

        $existingItem = $cartItemModel->where(['cart_id' => $cartId, 'product_id' => $productId])->first();
        $qty = $json->quantity ?? 1;

        if ($existingItem) {
            $cartItemModel->update($existingItem['id'], [
                'quantity' => $existingItem['quantity'] + $qty
            ]);
        } else {
            $cartItemModel->insert([
                'cart_id'       => $cartId,
                'product_id'    => $productId,
                'product_title' => $json->product_title,
                'product_price' => $json->product_price,
                'product_image' => $json->product_image ?? null,
                'quantity'      => $qty
            ]);
        }

        return $this->index(); // Return updated cart immediately
    }

    public function update($id = null)
    {
        $userId = session()->get('user_id');
        $json = $this->request->getJSON();
        $qty = $json->quantity ?? 0;

        if ($qty < 1) {
            return $this->fail('Invalid quantity');
        }

        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $cart = $cartModel->where('user_id', $userId)->first();
        if (!$cart) return $this->failUnauthorized();

        $item = $cartItemModel->where(['id' => $id, 'cart_id' => $cart['id']])->first();
        if (!$item) return $this->failNotFound('Item not found in your cart');

        $cartItemModel->update($id, ['quantity' => $qty]);

        return $this->index(); // Return updated cart
    }

    public function delete($id = null)
    {
        $userId = session()->get('user_id');
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $cart = $cartModel->where('user_id', $userId)->first();
        if (!$cart) return $this->failUnauthorized();

        $item = $cartItemModel->where(['id' => $id, 'cart_id' => $cart['id']])->first();
        if (!$item) return $this->failNotFound('Item not found');

        $cartItemModel->delete($id);

        return $this->index(); // Return updated cart
    }

    public function clear()
    {
        $userId = session()->get('user_id');
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $cart = $cartModel->where('user_id', $userId)->first();
        if ($cart) {
            $cartItemModel->where('cart_id', $cart['id'])->delete();
        }

        return $this->index(); // Return empty cart
    }
}