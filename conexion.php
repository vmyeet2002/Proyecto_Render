<?php

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT'); 
$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {

    $conn = new PDO($dsn, $user, $pass);
    

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>