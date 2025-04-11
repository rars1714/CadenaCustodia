<?php
// cadenacustodia/Evidencia/agregar_evidencia.php
session_start();
// Verificar si NO hay sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

  $conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta del último id_caso
$resultado = $conexion->query("SELECT MAX(id_evidencia) AS ultimo_id FROM evidencias");
$fila = $resultado->fetch_assoc();
$siguiente_id = $fila['ultimo_id'] + 1;

require_once '../permisos.php';
$rol = ucfirst(strtolower(trim($_SESSION['rol'])));


if (!isset($permisos[$rol]['ingresar_evidencia']) || !$permisos[$rol]['ingresar_evidencia']) {
  echo "<script>
      alert('No cuenta con permisos para ingresar evidencia.');
      window.location.href = '../home.php';
  </script>";
  exit();
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
          <span></span>
          <span></span>
          <span></span>
        </button>
        <a href="#">
          <img src="../images/techlab.png" alt="Legal Tech" style="width:150px; height:auto;">
        </a>
      </div>
      <div class="navbar-menu" id="open-navbar1">
        <ul class="navbar-nav">
          <li class="navbar-item">
            <a href="../home.php">
              <?php echo htmlspecialchars($_SESSION['nombre']); ?> (ID: <?php echo htmlspecialchars($_SESSION['usuario_id']); ?>)
            </a>
          </li>
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
              Evidencia <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-evidencia">
              <!-- Muestra el nombre y el ID del usuario logueado -->
              <li class="separator"></li>
              <li><a href="agregar_evidencia.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="modificar_evidencia.php">Consultar</a></li>
            </ul>
          </li>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="../Casos/agregar_caso.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="../Casos/modificar_caso.php">Consultar</a></li>
            </ul>
          </li>
          <li><a href="../Login/logout.php">Salir</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ========== Formulario de Evidencia ========== -->
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <!-- Se actualizó el action, método y enctype -->
      <form action="registro_evidencia.php" method="POST" enctype="multipart/form-data">
        <div class="formbold-input-flex">
          <div>
            <input type="text" name="id_evidencia" id="id_evidencia" placeholder="Número de Evidencia"
            class="formbold-form-input" 
            value="<?php echo $siguiente_id; ?>" readonly>
            <label for="id_evidencia" class="formbold-form-label">Número de Evidencia</label>
          </div>
          <div>
            <input type="text" name="id_caso" id="id_caso" placeholder="Número de Caso" class="formbold-form-input" />
            <label for="id_caso" class="formbold-form-label">Número de Caso</label>
          </div>
        </div>

        <div class="formbold-input-flex">
          <div>
            <select name="tipo_evidencia" id="tipo_evidencia" class="formbold-form-input">
              <option value="PDF">PDF</option>
              <option value="Imagen">Imagen</option>
              <option value="Video">Video</option>
              <option value="Audio">Audio</option>
              <option value="Otro">Otro</option>
            </select>
            <label for="tipo_evidencia" class="formbold-form-label">Tipo de Evidencia</label>
          </div>
        </div>

        <div class="formbold-textarea">
          <textarea rows="6" name="Descripcion" id="Descripcion" placeholder="Descripción de evidencia." class="formbold-form-input"></textarea>
          <label for="Descripcion" class="formbold-form-label">Descripción</label>
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
        <button class="formbold-btn" type="submit" style="margin-top: 20px;">
          Registrar Evidencia
        </button>
      </form>
    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
  <script src="../js/registro/registro_evidencia.js"></script>

</body>
</html>
