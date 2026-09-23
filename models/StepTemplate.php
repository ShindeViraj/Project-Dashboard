<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class StepTemplate extends Model {
    protected $table = 'step_templates';

    public function findDefaults() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE is_default = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cloneToProject($projectId) {
        $db = Database::getInstance();
        $defaults = $this->findDefaults();
        
        $stmt = $db->prepare("INSERT INTO project_steps (project_id, step_name, is_applicable, is_completed) VALUES (:project_id, :step_name, 1, 0)");
        
        foreach ($defaults as $step) {
            $stmt->bindParam(':project_id', $projectId);
            $stmt->bindParam(':step_name', $step['step_name']);
            $stmt->execute();
        }
        
        return true;
    }
}
