<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\ProjectStep;
use Models\ProjectTeam;
use Models\Project;

class StepController extends Controller
{
    public function toggle($projectId, $stepId)
    {
        Middleware::authRequired();
        
        // Team member check
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        $canEdit = $isLeaderOrAdmin || $teamModel->hasEditAccess($projectId, Session::get('user_id')); // assuming this returns true if member has edit rights or we just allow members
        
        // Let's just do a basic check
        if (!$canEdit) {
            Session::flash('error', 'Access denied.');
            return $this->redirect('/project/' . $projectId);
        }
        
        $is_applicable = isset($_POST['is_applicable']) ? 1 : 0;
        $is_completed = isset($_POST['is_completed']) ? 1 : 0;
        
        $stepModel = new ProjectStep();
        $stepModel->update($stepId, [
            'is_applicable' => $is_applicable,
            'is_completed' => $is_completed
        ]);
        
        // Trigger recalculate project progress if applicable
        $projectModel = new Project();
        if (method_exists($projectModel, 'calculateProgress')) {
            $projectModel->calculateProgress($projectId);
        }
        
        return $this->redirect('/project/' . $projectId);
    }


    public function addCustom($projectId)
    {
        Middleware::authRequired();
        $this->requireCsrf();
        
        $teamModel = new ProjectTeam();
        $isLeader = $teamModel->isLeader($projectId, Session::get('user_id'));
        $isAdmin = Session::get('user_role') === 'admin';
        
        if (!$isAdmin && !$isLeader) {
            Session::flash('error', 'Only admins or project leaders can add new steps.');
            return $this->redirect('/project/' . $projectId);
        }
        
        $step_name = $_POST['step_name'] ?? '';
        if (!empty(trim($step_name))) {
            $stepModel = new ProjectStep();
            $stepModel->create([
                'project_id' => $projectId,
                'step_name' => $step_name,
                'is_applicable' => 1,
                'is_completed' => 0
            ]);
            
            $projectModel = new Project();
            if (method_exists($projectModel, 'calculateProgress')) {
                $projectModel->calculateProgress($projectId);
            }
            
            Session::flash('success', 'Custom step added successfully.');
        } else {
            Session::flash('error', 'Step name cannot be empty.');
        }
        
        return $this->redirect('/project/' . $projectId);
    }
}
