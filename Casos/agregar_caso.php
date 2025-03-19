<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario</title>
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
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-casos">
              Casos <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-casos">
              <li><a href="agregar_caso.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Consultar</a></li>
              <li class="separator"></li>
              <li><a href="#">Modificar</a></li>
            </ul>
          </li>
          <li class="navbar-dropdown">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
              Usuarios <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-usuarios">
              <li><a href="../Usuarios/agregar_usuario.php">Agregar</a></li>
              <li class="separator"></li>
              <li><a href="#">Permisos</a></li>
              <li class="separator"></li>
              <li><a href="#">Modificar</a></li>
            </ul>
          </li>
          <li><a href="#">Historial de accesos</a></li>
          <li><a href="#">Salir</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ========== Formulario de Casos ========== -->
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <form action="https://formbold.com/s/FORM_ID" method="POST">
        <div class="formbold-input-flex">
          <div>
            <input type="text" name="id_caso" id="id_caso" class="formbold-form-input" value="1" readonly>
            <label for="id_caso" class="formbold-form-label">ID del Caso</label>
          </div>
          <div>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="formbold-form-input" value="<?php echo date('Y-m-d'); ?>" readonly>
            <label for="fecha_inicio" class="formbold-form-label">Fecha de Inicio</label>
          </div>
        </div>
        <!-- Grupo 1: Nombre del Caso y Usuario -->
        <div class="formbold-input-flex">
          <div>
            <input type="text" name="nombre_caso" id="nombre_caso" placeholder="Nombre del caso" class="formbold-form-input" />
            <label for="nombre_caso" class="formbold-form-label">Nombre del Caso</label>
          </div>
          <div>
            <input type="text" name="usuario" id="usuario" placeholder="ID del Usuario" class="formbold-form-input" />
            <label for="usuario" class="formbold-form-label">Usuario</label>
          </div>
        </div>
        <div class="formbold-input-flex">
          <div>
            <input type="text" name="estado_display" id="estado_display" class="formbold-form-input" value="Abierto" disabled>
            <!-- Input hidden para enviar el valor -->
            <input type="hidden" name="estado" value="Abierto">
            <label for="estado_display" class="formbold-form-label">Estado</label>
          </div>
        </div>

        <div class="formbold-textarea">
          <textarea rows="6" name="descripcion" id="descripcion" placeholder="Descripción del caso." class="formbold-form-input"></textarea>
          <label for="descripcion" class="formbold-form-label">Descripción</label>
        </div>

        <button class="formbold-btn" type="submit">
          Registrar Caso
        </button>
      </form>
    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
</body>
</html>
