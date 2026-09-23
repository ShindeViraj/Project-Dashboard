<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\Project;

class DashboardController extends Controller
{
    public function master()
    {
        Middleware::authRequired();
        
        $projectModel = new Project();
        $projects = $projectModel->findOngoing();
        $totalCount = count($projects);
        
        $user = [
            'id' => Session::get('user_id'),
            'name' => Session::get('user_name'),
            'role' => Session::get('user_role')
        ];
        
        return $this->view('dashboard/master', [
            'projects' => $projects,
            'totalCount' => $totalCount,
            'user' => $user
        ]);
    }

    public function completed()
    {
        Middleware::authRequired();
        
        $projectModel = new Project();
        $projects = $projectModel->findCompleted();
        
        return $this->view('dashboard/completed', [
            'projects' => $projects
        ]);
    }
}
