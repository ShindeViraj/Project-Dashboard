<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class MomEntry extends Model {
    protected $table = 'mom_entries';

    public function findByProject($projectId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT m.*, u.name as author_name 
                              FROM {$this->table} m 
                              JOIN users u ON m.created_by = u.id 
                              WHERE m.project_id = :project_id 
                              ORDER BY m.created_at DESC");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
