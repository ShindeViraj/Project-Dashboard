<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\Project;
use Models\StepTemplate;
use Models\ProjectStep;
use Models\ProjectTeam;
use Models\SubTask;
use Models\MomEntry;
use Models\User;

class ProjectController extends Controller
{
    public function create()
    {
        Middleware::authRequired();
        $userModel = new User();
        $users = $userModel->findAll();
        return $this->view('project/create', ['users' => $users]);
    }

    public function store()
    {
        Middleware::authRequired();
        
        $project_name = $_POST['project_name'] ?? '';
        $company_name = $_POST['company_name'] ?? '';
        $issue_date = $_POST['issue_date'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $problem_statement = $_POST['problem_statement'] ?? '';
        
        if (empty($project_name)) {
            Session::flash('error', 'Project Name is required.');
            return $this->redirect('/project/create');
        }
        
        $projectModel = new Project();
        $projectId = $projectModel->create([
            'project_name' => $project_name,
            'company_name' => $company_name,
            'issue_date' => $issue_date,
            'start_date' => $start_date,
            'problem_statement' => $problem_statement,
            'created_by' => Session::get('user_id'),
            'is_completed' => 0,
            'progress' => 0
        ]);
        
        if ($projectId) {
            // Clone default step_templates into project_steps
            $stepTemplateModel = new StepTemplate();
            $templates = $stepTemplateModel->findAll();
            
            $projectStepModel = new ProjectStep();
            foreach ($templates as $template) {
                if ($template['is_default']) {
                    $projectStepModel->create([
                        'project_id' => $projectId,
                        'step_name' => $template['step_name'],
                        'is_applicable' => 1,
                        'is_completed' => 0
                    ]);
                }
            }
            
            // Add current user as team leader in project_team
            $projectTeamModel = new ProjectTeam();
            $projectTeamModel->create([
                'project_id' => $projectId,
                'user_id' => Session::get('user_id'),
                'can_edit' => 1,
                'is_leader' => 1
            ]);
            
            // Add selected team members
            if (isset($_POST['team_members']) && is_array($_POST['team_members'])) {
                foreach ($_POST['team_members'] as $memberId) {
                    if ($memberId != Session::get('user_id')) {
                        $projectTeamModel->create([
                            'project_id' => $projectId,
                            'user_id' => $memberId,
                            'can_edit' => 1,
                            'is_leader' => 0
                        ]);
                    }
                }
            }
            
            Session::flash('success', 'Project created successfully.');
            return $this->redirect('/project/' . $projectId);
        }
        
        Session::flash('error', 'Failed to create project.');
        return $this->redirect('/project/create');
    }

    public function show($id)
    {
        Middleware::authRequired();
        
        $projectModel = new Project();
        $project = $projectModel->find($id);
        
        if (!$project) {
            Session::flash('error', 'Project not found.');
            return $this->redirect('/dashboard');
        }
        
        $stepModel = new ProjectStep();
        $steps = $stepModel->findByProject($id);
        
        $subTaskModel = new SubTask();
        $subtasks = [];
        foreach ($steps as $step) {
            $subtasks[$step['id']] = $subTaskModel->findByStep($step['id']);
        }
        
        $teamModel = new ProjectTeam();
        $team = $teamModel->findByProject($id);
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($id, Session::get('user_id'));
        
        $momModel = new MomEntry();
        $momEntries = $momModel->findByProject($id);
        
        return $this->view('project/view', [
            'project' => $project,
            'steps' => $steps,
            'subtasks' => $subtasks,
            'team' => $team,
            'momEntries' => $momEntries,
            'isLeaderOrAdmin' => $isLeaderOrAdmin
        ]);
    }

    public function complete($id)
    {
        Middleware::authRequired();
        // Check if admin or leader
        $teamModel = new ProjectTeam();
        $isLeaderOrAdmin = Session::get('user_role') === 'admin' || $teamModel->isLeader($id, Session::get('user_id'));
        
        if (!$isLeaderOrAdmin) {
            Session::flash('error', 'Access denied.');
            return $this->redirect('/project/' . $id);
        }
        
        $projectModel = new Project();
        $projectModel->update($id, [
            'is_completed' => 1,
            'completed_date' => date('Y-m-d')
        ]);
        
        Session::flash('success', 'Project marked as completed.');
        return $this->redirect('/project/' . $id);
    }

    public function toggleStatus($id)
    {
        Middleware::authRequired();
        $this->requireCsrf();
        
        $projectModel = new Project();
        $project = $projectModel->find($id);
        
        if (!$project) {
            $this->redirect('/dashboard');
        }
        
        $isAdmin = Session::get('user_role') === 'admin';
        if (!$isAdmin) {
            Session::flash('error', 'Only admins can update PO and Tax Invoice status.');
            return $this->redirect('/project/' . $id);
        }
        
        $po_status = isset($_POST['po_status']) ? 1 : 0;
        $tax_invoice_status = isset($_POST['tax_invoice_status']) ? 1 : 0;
        
        $updateData = [
            'po_status' => $po_status,
            'tax_invoice_status' => $tax_invoice_status
        ];
        
        // Handle file uploads
        $poPath = $this->uploadFile('po_document');
        if ($poPath !== false) {
            $updateData['po_document_path'] = $poPath;
        }
        
        $taxPath = $this->uploadFile('tax_invoice_document');
        if ($taxPath !== false) {
            $updateData['tax_invoice_document_path'] = $taxPath;
        }

        $projectModel->update($id, $updateData);
        Session::flash('success', 'Project statuses updated successfully.');
        $this->redirect('/project/' . $id);
    }
    
    public function addMom($id)
    {
        Middleware::authRequired();
        $this->requireCsrf();
        
        $projectModel = new Project();
        $project = $projectModel->find($id);
        
        if (!$project) {
            $this->redirect('/dashboard');
        }
        
        $teamModel = new ProjectTeam();
        $isLeader = $teamModel->isLeader($id, Session::get('user_id'));
        $isAdmin = Session::get('user_role') === 'admin';
        
        if (!$isAdmin && !$isLeader) {
            Session::flash('error', 'Only the project leader or admin can add MOM entries.');
            return $this->redirect('/project/' . $id);
        }
        
        $content = $_POST['content'] ?? '';
        if (empty(trim($content))) {
            Session::flash('error', 'MOM content cannot be empty.');
            return $this->redirect('/project/' . $id);
        }
        
        $attachmentPath = $this->uploadFile('mom_attachment');
        
        $momModel = new MomEntry();
        $momModel->create([
            'project_id' => $id,
            'content' => $content,
            'created_by' => Session::get('user_id'),
            'attachment_path' => $attachmentPath ?: null
        ]);
        
        Session::flash('success', 'MOM entry added successfully.');
        $this->redirect('/project/' . $id);
    }
    
    private function uploadFile($inputName)
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        $file = $_FILES[$inputName];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if ($file['size'] > $maxSize) {
            Session::flash('error', 'File size exceeds 5MB limit.');
            return false;
        }
        
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedMimes)) {
            Session::flash('error', 'Invalid file type. Only PDF, JPG, and PNG are allowed.');
            return false;
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destPath = __DIR__ . '/../public/uploads/documents/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/documents/' . $filename;
        }
        
        return false;
    }
}

