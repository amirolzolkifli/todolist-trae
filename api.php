<?php
require_once 'db.php';

header('Content-Type: application/json');
$db = new TodoDB();
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filter = $_GET['filter'] ?? 'all';
    
    switch ($filter) {
        case 'completed':
            $todos = $db->getCompletedTodos();
            break;
        case 'incomplete':
            $todos = $db->getIncompleteTodos();
            break;
        default:
            $todos = $db->getAllTodos();
    }
    
    $response = ['success' => true, 'todos' => $todos];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'add':
                if (isset($data['task']) && !empty($data['task'])) {
                    $result = $db->addTodo($data['task']);
                    $response = ['success' => $result, 'message' => $result ? 'Todo added successfully' : 'Failed to add todo'];
                }
                break;
                
            case 'update':
                if (isset($data['id']) && isset($data['completed'])) {
                    $result = $db->updateTodoStatus($data['id'], $data['completed'] ? 1 : 0);
                    $response = ['success' => $result, 'message' => $result ? 'Todo updated successfully' : 'Failed to update todo'];
                }
                break;
                
            case 'updateAll':
                if (isset($data['completed'])) {
                    $result = $db->updateAllTodosStatus($data['completed'] ? 1 : 0);
                    $response = ['success' => $result, 'message' => $result ? 'All todos updated successfully' : 'Failed to update todos'];
                }
                break;
                
            case 'delete':
                if (isset($data['id'])) {
                    $result = $db->deleteTodo($data['id']);
                    $response = ['success' => $result, 'message' => $result ? 'Todo deleted successfully' : 'Failed to delete todo'];
                }
                break;
                
            case 'deleteCompleted':
                $result = $db->deleteCompletedTodos();
                $response = ['success' => $result, 'message' => $result ? 'Completed todos deleted successfully' : 'Failed to delete completed todos'];
                break;
                
            case 'updatePositions':
                if (isset($data['positions']) && is_array($data['positions'])) {
                    $result = $db->updatePositions($data['positions']);
                    $response = ['success' => $result, 'message' => $result ? 'Positions updated successfully' : 'Failed to update positions'];
                }
                break;
        }
    }
}

echo json_encode($response);
?>