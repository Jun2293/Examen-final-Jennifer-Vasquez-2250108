// db.js
const mysql = require('mysql');

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root', // Cambia esto por tu usuario
    password: '', // Cambia esto por tu contraseña
    database: 'tareas_db' // Nombre de tu base de datos
});

connection.connect((err) => {
    if (err) {
        console.error('Error al conectar a la base de datos: ' + err.stack);
        return;
    }
    console.log('Conectado a la base de datos como ID ' + connection.threadId);
});

// Ejemplo de consulta
const agregarTarea = (tarea, asignadoA) => {
    const query = 'INSERT INTO tareas (tarea, asignado_a) VALUES (?, ?)';
    connection.query(query, [tarea, asignadoA], (err, results) => {
        if (err) {
            console.error('Error al agregar tarea: ' + err);
            return;
        }
        console.log('Tarea agregada con ID: ' + results.insertId);
    });
};

// Exporta la conexión y la función
module.exports = { connection, agregarTarea };
