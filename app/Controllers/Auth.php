<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function storeRegister()
    {
        $db = \Config\Database::connect();

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        $existing = $db->table('users')->where('email', $email)->get()->getRow();

        if ($existing) {
            return redirect()->back()->with('error', 'Email already registered.');
        }

        $db->table('users')->insert([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer',
            'status' => 'active'
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Account created successfully. Please login.');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function checkLogin()
    {
        $db = \Config\Database::connect();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $db->table('users')->where('email', $email)->get()->getRowArray();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account is not active.');
        }

        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'profile_image' => $user['profile_image'] ?? null,
            'is_logged_in' => true
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Logged out successfully.');
    }
}