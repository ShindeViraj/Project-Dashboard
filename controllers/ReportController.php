<?php

namespace Controllers;

use Core\Controller;
use Core\Session;
use Core\Middleware;
use Models\Project;
use Models\ProjectStep;
use Models\ProjectTeam;
use Models\SubTask;
use Models\MomEntry;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    private function getProjectData($id)
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);
        
        if (!$project) return null;
        
        $projectStepModel = new ProjectStep();
        $steps = $projectStepModel->findByProject($id);
        
        $subTaskModel = new SubTask();
        foreach ($steps as &$step) {
            $step['sub_tasks'] = $subTaskModel->findByStep($step['id']);
        }
        
        $teamModel = new ProjectTeam();
        $teamMembers = $teamModel->findByProject($id);
        
        $momModel = new MomEntry();
        $momEntries = $momModel->findByProject($id);
        
        return [
            'project' => $project,
            'steps' => $steps,
            'teamMembers' => $teamMembers,
            'momEntries' => $momEntries
        ];
    }

    public function show($id)
    {
        Middleware::authRequired();
        
        $data = $this->getProjectData($id);
        if (!$data) {
            Session::flash('error', 'Project not found.');
            return $this->redirect('/dashboard');
        }
        
        return $this->view('project/report', $data);
    }

    public function pdf($id)
    {
        Middleware::authRequired();
        
        $data = $this->getProjectData($id);
        if (!$data) {
            Session::flash('error', 'Project not found.');
            return $this->redirect('/dashboard');
        }
        
        // Render view for PDF
        ob_start();
        extract($data);
        $viewPath = __DIR__ . '/../views/project/report.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<h1>Project Report</h1><p>View not found.</p>";
        }
        $html = ob_get_clean();
        
        // Dompdf configuration
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream("project_{$id}_report.pdf", ["Attachment" => true]);
        exit;
    }
}
