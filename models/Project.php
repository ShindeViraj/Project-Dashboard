<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class Project extends Model {
    protected $table = 'projects';

    public function findOngoing() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE is_completed = 0 ORDER BY start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCompleted() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE is_completed = 1 ORDER BY completed_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByUser($userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT p.* FROM {$this->table} p 
                              LEFT JOIN project_team pt ON p.id = pt.project_id 
                              WHERE p.created_by = :userId OR pt.user_id = :userId
                              GROUP BY p.id ORDER BY p.start_date DESC");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($keyword) {
        $db = Database::getInstance();
        $keyword = '%' . $keyword . '%';
        $stmt = $db->prepare("SELECT * FROM {$this->table} 
                              WHERE project_name LIKE :keyword 
                              OR company_name LIKE :keyword 
                              OR problem_statement LIKE :keyword 
                              ORDER BY start_date DESC");
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }

            public function calculateProgress($projectId) {
        $db = Database::getInstance();
        
        // Get all applicable steps
        $stmt = $db->prepare("SELECT id, is_completed FROM project_steps WHERE project_id = :project_id AND is_applicable = 1");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        $steps = $stmt->fetchAll();
        
        if (empty($steps)) {
            $updateStmt = $db->prepare("UPDATE {$this->table} SET progress = 0 WHERE id = :id");
            $updateStmt->execute([':id' => $projectId]);
            return 0;
        }

        $totalStepPercentages = 0;

        foreach ($steps as $step) {
            // Get subtasks for this step
            $subStmt = $db->prepare("SELECT COUNT(*) as total, COALESCE(SUM(is_completed), 0) as completed FROM sub_tasks WHERE project_step_id = :step_id");
            $subStmt->execute([':step_id' => $step['id']]);
            $subData = $subStmt->fetch();

            if ($subData['total'] > 0) {
                // Progress is based on subtasks
                $stepProgress = ($subData['completed'] / $subData['total']) * 100;
                
                // Optional: auto-update the step's is_completed flag if all subtasks are done
                $isStepCompleted = ($subData['completed'] == $subData['total']) ? 1 : 0;
                if ($step['is_completed'] != $isStepCompleted) {
                    $db->prepare("UPDATE project_steps SET is_completed = ? WHERE id = ?")->execute([$isStepCompleted, $step['id']]);
                }
                
                $totalStepPercentages += $stepProgress;
            } else {
                // Progress is based on the step's own toggle
                $totalStepPercentages += $step['is_completed'] ? 100 : 0;
            }
        }

        $progress = round($totalStepPercentages / count($steps));
        
        $updateStmt = $db->prepare("UPDATE {$this->table} SET progress = :progress WHERE id = :id");
        $updateStmt->bindParam(':progress', $progress);
        $updateStmt->bindParam(':id', $projectId);
        $updateStmt->execute();
        
        return $progress;
    }
}



