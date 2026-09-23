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
}

