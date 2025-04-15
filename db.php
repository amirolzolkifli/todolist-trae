<?php
// Database connection and functions
class TodoDB {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTable();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function createTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS todos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            task TEXT NOT NULL,
            completed INTEGER DEFAULT 0,
            position INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function getAllTodos() {
        $stmt = $this->db->prepare("SELECT * FROM todos ORDER BY position ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompletedTodos() {
        $stmt = $this->db->prepare("SELECT * FROM todos WHERE completed = 1 ORDER BY position ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIncompleteTodos() {
        $stmt = $this->db->prepare("SELECT * FROM todos WHERE completed = 0 ORDER BY position ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTodo($task) {
        $stmt = $this->db->prepare("SELECT COALESCE(MAX(position), 0) + 1 as next_pos FROM todos");
        $stmt->execute();
        $nextPos = $stmt->fetch(PDO::FETCH_ASSOC)['next_pos'];

        $stmt = $this->db->prepare("INSERT INTO todos (task, position) VALUES (:task, :position)");
        $stmt->bindParam(':task', $task);
        $stmt->bindParam(':position', $nextPos);
        return $stmt->execute();
    }

    public function updateTodoStatus($id, $completed) {
        $stmt = $this->db->prepare("UPDATE todos SET completed = :completed WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':completed', $completed);
        return $stmt->execute();
    }

    public function updateAllTodosStatus($completed) {
        $stmt = $this->db->prepare("UPDATE todos SET completed = :completed");
        $stmt->bindParam(':completed', $completed);
        return $stmt->execute();
    }

    public function deleteTodo($id) {
        $stmt = $this->db->prepare("DELETE FROM todos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteCompletedTodos() {
        $stmt = $this->db->prepare("DELETE FROM todos WHERE completed = 1");
        return $stmt->execute();
    }

    public function updatePositions($positions) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("UPDATE todos SET position = :position WHERE id = :id");
            foreach ($positions as $id => $position) {
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':position', $position);
                $stmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>