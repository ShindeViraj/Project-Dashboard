<?php

namespace Models;

use Core\Model;
use Config\Database;
use PDO;

class ProjectTeam extends Model {
    protected $table = 'project_team';

    public function findByProject($projectId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT pt.*, u.name, u.email, u.department 
                              FROM {$this->table} pt 
                              JOIN users u ON pt.user_id = u.id 
                              WHERE pt.project_id = :project_id 
                              ORDER BY pt.is_leader DESC, u.name ASC");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isTeamMember($projectId, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE project_id = :project_id AND user_id = :user_id");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['cnt'] > 0;
    }

    public function isLeader($projectId, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT is_leader FROM {$this->table} WHERE project_id = :project_id AND user_id = :user_id");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result && $result['is_leader'] == 1;
    }

    public function toggleEditAccess($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE {$this->table} SET can_edit = CASE WHEN can_edit = 1 THEN 0 ELSE 1 END WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } 

        public function hasEditAccess($projectId, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT can_edit, is_leader FROM {$this->table} WHERE project_id = :project_id AND user_id = :user_id");
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result && ($result['can_edit'] == 1 || $result['is_leader'] == 1);
    } 
}

