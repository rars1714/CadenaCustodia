<?php
session_start();
// Verificar si NO hay sesión activa
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../../Login/login.php");
  exit();
}
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT id_evidencia, id_caso, id_usuario, tipo_evidencia, descripcion, nombre_archivo FROM evidencias";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Evidencia</title>
  <link rel="stylesheet" href="../../css/modificar.css">
  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../css/navbar.css">
  <link rel="stylesheet" href="../../css/forms.css">
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
              <li><a href="agregar_evidencia_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="modificar_evidencia_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="../../Admin/Casos/agregar_caso_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="../../Admin/Casos/modificar_caso_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
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
          <li><a href="#">Historial de accesos</a></li>
          <li><a href="../../Login/logout.php">Salir</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ========== Tabla de Evidencia ========== -->
  <table>
    <thead>
      <tr>
        <th>ID Evidencia</th>
        <th>ID Caso</th>
        <th>ID Usuario</th>
        <th>Tipo de Evidencia</th>
        <th>Descripción</th>
        <th>Nombre de Archivo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($fila = $resultado->fetch_assoc()) { ?>
      <tr data-id="<?= $fila['id_evidencia'] ?>">
        <td><?= $fila['id_evidencia'] ?></td>
        <td><input type="text" value="<?= $fila['id_caso'] ?>" disabled></td>
        <td><input type="text" value="<?= $fila['id_usuario'] ?>" disabled></td>
        <td>
          <select disabled>
            <option value="documento" <?= $fila['tipo_evidencia'] == 'documento' ? 'selected' : '' ?>>Documento</option>
            <option value="imagen" <?= $fila['tipo_evidencia'] == 'imagen' ? 'selected' : '' ?>>Imagen</option>
            <option value="video" <?= $fila['tipo_evidencia'] == 'video' ? 'selected' : '' ?>>Video</option>
            <option value="audio" <?= $fila['tipo_evidencia'] == 'audio' ? 'selected' : '' ?>>Audio</option>
            <option value="otro" <?= $fila['tipo_evidencia'] == 'otro' ? 'selected' : '' ?>>Otro</option>
          </select>
        </td>
        <td><input type="text" value="<?= $fila['descripcion'] ?>" disabled></td>
        <td><input type="text" value="<?= $fila['nombre_archivo'] ?>" disabled></td>
        <td>
          <button class="edit-btn">Editar</button>
          <button class="save-btn" style="display:none;">Guardar</button>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- Scripts -->
  <script src="../../js/navbar.js"></script>
  <script src="../../js/forms.js"></script>
  <script src="../../js/modificar/modificar_evidencia.js"></script>
</body>
</html>
