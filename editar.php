<?php
// El código espera recibir el código del producto por GET: ?cod=...
if (!isset($_GET['cod'])) {
    die("Código de producto no especificado.");
}

$cod = $_GET['cod'];

// Conexión a la base de datos
require_once 'conexion.php'; 


// Consulta para obtener los datos del producto por su código
$stmt = $conn->prepare("SELECT nombre_corto, nombre, descripcion, familia, PVP FROM producto WHERE cod = ?");
$stmt->execute([$cod]);
$producto = $stmt->fetch(PDO::FETCH_OBJ);

if (!$producto) {
    $conn = null;
    die("Producto no encontrado.");
}
$stmt = null;

// Consulta para obtener todas las familias
$familias_result = $conn->query("SELECT cod, nombre FROM familia ORDER BY nombre ASC");
$todas_las_familias = [];
if ($familias_result) {
    while ($fila = $familias_result->fetch(PDO::FETCH_ASSOC)) {
        $todas_las_familias[] = $fila;
    }
}
$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - <?= htmlspecialchars($nombre_corto) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 550px;
            background-color: #ffffff;
            padding: 2rem 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #343a40;
            margin-top: 0;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #f8f9fa;
            color: #495057;
            border-color: #ced4da;
        }

        .btn-secondary:hover {
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Producto</h1>
        <form action="actualizar.php" method="post" novalidate>
            <input type="hidden" name="cod" value="<?= htmlspecialchars($cod) ?>">
            
            <div class="form-group">
                <label for="nombre_corto">Nombre corto:</label>
                <input type="text" id="nombre_corto" name="nombre_corto" value="<?= htmlspecialchars($producto->nombre_corto) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto->nombre ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($producto->descripcion ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="PVP">PVP (€):</label>
                <input type="number" id="PVP" step="0.01" name="PVP" value="<?= htmlspecialchars($producto->pvp ?? 0) ?>" required>
            </div>

            <div class="form-group">
                <label for="familia">Familia:</label>
                <select id="familia" name="familia" required>
                    <?php foreach ($todas_las_familias as $f): ?>
                        <option value="<?= htmlspecialchars($f['cod']) ?>" <?= ($f['cod'] == $producto->familia) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" name="accion" value="actualizar" class="btn btn-primary">Actualizar</button>
                <a href="listado.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
