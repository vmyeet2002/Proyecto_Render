<?php
// Mostrar errores durante la depuración (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
require_once 'conexion.php';

// Obtener familias para el desplegable
try {
    $stmt = $conn->query("SELECT cod, nombre FROM familia ORDER BY nombre ASC");
    $familias = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    http_response_code(500);
    die("<div class='container'><p>Error al obtener las familias.</p></div>");
}

// Procesar selección de familia
$productos = [];
$familia_seleccionada = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['familia'])) {
    $familia_seleccionada = $_POST['familia'];
    if (!empty($familia_seleccionada)) {
        try {
            $stmt = $conn->prepare("SELECT cod, nombre_corto, PVP FROM producto WHERE familia = ? ORDER BY nombre_corto ASC");
            $stmt->execute([$familia_seleccionada]);
            $productos = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            http_response_code(500);
            die("<div class='container'><p>Error al obtener los productos.</p></div>");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .subtitle {
            text-align: center;
            color: #666;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        select {
            padding: 10px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-products {
            text-align: center;
            color: #999;
            margin-top: 20px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: #fff;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vicor Manuel </h1>
        <h1> 2do DAW </h1>
        <h1>Listado de Productos</h1>
        <p class="subtitle">Seleccione una familia para ver los productos asociados.</p>
        
        <form method="post">
            <label for="familia">Familia:</label>
            <select name="familia" id="familia" onchange="this.form.submit()">
                <option value="">-- Seleccione una --</option>
                <?php foreach ($familias as $f): ?>
                    <option value="<?= htmlspecialchars($f->cod) ?>"
                        <?= ($familia_seleccionada == $f->cod) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f->nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit">Mostrar</button></noscript>
        </form>

        <?php if (!empty($productos)): ?>
            <h2>Productos de la familia</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>PVP (€)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto->nombre_corto) ?></td>
                            <td><?= htmlspecialchars(number_format($producto->pvp, 2, ',', '.')) ?></td>
                            <td>
                                <a href="editar.php?cod=<?= urlencode($producto->cod) ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="no-products">No hay productos para la familia seleccionada.</p>
        <?php endif; ?>
    </div>
    <footer>
        <div id="footer"> 
            <h1> Proyecto de Victor Manuel </h1> 
            <h1> 2 DAW Vespertino </h1>
            <p><?php echo date('d/m/Y'); ?></p>
        </div>
    </footer>
</body>
</html>
