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
$perm_agregar_evidencia   = tiene_permiso($conexion, $_SESSION['id_rol'], 'ingresar_evidencia');
$perm_consultar_evidencia = tiene_permiso($conexion, $_SESSION['id_rol'], 'consultar_evidencia');
$perm_agregar_casos       = tiene_permiso($conexion, $_SESSION['id_rol'], 'crear_casos');
$perm_consultar_casos     = tiene_permiso($conexion, $_SESSION['id_rol'], 'consultar_casos');

$id_usuario = $_SESSION['usuario_id'];
$id_rol     = $_SESSION['id_rol'];

if (!tiene_permiso($conexion, $id_rol, 'crear_casos')) {
    echo "<script>
        alert('No tiene permiso para crear casos.');
        window.location.href = '../home.php';
    </script>";
    exit();
}

$resultado = $conexion->query("SELECT MAX(id_caso) AS ultimo_id FROM casos");
$fila = $resultado->fetch_assoc();
$siguiente_id = $fila['ultimo_id'] + 1;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Casos</title>
  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/forms.css">
  <link rel="stylesheet" href="../css/confirmacion/check_usuario.css">
</head>

<body>
<!-- ========== Navbar ========== -->
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
          <a href="#">
            <?= htmlspecialchars($_SESSION['nombre']) ?> (ID: <?= htmlspecialchars($_SESSION['usuario_id']) ?>)
          </a>
        </li>

        <!-- EVIDENCIA -->
        <?php if ($perm_agregar_evidencia || $perm_consultar_evidencia): ?>
        <li class="navbar-dropdown">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
            Evidencia <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown" id="dropdown-evidencia">
            <?php if ($perm_agregar_evidencia): ?>
              <li>
                <a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/agregar_evidencia_admin.php' : '../Evidencia/agregar_evidencia.php' ?>">Agregar</a>
              </li>
              <li class="separator"></li>
            <?php endif; ?>
            <?php if ($perm_consultar_evidencia): ?>
              <li>
                <a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/modificar_evidencia_admin.php' : '../Evidencia/modificar_evidencia.php' ?>">Consultar</a>
              </li>
              <li class="separator"></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <!-- CASOS -->
        <?php if ($perm_agregar_casos || $perm_consultar_casos): ?>
        <li class="navbar-dropdown active">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
            Casos <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown" id="dropdown-casos">
            <?php if ($perm_agregar_casos): ?>
              <li>
                <a href="<?= $id_rol === 4 ? '../../Admin/Casos/agregar_caso_admin.php' : '../Casos/agregar_caso.php' ?>">Agregar</a>
              </li>
              <li class="separator"></li>
            <?php endif; ?>
            <?php if ($perm_consultar_casos): ?>
              <li>
                <a href="<?= $id_rol === 4 ? '../../Admin/Casos/modificar_caso_admin.php' : '../Casos/modificar_caso.php' ?>">Consultar</a>
              </li>
              <li class="separator"></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <!-- ÁREA DE TRABAJO solo para rol 2 -->
        <?php if ($id_rol == 2): ?>
        <li class="navbar-dropdown">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-trabajo">Área de Trabajo</a>
          <ul class="dropdown" id="dropdown-trabajo">
            <li><a href="../Analisis/formulario_analisis.php">Registro</a></li>
            <li class="separator"></li>
            <li><a href="../Analisis/modificar_analisis.php">Consultar</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <!-- ADMIN: Usuarios y Accesos -->
        <?php if ($id_rol === 4): ?>
        <li class="navbar-dropdown">
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

        <!-- SALIR -->
        <li>
          <a href="<?= $id_rol === 4 ? '../../Login/logout.php' : '../Login/logout.php' ?>">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <!-- ========== Formulario de Casos ========== -->
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <!-- Se actualiza el action para apuntar a registro_caso.php -->
      <form id="registrationForm" action="registro_caso.php" method="POST">
        <div class="formbold-input-flex">
          <div>
          <input type="text" name="id_caso" id="id_caso" class="formbold-form-input" value="<?php echo $siguiente_id; ?>" readonly>
            <label for="id_caso" class="formbold-form-label">ID del Caso</label>
          </div>
          <div>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="formbold-form-input" value="<?php echo date('Y-m-d'); ?>" readonly>
            <label for="fecha_inicio" class="formbold-form-label">Fecha de Inicio</label>
          </div>
        </div>
        <!-- Grupo 1: Nombre del Caso y Usuario -->
        <div class="formbold-input-flex">
          <div>
            <input type="text" name="nombre_caso" id="nombre_caso" placeholder="Nombre del caso" class="formbold-form-input" />
            <label for="nombre_caso" class="formbold-form-label">Nombre del Caso</label>
          </div>
          <div>
            <input type="text" name="estado_display" id="estado_display" class="formbold-form-input" value="Abierto" disabled>
            <!-- Input hidden para enviar el valor -->
            <input type="hidden" name="estado" value="abierto">
            <label for="estado_display" class="formbold-form-label">Estado</label>
          </div>
        </div>


        <div class="formbold-textarea">
          <textarea rows="6" name="descripcion" id="descripcion" placeholder="Descripción del caso." class="formbold-form-input"></textarea>
          <label for="descripcion" class="formbold-form-label">Descripción</label>
        </div>
        <button type="button" id="confirmBtn" class="formbold-btn" style="display: block; margin: 30px auto;">
          Registrar Caso
        </button>
      </form>
      <div id="confirmation" style="display: none;">
        <h3>Confirma tus datos</h3>
        <ul>
          <li id="confirmIdCaso"></li>
          <li id="confirmFechaInicio"></li>
          <li id="confirmNombreCaso"></li>
          <li id="confirmEstado"></li>
          <li id="confirmDescripcion"></li>
        </ul>
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
          <button id="editBtn" class="formbold-btn">Editar</button>
          <button id="submitBtn" class="formbold-btn">Confirmar Registro</button>
        </div>
      </div>


    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
  <script src="../js/registro/registro_caso.js"></script>

</body>
</html>
