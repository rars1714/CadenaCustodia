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

$roles_query = $conexion->query("SELECT id_rol, nombre FROM roles");
$roles = [];
while ($row = $roles_query->fetch_assoc()) {
    $roles[$row['id_rol']] = $row['nombre'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <!-- Fuentes -->
    <link rel="stylesheet" href="../../css/modificar.css">
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
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
              Evidencia <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-evidencia">
              <li><a href="../Evidencia/agregar_evidencia_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="../Evidencia/modificar_evidencia_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="../Casos/agregar_caso_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="../Casos/modificar_caso_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
          <li class="navbar-dropdown  active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
              Usuarios <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-usuarios">
              <li><a href="agregar_usuario_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Permisos</a></li>
              <li class="separator"></li>
              <li><a href="modificar_usuario_admin.php">Modificar</a></li>
            </ul>
          </li>
          <li><a href="#">Historial de accesos</a></li>
          <li><a href="../../Login/login.php">Salir</a></li>
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

