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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Análisis</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/forms.css">
  <link rel="stylesheet" href="../css/confirmacion/check_usuario.css">


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
    <form action="registro_analisis.php" method="POST" enctype="multipart/form-data">
      <div class="formbold-input-flex">
        <div>
          <input type="text" name="id_caso" id="id_caso" class="formbold-form-input" placeholder="ID del Caso" required>
          <label for="id_caso" class="formbold-form-label">ID de Caso</label>
        </div>
        <div>
          <input type="text" name="id_evidencia" id="id_evidencia" class="formbold-form-input" placeholder="ID de Evidencia" required>
          <label for="id_evidencia" class="formbold-form-label">ID de Evidencia</label>
        </div>
      </div>

      <div class="formbold-textarea">
        <textarea rows="6" name="descripcion" id="descripcion" placeholder="Descripción del análisis." class="formbold-form-input"></textarea>
        <label for="descripcion" class="formbold-form-label">Descripción</label>
      </div>

      <div class="formbold-input-file">
          <div class="formbold-filename-wrapper" id="file-list">
            <!-- Aquí se mostrarán los archivos subidos (opcional con JS) -->
          </div>
          <label for="upload" class="formbold-input-label">
            Adjuntar archivos
            <input type="file" id="upload" name="archivo" multiple>
          </label>
        </div>
      <div class="formbold-btn-wrapper">
        <button class="formbold-btn" type="submit" style="display: block; margin: 30px auto;">Registrar Análisis</button>
      </div>
    </form>
  </div>
</div>
<script>
  document.getElementById('upload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const fileNameDisplay = document.getElementById('file-name');
    if (file) {
      fileNameDisplay.textContent = "Archivo seleccionado: " + file.name;
    } else {
      fileNameDisplay.textContent = "";
    }
  });
</script>
<script src="../js/navbar.js"></script>
<script src="../js/forms.js"></script>
</body>
</html>
