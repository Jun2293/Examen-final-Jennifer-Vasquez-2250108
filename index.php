<?php
// index.php

include 'db.php'; // Incluye el archivo de conexión

// Variable para almacenar mensajes
$message = '';

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title']; // Obtiene el título de la tarea
    $assigned_to = $_POST['assigned_to']; // Obtiene la persona asignada
    $completed = isset($_POST['completed']) ? 1 : 0; // Obtiene el estado de completado

    // Consulta para insertar la tarea
    $sql = "INSERT INTO tasks (title, completed, assigned_to) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $message = "Error en la preparación de la consulta: " . $conn->error;
    } else {
        $stmt->bind_param("sis", $title, $completed, $assigned_to); // "sis" significa string, integer, string

        if ($stmt->execute()) {
            $message = "Nueva tarea agregada con éxito.";
        } else {
            $message = "Error al agregar tarea: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Consulta para obtener todas las tareas
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

// Verifica si la consulta fue exitosa
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}
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
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Lista de Tareas</h1>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="POST" action="">
    <input type="text" name="title" placeholder="Título de la tarea" required>
    <input type="text" name="assigned_to" placeholder="Asignar a (nombre)" required>
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
        <th>Asignado a</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Salida de cada fila
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["title"] . "</td>
                    <td>" . ($row["completed"] ? 'Sí' : 'No') . "</td>
                    <td>" . htmlspecialchars($row["assigned_to"]) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No hay tareas.</td></tr>";
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
