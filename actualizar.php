<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizando Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .mensaje {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
        }
        .proceso {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<h1>Proceso de Actualización</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['accion']) || $_POST['accion'] !== 'actualizar') {
    echo "<div class='mensaje error'>Acceso no permitido.</div>";
    echo "<a href='listado.php'>Volver al listado</a>";
    exit;
}

echo "<div class='mensaje proceso'>Actualizando producto... Por favor, espere.</div>";
flush();
ob_flush();


require_once 'conexion.php';


$cod = $_POST['cod'];
$nombre_corto = $_POST['nombre_corto'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$pvp = floatval($_POST['PVP'] ?? 0);
$familia = $_POST['familia'] ?? '';


$sql = "UPDATE producto SET nombre_corto = ?, nombre = ?, descripcion = ?, PVP = ?, familia = ? WHERE cod = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $params = [$nombre_corto, $nombre, $descripcion, $pvp, $familia, $cod];
    
    /
    sleep(1); 

    if ($stmt->execute($params)) {
        echo "<div class='mensaje exito'>¡Finalizado! El producto ha sido actualizado correctamente.</div>";
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "<div class='mensaje error'>Error al ejecutar la actualización: " . htmlspecialchars(implode(' | ', $errorInfo)) . "</div>";
    }
    $stmt = null;
} else {
    $errorInfo = $conn->errorInfo();
    echo "<div class='mensaje error'>Error al preparar la consulta: " . htmlspecialchars(implode(' | ', $errorInfo)) . "</div>";
}

$conn = null;
?>

<br>
<a href="listado.php">Volver al listado de productos</a>

</body>
</html>