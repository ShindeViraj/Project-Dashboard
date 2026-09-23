<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class SubTask extends Model {
    protected $table = 'sub_tasks';

    public function findByStep($stepId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE project_step_id = :project_step_id ORDER BY created_at ASC");
        $stmt->bindParam(':project_step_id', $stepId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function toggleCompleted($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE {$this->table} SET is_completed = CASE WHEN is_completed = 1 THEN 0 ELSE 1 END WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
