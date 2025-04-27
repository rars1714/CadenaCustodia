<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}

require_once '../validar_permisos.php';

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['usuario_id'];
$id_rol     = $_SESSION['id_rol'];

// Verificar si tiene permiso para consultar evidencia
if (!tiene_permiso($conexion, $id_rol, 'consultar_casos')) {
    echo "<script>
        alert('No tiene permiso para consultar casos.');
        window.location.href = '../home.php';
    </script>";
    exit();
}

// Recoger filtros de la query string
$filtros = [];
if (!empty($_GET['id_caso'])) {
    $id = intval($_GET['id_caso']);
    $filtros[] = "id_caso = $id";
}
if (!empty($_GET['nombre_caso'])) {
    $nombre = $conexion->real_escape_string($_GET['nombre_caso']);
    $filtros[] = "nombre_caso LIKE '%$nombre%'";
}
if (!empty($_GET['fecha_creacion'])) {
    $fecha = $conexion->real_escape_string($_GET['fecha_creacion']);
    $filtros[] = "DATE(fecha_creacion) = '$fecha'";
}
// La condición de id_usuario SIEMPRE debe aplicarse
$filtros[] = "id_usuario = $id_usuario";

if (!empty($_GET['estado'])) {
    $estados = array_map(function($e) use ($conexion) {
        return "'" . $conexion->real_escape_string($e) . "'";
    }, (array)$_GET['estado']);
    $filtros[] = "estado IN (" . implode(',', $estados) . ")";
}

// Construir la consulta dinámica
$sql = "SELECT id_caso, nombre_caso, fecha_creacion, descripcion, estado, id_usuario FROM casos";
if (count($filtros) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filtros);
}
$sql .= " ORDER BY id_caso DESC";

$resultado = $conexion->query($sql);

// 1) Saca la URI o el nombre del script actual
$current = $_SERVER['REQUEST_URI'];
// 2) Marca cada sección como activa si la URI la contiene
$isEvidencia = strpos($current, '/Evidencia/') !== false;
$isCasos     = strpos($current, '/Casos/') !== false;
$isUsuarios = strpos($current, '/Usuarios/') !== false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Casos</title>
    <link rel="stylesheet" href="../css/modificar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/forms.css">
    <style>
        .filter-panel form { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
        .filter-panel .field { flex: 1; min-width: 150px; }
        .filter-panel .field label { display: block; font-weight: 600; margin-bottom: 0.25rem; }
        .filter-panel .field input,
        .filter-panel .field select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.25rem; }
        .filter-panel .actions { display: flex; gap: 0.5rem; align-items: center; }
        .filter-panel .actions button,
        .filter-panel .actions a { padding: 0.5rem 1rem; border: none; background: #4A90E2; color: #fff; text-decoration: none; border-radius: 0.25rem; cursor: pointer; }
        .filter-panel .actions a { background: #888; }
        .filter-panel .actions a:hover,
        .filter-panel .actions button:hover { opacity: 0.9; }

        /* Tabla con estilos restaurados */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Roboto', sans-serif;
        }
        table th,
        table td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #4A90E2;
            color: #fff;
            font-weight: 600;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #e6f7ff;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggler" data-toggle="open-navbar1">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a href="#">
                <img
                    src="<?= $_SESSION['id_rol'] === 4
                            ? '../../images/techlab.png'
                            : '../images/techlab.png' ?>"
                    alt="Legal Tech"
                    style="width:150px; height:auto;"
                >
            </a>
        </div>
        <div class="navbar-menu" id="open-navbar1">
            <ul class="navbar-nav">
                <li class="navbar-item">
                    <a href="#">
                        <?= htmlspecialchars($_SESSION['nombre']) ?> (ID: <?= htmlspecialchars($_SESSION['usuario_id']) ?>)
                    </a>
                </li>

                <li class="navbar-dropdown <?= $isEvidencia ? 'active' : '' ?>">
                    <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
                        Evidencia <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown" id="dropdown-evidencia">
                        <li class="separator"></li>
                        <li>
                            <a href="<?= $_SESSION['id_rol'] === 4
                                            ? '../../Admin/Evidencia/agregar_evidencia_admin.php'
                                            : '../Evidencia/agregar_evidencia.php' ?>">
                                Agregar
                            </a>
                        </li>
                        <li class="separator"></li>
                        <li>
                            <a href="<?= $_SESSION['id_rol'] === 4
                                            ? '../../Admin/Evidencia/modificar_evidencia_admin.php'
                                            : '../Evidencia/modificar_evidencia.php' ?>">
                                Consultar
                            </a>
                        </li>
                        <li class="separator"></li>
                    </ul>
                </li>

                <li class="navbar-dropdown <?= $isCasos ? 'active' : '' ?>">
                    <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
                        Casos <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown" id="dropdown-casos">
                        <li>
                            <a href="<?= $_SESSION['id_rol'] === 4
                                            ? '../../Admin/Casos/agregar_caso_admin.php'
                                            : '../Casos/agregar_caso.php' ?>">
                                Agregar
                            </a>
                        </li>
                        <li class="separator"></li>
                        <li>
                            <a href="<?= $_SESSION['id_rol'] === 4
                                            ? '../../Admin/Casos/modificar_caso_admin.php'
                                            : '../Casos/modificar_caso.php' ?>">
                                Consultar
                            </a>
                        </li>
                        <li class="separator"></li>
                    </ul>
                </li>

                <?php if ($_SESSION['id_rol'] === 4): ?>
                <li class="navbar-dropdown <?= $isUsuarios ? 'active' : '' ?>">
                    <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
                        Usuarios <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown" id="dropdown-usuarios">
                        <li><a href="../../Admin/Usuarios/agregar_usuario_admin.php">Agregar</a></li>
                        <li class="separator"></li>
                        <li><a href="../Usuarios/permisos_admin.php">Permisos</a></li>
                        <li class="separator"></li>
                        <li><a href="../../Admin/Usuarios/modificar_usuario_admin.php">Consultar</a></li>
                        <li class="separator"></li>
                    </ul>
                </li>
                <li><a href="../../Admin/Usuarios/historial_accesos.php">Historial de accesos</a></li>
                <?php endif; ?>

                <li>
                    <a href="<?= $_SESSION['id_rol'] === 4
                                    ? '../../Login/logout.php'
                                    : '../Login/logout.php' ?>">
                        Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="filter-panel">
    <form method="get" action="">
        <div class="field">
            <label for="id_caso">ID:</label>
            <input type="number" id="id_caso" name="id_caso" value="<?= htmlspecialchars($_GET['id_caso'] ?? '') ?>">
        </div>
        <div class="field">
            <label for="nombre_caso">Nombre:</label>
            <input type="text" id="nombre_caso" name="nombre_caso" value="<?= htmlspecialchars($_GET['nombre_caso'] ?? '') ?>">
        </div>
        <div class="field">
            <label for="fecha_creacion">Fecha:</label>
            <input type="date" id="fecha_creacion" name="fecha_creacion" value="<?= htmlspecialchars($_GET['fecha_creacion'] ?? '') ?>">
        </div>

        <div class="field">
            <label for="estado">Estado:</label>
            <select id="estado" name="estado[]" multiple>
                <option value="abierto" <?= in_array('abierto', (array)($_GET['estado'] ?? [])) ? 'selected' : '' ?>>Abierto</option>
                <option value="cerrado" <?= in_array('cerrado', (array)($_GET['estado'] ?? [])) ? 'selected' : '' ?>>Cerrado</option>
            </select>
        </div>
        <div class="actions">
            <button type="submit">Filtrar</button>
            <a href="<?= strtok($_SERVER['REQUEST_URI'], '?') ?>">Limpiar</a>
        </div>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre del Caso</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
    <tr data-id="<?= $fila['id_caso'] ?>">
        <td><?= $fila['id_caso'] ?></td>
        <td><input type="text" value="<?= htmlspecialchars($fila['nombre_caso']) ?>" disabled></td>
        <td><input type="text" value="<?= htmlspecialchars($fila['fecha_creacion']) ?>" disabled></td>
        <td><input type="text" value="<?= htmlspecialchars($fila['descripcion']) ?>" disabled></td>
        <td>
        <select disabled>
            <option value="abierto" <?= $fila['estado'] == 'abierto' ? 'selected' : '' ?>>Abierto</option>
            <option value="cerrado" <?= $fila['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
        </select>
        </td>
        <td>
        <button class="edit-btn">Editar</button>
        <button class="save-btn" style="display:none;">Guardar</button>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>

    <script src="../js/navbar.js"></script>
</body>
</html>
