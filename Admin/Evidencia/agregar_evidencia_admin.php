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

// 1) Saca la URI o el nombre del script actual
$current = $_SERVER['REQUEST_URI']; 
// 2) Marca cada sección como activa si la URI la contiene
$isEvidencia = strpos($current, '/Evidencia/') !== false;
$isCasos    = strpos($current, '/Casos/') !== false;
$isUsuarios = strpos($current, '/Usuarios/') !== false;
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
  <link rel="stylesheet" href="../../css/navbar.css">
  <link rel="stylesheet" href="../../css/forms.css">
  <link rel="stylesheet" href="../../css/confirmacion/check_usuario.css">
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

        <!-- EVIDENCIA -->
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

        <!-- CASOS -->
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
          <!-- USUARIOS (solo admin) -->
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
          <!-- HISTORIAL DE ACCESOS (solo admin) -->
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




  <!-- ========== Formulario de Evidencia ========== -->
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <!-- Se actualizó el action, método y enctype -->
      <form id="registrationForm" action="../../Evidencia/registro_evidencia.php" method="POST" enctype="multipart/form-data">
        <div class="formbold-input-flex">
          <div>
            <!-- Aunque id_evidencia sea autoincrement, se muestra por si deseas asignar manual -->
            <input type="text" name="id_evidencia" id="id_evidencia" placeholder="Número de Evidencia" class="formbold-form-input" value="<?php echo $siguiente_id; ?>" readonly/>
            <label for="id_evidencia" class="formbold-form-label">Número de Evidencia</label>
          </div>
          <div>
            <input type="text" name="id_caso" id="id_caso" placeholder="Número de Caso" class="formbold-form-input" />
            <label for="id_caso" class="formbold-form-label">Número de Caso</label>
          </div>
        </div>

        <div class="formbold-input-flex">
          <div>
            <!-- Rellenar con el ID del usuario logueado (readonly) -->
            <input
              type="text"
              name="id_usuario"
              id="id_usuario"
              placeholder="ID Usuario"
              class="formbold-form-input">   
              <!-- value="<?php echo htmlspecialchars($_SESSION['usuario_id']); ?>" readonly -->
            <label for="id_usuario" class="formbold-form-label">Usuario</label>
          </div>
          <div>
            <select name="tipo_evidencia" id="tipo_evidencia" class="formbold-form-input">
              <option value="documento">PDF</option>
              <option value="imagen">Imagen</option>
              <option value="video">Video</option>
              <option value="audio">Audio</option>
              <option value="otro">Otro</option>
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
          </div>
          <label for="upload" class="formbold-input-label">
            Adjuntar archivos
            <input type="file" id="upload" name="archivo" multiple>
          </label>
        </div>
        <button type="button" id="confirm" class="formbold-btn" style="display: block; margin: 30px auto;">
          Confirmar Evidencia
        </button>

      </form>
      <!-- Sección de confirmación -->
      <div id="confirmation">
        <h3>Confirma tus datos</h3>
        <ul>
          <li id="confirmid_evidencia"></li>
          <li id="confirmid_caso"></li>
          <li id="confirmtipo_evidencia"></li>
          <li id="confirmdescripcion"></li>
          <li id="confirmnombre_archivo"></li>
        </ul>
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
          <button id="editBtn" class="formbold-btn">Editar</button>
          <button id="submitBtn" class="formbold-btn">Confirmar Registro</button>
        </div>
      </div>
    </div>
  </div>
  <script src="../../js/registro/tipo_archivo_evidencia.js"></script>
  <script src="../../js/navbar.js"></script>
  <script src="../../js/registro/registro_evidencia.js"></script>
  <script src="../../js/forms.js"></script>
</body>
</html>
