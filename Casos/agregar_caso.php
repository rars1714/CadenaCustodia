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
</head>

<body>
<!-- ========== Navbar ========== -->
<nav class="navbar">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggler" data-toggle="open-navbar1">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <a href="#">
        <img src="../../images/techlab.png" alt="Legal Tech" style="width:150px; height:auto;">
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
        <li class="navbar-dropdown active">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
            Evidencia <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown" id="dropdown-evidencia">
            <li class="separator"></li>
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? 'agregar_evidencia_admin.php' 
                            : 'agregar_evidencia.php' ?>">
                Agregar
              </a>
            </li>
            <li class="separator"></li>
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? 'modificar_evidencia_admin.php' 
                            : 'modificar_evidencia.php' ?>">
                Consultar
              </a>
            </li>
            <li class="separator"></li>
          </ul>
        </li>

        <!-- CASOS -->
        <li class="navbar-dropdown">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
            Casos <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown" id="dropdown-casos">
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? '../../Admin/Casos/agregar_caso_admin.php' 
                            : '../../Casos/agregar_caso.php' ?>">
                Agregar
              </a>
            </li>
            <li class="separator"></li>
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? '../../Admin/Casos/modificar_caso_admin.php' 
                            : '../../Casos/modificar_caso.php' ?>">
                Consultar
              </a>
            </li>
            <li class="separator"></li>
          </ul>
        </li>

        <?php if ($_SESSION['id_rol'] === 4): ?>
          <!-- USUARIOS (solo admin) -->
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
          <!-- HISTORIAL DE ACCESOS (solo admin) -->
          <li><a href="historial_accesos.php">Historial de accesos</a></li>
        <?php endif; ?>

        <li><a href="../../Login/logout.php">Salir</a></li>
      </ul>
    </div>
  </div>
</nav>



  <!-- ========== Formulario de Casos ========== -->
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <!-- Se actualiza el action para apuntar a registro_caso.php -->
      <form action="registro_caso.php" method="POST">
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

        <button class="formbold-btn" type="submit">
          Registrar Caso
        </button>
      </form>
    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
</body>
</html>
