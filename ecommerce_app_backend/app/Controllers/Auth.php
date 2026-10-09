<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function signup()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];
        if (!$this->validate($rules)) {
            return $this->respond([
                'status'  => false,
                'message' => $this->validator->getErrors()
            ], 400);
        }

        $userModel = new UserModel();
        $data = [
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => 'user'
        ];

        $userModel->insert($data);
        return $this->respond([
            'status'  => true,
            'message' => 'User registered successfully',
            'data'    => []
        ], 201);
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status'  => false,
                'message' => $this->validator->getErrors()
            ], 400);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getVar('email'))->first();

        if (!$user || !password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->respond([
                'status'  => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        session()->set([
            'user_id'   => $user['id'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        unset($user['password']);

        return $this->respond([
            'status'  => true,
            'message' => 'Login successful',
            'data'    => $user
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return $this->respond([
            'status'  => true,
            'message' => 'Logged out successfully',
            'data'    => []
        ]);
    }

    public function me()
    {
        if (!session()->get('logged_in')) {
            return $this->respond([
                'status'  => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));
        if ($user) {
            unset($user['password']);
        }
        return $this->respond([
            'status'  => true,
            'message' => 'Current user data',
            'data'    => $user
        ]);
    }
}