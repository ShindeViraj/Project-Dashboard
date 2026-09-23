<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\User;
use Models\StepTemplate;

class AdminController extends Controller
{
    public function users()
    {
        Middleware::adminRequired();
        $userModel = new User();
        $users = $userModel->findAll();
        return $this->view('admin/users', ['users' => $users]);
    }

    public function addUser()
    {
        Middleware::adminRequired();
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $department = $_POST['department'] ?? '';
        
        if ($name && $email && $password) {
            $userModel = new User();
            $userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'department' => $department
            ]);
            Session::flash('success', 'User added successfully.');
        } else {
            Session::flash('error', 'Missing required fields.');
        }
        
        return $this->redirect('/admin/users');
    }

    public function deleteUser($id)
    {
        Middleware::adminRequired();
        
        if ($id == Session::get('user_id')) {
            Session::flash('error', 'You cannot delete yourself.');
            return $this->redirect('/admin/users');
        }
        
        $userModel = new User();
        $userModel->delete($id);
        Session::flash('success', 'User deleted.');
        
        return $this->redirect('/admin/users');
    }

    public function steps()
    {
        Middleware::adminRequired();
        $stepTemplateModel = new StepTemplate();
        $steps = $stepTemplateModel->findAll();
        return $this->view('admin/steps', ['steps' => $steps]);
    }

    public function addStep()
    {
        Middleware::adminRequired();
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $stepNumber = $_POST['step_number'] ?? 0;
        
        if ($title && $stepNumber) {
            $stepTemplateModel = new StepTemplate();
            $stepTemplateModel->create([
                'title' => $title,
                'description' => $description,
                'step_number' => $stepNumber
            ]);
            Session::flash('success', 'Step template added.');
        }
        
        return $this->redirect('/admin/steps');
    }

    public function deleteStep($id)
    {
        Middleware::adminRequired();
        
        $stepTemplateModel = new StepTemplate();
        $step = $stepTemplateModel->find($id);
        
        if ($step && isset($step['is_default']) && $step['is_default'] == 1) {
            Session::flash('error', 'Cannot delete default steps.');
        } else {
            $stepTemplateModel->delete($id);
            Session::flash('success', 'Step template deleted.');
        }
        
        return $this->redirect('/admin/steps');
    }
}
