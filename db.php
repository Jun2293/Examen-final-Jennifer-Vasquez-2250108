<?php

$servername = "localhost"; // Cambia esto si tu servidor de base de datos es diferente
$username = "root"; // Tu usuario de MySQL
$password = ""; // Tu contraseña de MySQL (deja vacío si no tienes)
$dbname = "tareas_db"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conectado exitosamente a la base de datos";
?>
