<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\SubTask;
use Models\ProjectStep;
use Models\ProjectTeam;

class SubTaskController extends Controller
{
    private function updateProgress($projectId) {
        $projectModel = new \Models\Project();
        if (method_exists($projectModel, 'calculateProgress')) {
            $projectModel->calculateProgress($projectId);
        }
    }
    public function add($projectId, $stepId)
    {
        Middleware::authRequired();
        
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        $canEdit = $isLeaderOrAdmin || $teamModel->hasEditAccess($projectId, Session::get('user_id'));
        
        if (!$canEdit) {
            Session::flash('error', 'Access denied.');
            return $this->redirect('/project/' . $projectId);
        }
        
        $title = $_POST['task_name'] ?? '';
        
        if ($stepId && $title) {
            $subTaskModel = new SubTask();
            $subTaskModel->create([
                'project_step_id' => $stepId,
                'task_description' => $title,
                'is_completed' => 0,
                'created_by' => Session::get('user_id')
            ]);
            Session::flash('success', 'Sub-task added.');
            $this->updateProgress($projectId);
        } else {
            Session::flash('error', 'Task description is required.');
        }
        
        return $this->redirect('/project/' . $projectId);
    }

    public function toggle($projectId, $subtaskId)
    {
        Middleware::authRequired();
        
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        $canEdit = $isLeaderOrAdmin || $teamModel->hasEditAccess($projectId, Session::get('user_id'));
        
        if ($canEdit) {
            $subTaskModel = new SubTask();
            $subTaskModel->toggleCompleted($subtaskId);
            $this->updateProgress($projectId);
        }
        
        return $this->redirect('/project/' . $projectId);
    }

    public function delete($projectId, $subtaskId)
    {
        Middleware::authRequired();
        
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        $canEdit = $isLeaderOrAdmin || $teamModel->hasEditAccess($projectId, Session::get('user_id'));
        
        if ($canEdit) {
            $subTaskModel = new SubTask();
            $subTaskModel->delete($subtaskId);
            Session::flash('success', 'Sub-task deleted.');
            $this->updateProgress($projectId);
        }
        
        return $this->redirect('/project/' . $projectId);
    }
}



