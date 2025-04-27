<?php
session_start();
// Verificar si NO hay sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../Login/login.php");
    exit();
}
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener usuarios con nombre de rol
$sql = "SELECT u.id_usuario, u.Nombre, u.Apellido, u.despacho, u.Correo, u.id_rol, r.nombre AS rol_nombre
        FROM usuarios u
        JOIN roles r ON u.id_rol = r.id_rol";
$resultado = $conexion->query($sql);

$roles_query = $conexion->query("SELECT id_rol, nombre FROM roles WHERE nombre != 'Admin'"); // Excluir 'Admin'
$roles = [];
while ($row = $roles_query->fetch_assoc()) {
    $roles[$row['id_rol']] = $row['nombre'];
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../css/modificar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/forms.css">
    
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


<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Despacho</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
    <tr data-id="<?= $fila['id_usuario'] ?>">
        <td><?= $fila['id_usuario'] ?></td>
        <td><input type="text" value="<?= $fila['Nombre'] ?>" disabled></td>
        <td><input type="text" value="<?= $fila['Apellido'] ?>" disabled></td>
        <td><input type="text" value="<?= $fila['despacho'] ?>" disabled></td>
        <td><input type="text" value="<?= $fila['Correo'] ?>" disabled></td>
        <td>
        <select disabled>
            <?php foreach ($roles as $id_rol => $nombre_rol): ?>
                <option value="<?= $id_rol ?>" <?= $fila['id_rol'] == $id_rol ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nombre_rol) ?>
                </option>
            <?php endforeach; ?>
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
    <script src="../../js/navbar.js"></script>
    <script src="../../js/forms.js"></script>
    <script src="../../js/modificar/modificar_usuario.js"></script>

</body>
</html>
