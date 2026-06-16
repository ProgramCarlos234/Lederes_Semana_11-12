<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'RURALMED';

// Conexión sin base de datos
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Eliminar base de datos si existe
$conn->query("DROP DATABASE IF EXISTS `$db_name`");

// Crear base de datos
if ($conn->query("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "Base de datos creada exitosamente!<br>";
} else {
    die("Error al crear la base de datos: " . $conn->error);
}

// Seleccionar la base de datos
$conn->select_db($db_name);

// Leer el archivo SQL
$sql_file = 'C:\PAUL\UC\UC-2025-2\DISEÑO DE SOFTWARE\UNIDAD 4\GITADICIONALRURALMED\db\ruralmed_ultima_version.sql';
$sql = file_get_contents($sql_file);

if ($sql === false) {
    die("Error al leer el archivo SQL");
}

// Ejecutar las consultas
if ($conn->multi_query($sql)) {
    do {
        // Almacenar el resultado
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Base de datos importada exitosamente!<br>";
} else {
    echo "Error al importar la base de datos: " . $conn->error . "<br>";
}

$conn->close();

echo "<a href='index.php'>Ir al sistema</a>";
?>
