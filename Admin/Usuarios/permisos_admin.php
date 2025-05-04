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

// Obtener roles y permisos, excluyendo al administrador (id_rol = 4)
$roles = $conexion->query("SELECT id_rol, nombre FROM roles WHERE id_rol != 4");
$permisos = [];
$res = $conexion->query("SELECT id_rol, accion, valor FROM permisos_roles");
while ($row = $res->fetch_assoc()) {
    $permisos[$row['id_rol']][$row['accion']] = $row['valor'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Permisos</title>
  <link rel="stylesheet" href="../../css/modificar.css">
  <link rel="stylesheet" href="../../css/navbar.css">
  <link rel="stylesheet" href="../../css/forms.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>
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

        <li class="navbar-dropdown">
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
          <li class="navbar-dropdown  active">
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
        <th>Rol</th>
        <th>Ingresar Evidencia</th>
        <th>Crear Casos</th>
        <th>Consultar Evidencia</th>
        <th>Consultar Casos</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($rol = $roles->fetch_assoc()):
        $id = $rol['id_rol'];
        $p = $permisos[$id] ?? [];
      ?>
      <tr data-id="<?= $id ?>">
        <td><?= htmlspecialchars($rol['nombre']) ?></td>
        <td>
          <select disabled>
            <option value="1" <?= !empty($p['ingresar_evidencia']) ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= empty($p['ingresar_evidencia']) ? 'selected' : '' ?>>No</option>
          </select>
        </td>
        <td>
          <select disabled>
            <option value="1" <?= !empty($p['crear_casos']) ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= empty($p['crear_casos']) ? 'selected' : '' ?>>No</option>
          </select>
        </td>
        <td>
          <select disabled>
            <option value="1" <?= !empty($p['consultar_evidencia']) ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= empty($p['consultar_evidencia']) ? 'selected' : '' ?>>No</option>
          </select>
        </td>
        <td>
          <select disabled>
            <option value="1" <?= !empty($p['consultar_casos']) ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= empty($p['consultar_casos']) ? 'selected' : '' ?>>No</option>
          </select>
        </td>
        <td>
          <button class="edit-btn">Editar</button>
          <button class="save-btn" style="display:none;">Guardar</button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <script src="../../js/navbar.js"></script>
  <script>
    document.querySelectorAll(".edit-btn").forEach(button => {
      button.addEventListener("click", function () {
        let row = this.closest("tr");
        row.querySelectorAll("select").forEach(select => select.removeAttribute("disabled"));
        row.querySelector(".edit-btn").style.display = "none";
        row.querySelector(".save-btn").style.display = "inline-block";
      });
    });

    document.querySelectorAll(".save-btn").forEach(button => {
      button.addEventListener("click", function () {
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let selects = row.querySelectorAll("select");
        let acciones = [
          'ingresar_evidencia',
          'crear_casos',
          'consultar_evidencia',
          'consultar_casos',
        ];

        let formData = new FormData();
        formData.append("id_rol", id);
        selects.forEach((select, i) => {
          formData.append("permisos[" + acciones[i] + "]", select.value);
        });

        fetch("actualizar_permisos.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          row.querySelectorAll("select").forEach(input => input.setAttribute("disabled", "disabled"));
          row.querySelector(".edit-btn").style.display = "inline-block";
          row.querySelector(".save-btn").style.display = "none";
        })
        .catch(error => console.error("Error:", error));
      });
    });
  </script>
</body>
</html>