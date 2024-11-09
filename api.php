<?php
$host = 'localhost';
$user = 'root'; // Cambia esto a tu usuario de MySQL
$password = ''; // Cambia esto a tu contraseña de MySQL
$dbname = 'todolist';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

header("Content-Type: application/json");

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        // Listar todas las tareas
        $result = $conn->query("SELECT * FROM tasks");
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        // Crear una nueva tarea
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $conn->real_escape_string($data['name']);
        $assignee = $conn->real_escape_string($data['assignee']);
        $conn->query("INSERT INTO tasks (name, assignee) VALUES ('$name', '$assignee')");
        echo json_encode(['message' => 'Tarea creada exitosamente', 'task' => ['id' => $conn->insert_id, 'name' => $name, 'assignee' => $assignee]]);
        break;

    case 'PUT':
        // Actualizar una tarea existente
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $name = $conn->real_escape_string($data['name']);
        $assignee = $conn->real_escape_string($data['assignee']);
        $conn->query("UPDATE tasks SET name='$name', assignee='$assignee' WHERE id=$id");
        echo json_encode(['message' => 'Tarea actualizada exitosamente', 'task' => ['id' => $id, 'name' => $name, 'assignee' => $assignee]]);
        break;

    case 'DELETE':
        // Eliminar una tarea
        $id = intval(basename($_SERVER['REQUEST_URI']));
        $conn->query("DELETE FROM tasks WHERE id=$id");
        echo json_encode(['message' => 'Tarea eliminada exitosamente']);
        break;

    default:
        echo json_encode(['message' => 'Método no permitido']);
        break;
}

$conn->close();
?>