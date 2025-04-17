<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT MAX(id_usuario) AS ultimo_id FROM usuarios");
$fila = $resultado->fetch_assoc();
$siguiente_id_usuario = $fila['ultimo_id'] + 1;
$roles_result = $conexion->query("SELECT nombre FROM roles");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro de Usuario</title>
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
          <img src="../images/techlab.png" alt="Legal Tech" style="display:block; margin: 0 auto; width:150px; height:auto;">
        </a>
      </div>
    </div>
  </nav>

  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <!-- Formulario de registro -->
      <form id="registrationForm" action="registro.php" method="POST">
        <!-- ID Usuario (opcional, si se requiere) -->
        <div class="formbold-input-flex">
          <div>
            <input
              type="text"
              name="id_usuario"
              id="id_usuario"
              placeholder="ID Usuario"
              class="formbold-form-input"
              value="<?php echo $siguiente_id_usuario; ?>" 
              readonly
            />
            <label for="id_usuario" class="formbold-form-label">ID Usuario</label>
          </div>
          <!-- Campo para contraseña, tipo password para ocultar -->
          <div>
            <input
              type="password"
              name="contrasena"
              id="contrasena"
              placeholder="Contraseña"
              class="formbold-form-input"
            />
            <label for="contrasena" class="formbold-form-label">Contraseña</label>
          </div>
        </div>

        <!-- Nombre y Apellido -->
        <div class="formbold-input-flex">
          <div>
            <input
              type="text"
              name="Nombre"
              id="Nombre"
              placeholder="Nombre"
              class="formbold-form-input"
            />
            <label for="Nombre" class="formbold-form-label">Nombre</label>
          </div>
          <div>
            <input
              type="text"
              name="Apellido"
              id="Apellido"
              placeholder="Apellido"
              class="formbold-form-input"
            />
            <label for="Apellido" class="formbold-form-label">Apellidos</label>
          </div>
        </div>

        <!-- Despacho y Correo -->
        <div class="formbold-input-flex">
          <div>
            <input
              type="text"
              name="despacho"
              id="despacho"
              placeholder="Despacho"
              class="formbold-form-input"
            />
            <label for="despacho" class="formbold-form-label">Despacho</label>
          </div>
          <div>
            <input
              type="text"
              name="Correo"
              id="Correo"
              placeholder="Correo"
              class="formbold-form-input"
            />
            <label for="Correo" class="formbold-form-label">Correo</label>
          </div>
        </div>

        <!-- Rol -->
        <div class="formbold-input-flex">
          <div>
            <select name="rol" id="rol" class="formbold-form-input">
              <?php while ($rol = $roles_result->fetch_assoc()) { ?>
                <option value="<?= $rol['nombre'] ?>"><?= ucfirst($rol['nombre']) ?></option>
              <?php } ?>
            </select>
            <label for="rol" class="formbold-form-label">Rol</label>
          </div>
        </div>
        <!-- Botón para pasar a la confirmación -->
        <button type="button" id="confirmBtn" class="formbold-btn" style="width: 250px; display: block; margin: 0 auto;">
          Confirmar Datos
        </button>

      </form>

      <!-- Sección de confirmación -->
      <div id="confirmation">
        <h3>Confirma tus datos</h3>
        <ul>
          <li id="confirmIdUsuario"></li>
          <li id="confirmNombre"></li>
          <li id="confirmApellido"></li>
          <li id="confirmDespacho"></li>
          <li id="confirmCorreo"></li>
          <li id="confirmRol"></li>
        </ul>
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
          <button id="editBtn" class="formbold-btn">Editar</button>
          <button id="submitBtn" class="formbold-btn">Confirmar Registro</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script src="../js/registro/registro_usuario.js"></script>
  <script src="../js/forms.js"></script>
</body>
</html>
