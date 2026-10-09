<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected $format = 'json';
    private $apiUrl = 'https://dummyjson.com/products';

    public function index()
    {
        $limit = $this->request->getVar('limit') ?? 12;
        $skip = $this->request->getVar('skip') ?? 0;
        $client = \Config\Services::curlrequest();
        try {
            // Added ['verify' => false] to bypass local SSL certificate issues
            $response = $client->request('GET', "{$this->apiUrl}?limit={$limit}&skip={$skip}", [
                'verify' => false
            ]);
            $data = json_decode($response->getBody(), true);
            return $this->respond([
                'status'  => true,
                'message' => 'Products fetched successfully',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status'  => false,
                'message' => 'Failed to fetch products',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id = null)
    {
        if (!$id) {
            return $this->respond([
                'status'  => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $client = \Config\Services::curlrequest();
        try {
            // Added ['verify' => false] to bypass local SSL certificate issues
            $response = $client->request('GET', "{$this->apiUrl}/{$id}", [
                'verify' => false
            ]);
            $data = json_decode($response->getBody(), true);
            if (isset($data['message']) && strpos($data['message'], 'not found') !== false) {
                 return $this->respond([
                    'status'  => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return $this->respond([
                'status'  => true,
                'message' => 'Product fetched successfully',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status'  => false,
                'message' => 'Failed to fetch product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}