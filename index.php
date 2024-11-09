<?php
// index.php

include 'db.php'; // Incluye el archivo de conexión

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title']; // Obtiene el título de la tarea
    $completed = isset($_POST['completed']) ? 1 : 0; // Obtiene el estado de completado

    // Consulta para insertar la tarea
    $sql = "INSERT INTO tasks (title, completed) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $title, $completed); // "si" significa string, integer

    if ($stmt->execute()) {
        echo "Nueva tarea agregada con éxito. ID: " . $conn->insert_id;
    } else {
        echo "Error al agregar tarea: " . $stmt->error;
    }

    $stmt->close();
}

// Consulta para obtener todas las tareas
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Lista de Tareas</h1>

<form method="POST" action="">
    <input type="text" name="title" placeholder="Título de la tarea" required>
    <label>
        <input type="checkbox" name="completed"> Completado
    </label>
    <button type="submit">Agregar Tarea</button>
</form>

<h2>Tareas</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Completado</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Salida de cada fila
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["title"] . "</td>
                    <td>" . ($row["completed"] ? 'Sí' : 'No') . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No hay tareas.</td></tr>";
    }
    ?>
</table>

<?php
// Cierra la conexión a la base de datos
if (isset($conn) && $conn) {
    $conn->close(); // Asegúrate de que $conn esté definido
}
?>

</body>
</html>
