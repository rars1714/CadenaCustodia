<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../Login/login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT id_caso, nombre_caso, fecha_creacion, descripcion, estado, id_usuario FROM casos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Casos</title>
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
        <li class="navbar-dropdown active">
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
        <li class="navbar-dropdown">
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



<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre del Caso</th>
      <th>Fecha</th>
      <th>Usuario</th>
      <th>Descripción</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
    <tr data-id="<?= $fila['id_caso'] ?>">
      <td><?= $fila['id_caso'] ?></td>
      <td><input type="text" value="<?= $fila['nombre_caso'] ?>" disabled></td>
      <td><input type="date" value="<?= $fila['fecha_creacion'] ?>" disabled></td>
      <td><input type="text" value="<?= $fila['id_usuario'] ?>" disabled></td>
      <td><input type="text" value="<?= $fila['descripcion'] ?>" disabled></td>
      <td>
        <select disabled>
          <option value="abierto" <?= $fila['estado'] == 'abierto' ? 'selected' : '' ?>>Abierto</option>
          <option value="cerrado" <?= $fila['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
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
<script>
document.querySelectorAll(".edit-btn").forEach(button => {
  button.addEventListener("click", function () {
    let row = this.closest("tr");
    row.querySelectorAll("input, select").forEach(el => el.removeAttribute("disabled"));
    row.querySelector(".edit-btn").style.display = "none";
    row.querySelector(".save-btn").style.display = "inline-block";
  });
});

document.querySelectorAll(".save-btn").forEach(button => {
  button.addEventListener("click", function () {
    let row = this.closest("tr");
    let id = row.getAttribute("data-id");
    let nombre = row.cells[1].querySelector("input").value;
    let fecha = row.cells[2].querySelector("input").value;
    let usuario = row.cells[3].querySelector("input").value;
    let descripcion = row.cells[4].querySelector("input").value;
    let estado = row.cells[5].querySelector("select").value;

    let formData = new FormData();
    formData.append("id_caso", id);
    formData.append("nombre_caso", nombre);
    formData.append("fecha_creacion", fecha);
    formData.append("id_usuario", usuario);
    formData.append("descripcion", descripcion);
    formData.append("estado", estado);

    fetch("actualizar_caso.php", {
      method: "POST",
      body: formData
    })
    .then(r => r.text())
    .then(data => {
      alert(data);
      row.querySelectorAll("input, select").forEach(el => el.setAttribute("disabled", true));
      row.querySelector(".edit-btn").style.display = "inline-block";
      row.querySelector(".save-btn").style.display = "none";
    })
    .catch(error => console.error("Error:", error));
  });
});
</script>
</body>
</html>
