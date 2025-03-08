<?php
class Task {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Получение всех задач пользователя
    public function getTasks($userId, $filters = []) {
        $sql = 'SELECT * FROM tasks WHERE user_id = :user_id';
        
        // Добавление фильтров
        if(!empty($filters)) {
            if(isset($filters['status']) && !empty($filters['status'])) {
                $sql .= ' AND status = :status';
            }
            
            if(isset($filters['priority']) && !empty($filters['priority'])) {
                $sql .= ' AND priority = :priority';
            }
            
            if(isset($filters['search']) && !empty($filters['search'])) {
                $sql .= ' AND (title LIKE :search OR description LIKE :search)';
            }
        }
        
        // Сортировка
        $sql .= ' ORDER BY created_at DESC';
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        
        // Привязка параметров фильтрации
        if(!empty($filters)) {
            if(isset($filters['status']) && !empty($filters['status'])) {
                $this->db->bind(':status', $filters['status']);
            }
            
            if(isset($filters['priority']) && !empty($filters['priority'])) {
                $this->db->bind(':priority', $filters['priority']);
            }
            
            if(isset($filters['search']) && !empty($filters['search'])) {
                $this->db->bind(':search', '%' . $filters['search'] . '%');
            }
        }
        
        return $this->db->resultSet();
    }
    
    // Получение задачи по ID
    public function getTaskById($id) {
        $this->db->query('SELECT * FROM tasks WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Добавление новой задачи
    public function addTask($data) {
        $this->db->query('INSERT INTO tasks (user_id, title, description, status, priority, due_date) 
                          VALUES (:user_id, :title, :description, :status, :priority, :due_date)');
        
        // Привязка значений
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':due_date', $data['due_date']);
        
        // Выполнение запроса
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Обновление задачи
    public function updateTask($data) {
        $this->db->query('UPDATE tasks SET title = :title, description = :description, 
                          status = :status, priority = :priority, due_date = :due_date 
                          WHERE id = :id AND user_id = :user_id');
        
        // Привязка значений
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':due_date', $data['due_date']);
        
        // Выполнение запроса
        return $this->db->execute();
    }
    
    // Удаление задачи
    public function deleteTask($id, $userId) {
        $this->db->query('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        
        // Привязка значений
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        
        // Выполнение запроса
        return $this->db->execute();
    }
    
    // Изменение статуса задачи
    public function updateStatus($id, $userId, $status) {
        $this->db->query('UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id');
        
        // Привязка значений
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':status', $status);
        
        // Выполнение запроса
        return $this->db->execute();
    }
} 