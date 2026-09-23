<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class ProjectStep extends Model {
    protected $table = 'project_steps';

    public function findByProject($projectId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE project_id = :project_id ORDER BY id ASC");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function toggleApplicable($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE {$this->table} SET is_applicable = CASE WHEN is_applicable = 1 THEN 0 ELSE 1 END WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function toggleCompleted($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE {$this->table} SET is_completed = CASE WHEN is_completed = 1 THEN 0 ELSE 1 END WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
