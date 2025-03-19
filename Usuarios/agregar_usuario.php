<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario</title>
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
        <img src="../images/techlab.png" alt="Legal Tech"  style="width:150px; height:auto;">
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
        <li class="navbar-dropdown active">
          <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
            Usuarios <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown" id="dropdown-usuarios">
            <li><a href="agregar_usuario.php">Agregar</a></li>
            <li class="separator"></li>
            <li><a href="#">Permisos
            </a></li>
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
  <div class="formbold-main-wrapper">
  <!-- Author: FormBold Team -->
  <!-- Learn More: https://formbold.com -->
    <div class="formbold-form-wrapper">
      <form action="https://formbold.com/s/FORM_ID" method="POST">
        <div class="formbold-input-flex">
          <div>
            <input
              type="text"
              name="id_usuario"
              id="id_usuario"
              placeholder="id_usuario"
              class="formbold-form-input"
              />
              <label for="Evidencia" class="formbold-form-label"> ID Usuario </label>
          </div>
          <div>
            <input
              type="text"
              name="contrasena"
              id="contrasena"
              placeholder="contrasena"
              class="formbold-form-input"
              />
              <label for="Caso" class="formbold-form-label"> Contrasena </label>
          </div>
        </div>
        <div class="formbold-input-flex">
          <div>
            <input
              type="text"
              name="Nombre"
              id="Nombre"
              placeholder="Nombre"
              class="formbold-form-input"
              />
              <label for="Evidencia" class="formbold-form-label"> Nombre </label>
          </div>
          <div>
            <input
              type="text"
              name="Apellido"
              id="Apellido"
              placeholder="Apellido"
              class="formbold-form-input"
              />
              <label for="Caso" class="formbold-form-label"> Apellidos </label>
          </div>
        </div>
        <div class="formbold-input-flex">
            <div>
              <input
                type="text"
                name="Correo"
                id="Correo"
                placeholder="Correo"
                class="formbold-form-input"
                />
                <label for="Evidencia" class="formbold-form-label"> Correo </label>
            </div>
            <div>
                <select name="rol" id="rol" class="formbold-form-input">
                    <option value="Abogado">Abogado</option>
                    <option value="Perito">Perito</option>
                    <option value="Juez">Juez</option>
                    <option value="Fiscal">Fiscal</option>
                </select>
                <label for="rol" class="formbold-form-label">Rol</label>
            </div>
          </div>
          <button class="formbold-btn">
              Registrar Usuario
          </button>
      </form>
    </div>
  </div>
  <script src="../js/navbar.js"></script>
  <script src="../js/forms.js"></script>
</body>
</html>