<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}


$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

require_once '../validar_permisos.php';
$perm_agregar_evidencia   = tiene_permiso($conexion, $_SESSION['id_rol'], 'ingresar_evidencia');
$perm_consultar_evidencia = tiene_permiso($conexion, $_SESSION['id_rol'], 'consultar_evidencia');
$perm_agregar_casos       = tiene_permiso($conexion, $_SESSION['id_rol'], 'crear_casos');
$perm_consultar_casos     = tiene_permiso($conexion, $_SESSION['id_rol'], 'consultar_casos');

$id_usuario = $_SESSION['usuario_id'];
$id_rol = $_SESSION['id_rol'];

// Verificar permiso para consultar evidencia
if (!tiene_permiso($conexion, $id_rol, 'consultar_evidencia')) {
    echo "<script>
        alert('No tiene permiso para consultar evidencia.');
        window.location.href = '../home.php';
    </script>";
    exit();
}

// Consulta modificada: Evidencias de casos donde el usuario participa
$sql = "SELECT e.* 
        FROM evidencias e
        INNER JOIN casos c ON e.id_caso = c.id_caso 
        WHERE c.id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Evidencia</title>
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
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
              Evidencia <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-evidencia">
              <?php if ($perm_agregar_evidencia): ?>
                <li><a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/agregar_evidencia_admin.php' : '../Evidencia/agregar_evidencia.php' ?>">Agregar</a></li>
                <li class="separator"></li>
              <?php endif; ?>
              <?php if ($perm_consultar_evidencia): ?>
                <li><a href="<?= $id_rol === 4 ? '../../Admin/Evidencia/modificar_evidencia_admin.php' : '../Evidencia/modificar_evidencia.php' ?>">Consultar</a></li>
                <li class="separator"></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <!-- CASOS -->
        <?php if ($perm_agregar_casos || $perm_consultar_casos): ?>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <?php if ($perm_agregar_casos): ?>
                <li><a href="<?= $id_rol === 4 ? '../../Admin/Casos/agregar_caso_admin.php' : '../Casos/agregar_caso.php' ?>">Agregar</a></li>
                <li class="separator"></li>
              <?php endif; ?>
              <?php if ($perm_consultar_casos): ?>
                <li><a href="<?= $id_rol === 4 ? '../../Admin/Casos/modificar_caso_admin.php' : '../Casos/modificar_caso.php' ?>">Consultar</a></li>
                <li class="separator"></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <!-- ÁREA DE TRABAJO SOLO PARA ROL 2 -->
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

        <!-- SOLO PARA ADMIN -->
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
  <!-- Tabla de Evidencias -->
<table>
  <thead>
    <tr>
      <th>ID Evidencia</th>
      <th>ID Caso</th>
      <th>ID Usuario</th>
      <th>Tipo de Evidencia</th>
      <th>Descripción</th>
      <th>Nombre de Archivo</th>
      <th>Ver Archivo</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
    <tr>
      <td><?= $fila['id_evidencia'] ?></td>
      <td><?= $fila['id_caso'] ?></td>
      <td><?= $fila['id_usuario'] ?></td>
      <td><?= ucfirst($fila['tipo_evidencia']) ?></td>
      <td><?= htmlspecialchars($fila['descripcion']) ?></td>
      <td><?= htmlspecialchars($fila['nombre_archivo']) ?></td>
      <td>
          <a href="<?= htmlspecialchars($fila['ruta_archivo']) ?>" target="_blank">
            <?= htmlspecialchars($fila['nombre_archivo']) ?>
          </a>
        </td>
    </tr>
    <?php } ?>
  </tbody>
</table>


  <!-- Scripts -->
  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
</body>
</html>
