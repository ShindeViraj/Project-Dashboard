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
        
        $stmt = $db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM project_steps WHERE project_id = :project_id AND is_applicable = 1) as total_steps,
                (SELECT COALESCE(SUM(is_completed), 0) FROM project_steps WHERE project_id = :project_id AND is_applicable = 1) as completed_steps,
                (SELECT COUNT(*) FROM sub_tasks st JOIN project_steps ps ON st.project_step_id = ps.id WHERE ps.project_id = :project_id AND ps.is_applicable = 1) as total_subtasks,
                (SELECT COALESCE(SUM(st.is_completed), 0) FROM sub_tasks st JOIN project_steps ps ON st.project_step_id = ps.id WHERE ps.project_id = :project_id AND ps.is_applicable = 1) as completed_subtasks
        ");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        $data = $stmt->fetch();
        
        $progress = 0;
        if ($data) {
            $totalItems = $data['total_steps'] + $data['total_subtasks'];
            $completedItems = $data['completed_steps'] + $data['completed_subtasks'];
            if ($totalItems > 0) {
                $progress = round(($completedItems / $totalItems) * 100);
            }
        }
        
        $updateStmt = $db->prepare("UPDATE {$this->table} SET progress = :progress WHERE id = :id");
        $updateStmt->bindParam(':progress', $progress);
        $updateStmt->bindParam(':id', $projectId);
        $updateStmt->execute();
        
        return $progress;
    }
}


