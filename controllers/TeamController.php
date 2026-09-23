<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\ProjectTeam;

class TeamController extends Controller
{
    public function add($projectId)
    {
        Middleware::authRequired();
        
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        if (!$isLeaderOrAdmin) {
            Session::flash('error', 'Must be leader or admin to add team members.');
            return $this->redirect('/project/' . $projectId);
        }
        
        $userId = $_POST['user_id'] ?? null;
        $canEdit = isset($_POST['can_edit']) ? 1 : 0;
        
        if ($userId) {
            // Check if already a member
            if (!$teamModel->isTeamMember($projectId, $userId)) {
                $teamModel->create([
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'is_leader' => 0,
                    'can_edit' => $canEdit
                ]);
                Session::flash('success', 'Team member added.');
            } else {
                Session::flash('error', 'User is already in the team.');
            }
        }
        
        return $this->redirect('/project/' . $projectId);
    }

    public function toggleAccess($projectId, $teamId)
    {
        Middleware::authRequired();
        
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        if (!$isLeaderOrAdmin) {
            Session::flash('error', 'Must be leader to edit access.');
            return $this->redirect('/project/' . $projectId);
        }
        
        if ($teamId) {
            $teamModel->toggleEditAccess($teamId);
        }
        
        return $this->redirect('/project/' . $projectId);
    }

    public function remove($projectId, $teamId)
    {
        Middleware::authRequired();
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($projectId, Session::get('user_id'));
        if (!$isLeaderOrAdmin) {
            Session::flash('error', 'Must be leader to remove team members.');
            return $this->redirect('/project/' . $projectId);
        }
        
        if ($teamId) {
            $teamModel->delete($teamId);
            Session::flash('success', 'Team member removed.');
        }
        
        return $this->redirect('/project/' . $projectId);
    }
}
