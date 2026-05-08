<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends BaseController
{
    /**
     * Display the login form
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Handle login form submission
     */
    public function handleLogin()
    {
        // Validate input
        if (!$this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe invalide');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // TODO: Verify credentials against database
        // Example:
        // $userModel = new UserModel();
        // $user = $userModel->where('email', $email)->first();
        // 
        // if ($user && password_verify($password, $user['password_hash'])) {
        //     session()->set(['user_id' => $user['id']]);
        //     return redirect()->to('/dashboard');
        // }

        return redirect()->back()
            ->with('error', 'Identifiants invalides');
    }

    /**
     * Display the registration form
     */
    public function register()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Handle registration form submission
     */
    public function handleRegister()
    {
        // Validate input
        if (!$this->validate([
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // TODO: Store user in database
        // Example:
        // $userModel = new UserModel();
        // $userModel->insert([
        //     'email'         => $email,
        //     'password_hash' => password_hash($password, PASSWORD_BCRYPT),
        //     'created_at'    => date('Y-m-d H:i:s')
        // ]);

        return redirect()->to('/auth/login')
            ->with('success', 'Inscription réussie. Veuillez vous connecter.');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')
            ->with('success', 'Vous avez été déconnecté');
    }

    /**
     * Display forgot password form
     */
    public function forgotPassword()
    {
        return view('auth/forgot-password');
    }
}
