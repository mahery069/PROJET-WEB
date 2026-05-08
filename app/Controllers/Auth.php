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
     * Display the registration form - Step 1
     */
    public function registerStep1()
    {
        return view('auth/formulaire');
    }

    /**
     * Handle registration step 1 - Store data in session
     */
    public function handleRegisterStep1()
    {
        return redirect()->to('/formulaire-step2');
    }

    /**
     * Display the registration form - Step 2
     */
    public function registerStep2()
    {
        return view('auth/formuaire2');
    }

    /**
     * Handle registration step 2 - Final registration
     */
    public function handleRegisterStep2()
    {
        // Check if step 1 is completed
        if (!session()->has('register_step1')) {
            return redirect()->to('/auth/register');
        }

        // Validate input
        if (!$this->validate([
            'taille' => 'required|numeric|greater_than[100]|less_than[250]',
            'poids' => 'required|numeric|greater_than[30]|less_than[200]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $step1 = session('register_step1');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');

        // TODO: Store user in database
        // Example:
        // $userModel = new UserModel();
        // $userModel->insert([
        //     'name'          => $step1['nom'],
        //     'email'         => $step1['email'],
        //     'password_hash' => password_hash($step1['password'], PASSWORD_BCRYPT),
        //     'taille'        => $taille,
        //     'poids'         => $poids,
        //     'created_at'    => date('Y-m-d H:i:s')
        // ]);

        // Clear session
        session()->remove('register_step1');

        return redirect()->to('/auth/login')
            ->with('success', 'Inscription réussie! Connectez-vous');
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

    /**
     * Display the profile form
     */
    public function profile()
    {
        return view('auth/profile');
    }

    /**
     * Handle profile update (placeholder)
     */
    public function updateProfile()
    {
        return redirect()->back()->with('success', 'Profil mis a jour.');
    }

    /**
     * Display objectifs and suggestions page
     */
    public function objectifs()
    {
        return view('auth/objectifs');
    }
}
