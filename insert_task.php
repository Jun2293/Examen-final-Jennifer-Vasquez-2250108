<?php
// insert_task.php

include 'db.php'; // Incluye el archivo de conexión

$title = "Aprender PHP"; // Título de la tarea
$completed = false; // Estado de completado

// Consulta para insertar la tarea
$sql = "INSERT INTO tasks (title, completed) VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $title, $completed); // "si" significa string, integer

if ($stmt->execute()) {
    echo "Nueva tarea agregada con éxito. ID: " . $conn->insert_id;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
