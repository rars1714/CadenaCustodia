<?php
// cadenacustodia/Evidencia/agregar_evidencia.php
session_start();
// Verificar si NO hay sesión activa

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: Login/login.php");
    exit();
}

require_once 'validar_permisos.php';

  $conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Evidencia</title>
  <!-- Tus fuentes y CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/forms.css">
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
        <img src="images/techlab.png" alt="Legal Tech" style="width:150px; height:auto;">
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
                            ? 'Admin/Evidencia/agregar_evidencia_admin.php' 
                            : 'Evidencia/agregar_evidencia.php' ?>">
                Agregar
              </a>
            </li>
            <li class="separator"></li>
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? 'Admin/Evidencia/modificar_evidencia_admin.php' 
                            : 'Evidencia/modificar_evidencia.php' ?>">
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
                            ? 'Admin/Casos/agregar_caso_admin.php' 
                            : 'Casos/agregar_caso.php' ?>">
                Agregar
              </a>
            </li>
            <li class="separator"></li>
            <li>
              <a href="<?= $_SESSION['id_rol'] === 4 
                            ? 'Admin/Casos/modificar_caso_admin.php' 
                            : 'Casos/modificar_caso.php' ?>">
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
              <li><a href="Admin/Usuarios/agregar_usuario_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="Usuarios/permisos_admin.php">Permisos</a></li>
              <li class="separator"></li>
              <li><a href="Admin/Usuarios/modificar_usuario_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
          <!-- HISTORIAL DE ACCESOS (solo admin) -->
          <li><a href="Admin/Usuarios/historial_accesos.php">Historial de accesos</a></li>
        <?php endif; ?>

        <li><a href="Login/logout.php">Salir</a></li>
      </ul>
    </div>
  </div>
</nav>
  <script src="js/navbar.js"></script>
  <script src="js/forms.js"></script>
  <script src="js/registro/registro_evidencia.js"></script>
</body>

</html>