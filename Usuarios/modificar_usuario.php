<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT id_usuario, Nombre, Apellido, despacho, Correo, rol FROM usuarios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/usuario/modificar_usuario.css">
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
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-evidencia">
              Evidencia <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-evidencia">
              <li><a href="../Evidencia/agregar_evidencia.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Consultar</a></li>
              <li class="separator"></li>
              <li><a href="#">Modificar</a></li>
            </ul>
          </li>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="../Casos/agregar_caso.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Consultar</a></li>
              <li class="separator"></li>
              <li><a href="#">Modificar</a></li>
            </ul>
          </li>
          <li class="navbar-dropdown  active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
              Usuarios <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-usuarios">
              <li><a href="agregar_usuario.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Permisos</a></li>
              <li class="separator"></li>
              <li><a href="modificar_usuario.php">Modificar</a></li>
            </ul>
          </li>
          <li><a href="#">Historial de accesos</a></li>
          <li><a href="#">Salir</a></li>
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
            <option value="abogado" <?= $fila['rol'] == 'abogado' ? 'selected' : '' ?>>Abogado</option>
            <option value="perito" <?= $fila['rol'] == 'perito' ? 'selected' : '' ?>>Perito</option>
            <option value="juez" <?= $fila['rol'] == 'juez' ? 'selected' : '' ?>>Juez</option>
            <option value="fiscal" <?= $fila['rol'] == 'fiscal' ? 'selected' : '' ?>>Fiscal</option>
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
  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
  <script>document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
        let row = this.closest("tr");
        row.querySelectorAll("input, select").forEach(input => input.removeAttribute("disabled"));
        row.querySelector(".edit-btn").style.display = "none";
        row.querySelector(".save-btn").style.display = "inline-block";
    });
});

document.querySelectorAll(".save-btn").forEach(button => {
    button.addEventListener("click", function () {
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let nombre = row.cells[1].querySelector("input").value;
        let apellido = row.cells[2].querySelector("input").value;
        let despacho = row.cells[3].querySelector("input").value;
        let correo = row.cells[4].querySelector("input").value;
        let rol = row.cells[5].querySelector("select").value;

        let formData = new FormData();
        formData.append("id", id);
        formData.append("Nombre", nombre);
        formData.append("Apellido", apellido);
        formData.append("despacho", despacho);
        formData.append("Correo", correo);
        formData.append("rol", rol);

        fetch("actualizar_usuario.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            row.querySelectorAll("input, select").forEach(input => input.setAttribute("disabled", "disabled"));
            row.querySelector(".edit-btn").style.display = "inline-block";
            row.querySelector(".save-btn").style.display = "none";
        })
        .catch(error => console.error("Error:", error));
    });
});
</script>
</body>
</html>

