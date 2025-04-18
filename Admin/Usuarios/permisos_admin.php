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

// Obtener roles y permisos
$roles = $conexion->query("SELECT id_rol, nombre FROM roles");
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
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
              Usuarios <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-usuarios">
              <li><a href="agregar_usuario_admin.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Permisos</a></li>
              <li class="separator"></li>
              <li><a href="modificar_usuario_admin.php">Consultar</a></li>
              <li class="separator"></li>
            </ul>
          </li>
          <li><a href="#">Historial de accesos</a></li>
          <li><a href="../../Login/login.php">Salir</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ========== Tabla de Permisos ========== -->
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

  <!-- Scripts -->
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
