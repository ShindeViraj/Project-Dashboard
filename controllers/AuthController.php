<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        Middleware::guestOnly();
        return $this->view('auth/login');
    }

    public function login()
    {
        Middleware::guestOnly();
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role']);
            Session::set('user_department', $user['department']);
            
            Session::flash('success', 'Welcome back!');
            return $this->redirect('/dashboard');
        } else {
            Session::flash('error', 'Invalid email or password.');
            return $this->redirect('/login');
        }
    }

    public function logout()
    {
        Session::destroy();
        return $this->redirect('/login');
    }
}
