<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

require_once '../permisos.php';

$rol = ucfirst(strtolower(trim($_SESSION['rol'])));
$id_usuario = $_SESSION['usuario_id'];

// Verifica permiso de consulta
if (!isset($permisos[$rol]['consultar_casos']) || $permisos[$rol]['consultar_casos'] === false) {
    echo "<script>alert('No tiene permiso para consultar casos.'); window.location.href = '../home.php';</script>";
    exit();
}

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta solo los casos del usuario
$sql = "SELECT id_caso, nombre_caso, fecha_creacion, descripcion, estado 
        FROM casos 
        WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultar Casos</title>
  <link rel="stylesheet" href="../css/modificar.css">
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
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
              Evidencia <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-evidencia">
              <li><a href="../Evidencia/agregar_evidencia.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="../Evidencia/modificar_evidencia.php">Consultar</a></li>
            </ul>
          </li>
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="agregar_caso.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="modificar_caso.php">Consultar</a></li>
            </ul>
          </li>
          <li><a href="../Login/logout.php">Salir</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Tabla de Casos -->
  <table>
    <thead>
      <tr>
        <th>ID Caso</th>
        <th>Nombre del Caso</th>
        <th>Fecha Creación</th>
        <th>Usuario</th>
        <th>Descripción</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($fila = $resultado->fetch_assoc()) { ?>
      <tr>
        <td><?= $fila['id_caso'] ?></td>
        <td><?= htmlspecialchars($fila['nombre_caso']) ?></td>
        <td><?= $fila['fecha_creacion'] ?></td>
        <td>
          <select disabled>
            <option value="<?= htmlspecialchars($_SESSION['usuario_id']) ?>" selected>
              <?= htmlspecialchars($_SESSION['nombre']) ?> (ID: <?= htmlspecialchars($_SESSION['usuario_id']) ?>)
            </option>
          </select>
        </td>
        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
        <td><?= ucfirst($fila['estado']) ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <script src="../js/navbar.js"></script>
</body>
</html>
