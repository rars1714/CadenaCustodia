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
$id_rol = $_SESSION['id_rol'];
$id_usuario = $_SESSION['usuario_id'];

$perm_agregar_casos     = tiene_permiso($conexion, $id_rol, 'crear_casos');
$perm_consultar_casos   = tiene_permiso($conexion, $id_rol, 'consultar_casos');
$perm_agregar_evidencia      = tiene_permiso($conexion, $id_rol, 'ingresar_evidencia');
$perm_consultar_evidencia    = tiene_permiso($conexion, $id_rol, 'consultar_evidencia');


$sql = ($id_rol == 4)
    ? "SELECT * FROM analisis"
    : "SELECT * FROM analisis WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
if ($id_rol != 4) $stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Consulta de Análisis</title>
  <link rel="stylesheet" href="../css/modificar.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>

<nav class="navbar">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggler" data-toggle="open-navbar1">
        <span></span><span></span><span></span>
      </button>
      <a href="#">
        <img
          src="<?= $id_rol === 4 ? '../../images/techlab.png' : '../images/techlab.png' ?>"
          alt="Legal Tech"
          style="width:150px; height:auto;"
        >
      </a>
    </div>
    <div class="navbar-menu" id="open-navbar1">
      <ul class="navbar-nav">
        <li class="navbar-item">
          <a href="#"><?= htmlspecialchars($_SESSION['nombre']) ?> (ID: <?= htmlspecialchars($_SESSION['usuario_id']) ?>)</a>
        </li>

        <!-- EVIDENCIA -->
        <?php if ($perm_agregar_evidencia || $perm_consultar_evidencia): ?>
        <li class="navbar-dropdown">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">Evidencia</a>
          <ul class="dropdown" id="dropdown-evidencia">
            <?php if ($perm_agregar_evidencia): ?>
              <li><a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/agregar_evidencia_admin.php' : '../Evidencia/agregar_evidencia.php' ?>">Agregar</a></li>
              <li class="separator"></li>
            <?php endif; ?>
            <?php if ($perm_consultar_evidencia): ?>
              <li><a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/modificar_evidencia_admin.php' : '../Evidencia/modificar_evidencia.php' ?>">Consultar</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <!-- CASOS -->
        <?php if ($perm_agregar_casos || $perm_consultar_casos): ?>
        <li class="navbar-dropdown">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">Casos</a>
          <ul class="dropdown" id="dropdown-casos">
            <?php if ($perm_agregar_casos): ?>
              <li><a href="<?= $id_rol === 4 ? '../../Admin/Casos/agregar_caso_admin.php' : '../Casos/agregar_caso.php' ?>">Agregar</a></li>
              <li class="separator"></li>
            <?php endif; ?>
            <?php if ($perm_consultar_casos): ?>
              <li><a href="<?= $id_rol === 4 ? '../../Admin/Casos/modificar_caso_admin.php' : '../Casos/modificar_caso.php' ?>">Consultar</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <!-- ÁREA DE TRABAJO SOLO PARA ROL 2 -->
        <?php if ($id_rol == 2): ?>
        <li class="navbar-dropdown active">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-trabajo">Área de Trabajo</a>
          <ul class="dropdown" id="dropdown-trabajo">
            <li><a href="formulario_analisis.php">Registro</a></li>
            <li class="separator"></li>
            <li><a href="modificar_analisis.php">Consultar</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <!-- SALIR -->
        <li><a href="<?= $id_rol === 4 ? '../../Login/logout.php' : '../Login/logout.php' ?>">Salir</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID Análisis</th>
          <th>ID Caso</th>
          <th>ID Evidencia</th>
          <th>Descripción</th>
          <th>Archivo</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
          <tr>
            <td><?= $fila['id_analisis'] ?></td>
            <td><?= $fila['id_caso'] ?></td>
            <td><?= $fila['id_evidencia'] ?></td>
            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
            <td>
            <a href="<?= htmlspecialchars($fila['ruta_archivo']) ?>" target="_blank">
                Ver archivo
                </a>
            </td>
            <td><?= $fila['fecha_ingreso'] ?? 'N/D' ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script src="../js/navbar.js"></script>
</body>
</html>
