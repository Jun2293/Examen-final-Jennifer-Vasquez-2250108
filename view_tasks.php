<?php
// view_tasks.php

include 'db.php'; // Incluye el archivo de conexión

$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Salida de cada fila
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Título: " . $row["title"]. " - Completado: " . ($row["completed"] ? 'Sí' : 'No') . "<br>";
    }
} else {
    echo "No hay tareas.";
}

$conn->close();
?>
