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
          <li class="navbar-dropdown active">
            <a href="#" class="dropdown-toggler" data-dropdown="dropdown-usuarios">
              Usuarios <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown" id="dropdown-usuarios">
              <li><a href="agregar_usuario.php">Agregar</a></li>
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
              <option value="abogado">Abogado</option>
              <option value="perito">Perito</option>
              <option value="juez">Juez</option>
              <option value="fiscal">Fiscal</option>
            </select>
            <label for="rol" class="formbold-form-label">Rol</label>
          </div>
        </div>

        <!-- Botón para pasar a la confirmación -->
        <button type="button" id="confirmBtn" class="formbold-btn">
          Confirmar Datos
        </button>
      </form>

      <!-- Sección de confirmación -->
      <div id="confirmation" style="display:none;">
        <h3>Confirma tus datos</h3>
        <p id="confirmIdUsuario"></p>
        <p id="confirmNombre"></p>
        <p id="confirmApellido"></p>
        <p id="confirmDespacho"></p>
        <p id="confirmCorreo"></p>
        <p id="confirmRol"></p>
        <button id="editBtn" class="formbold-btn">Editar</button>
        <button id="submitBtn" class="formbold-btn">Confirmar Registro</button>
      </div>
    </div>
  </div>

  <script src="../js/navbar.js"></script>
  <script>
    // Al hacer clic en "Confirmar Datos", se recopilan y muestran los datos (excepto la contraseña)
    document.getElementById("confirmBtn").addEventListener("click", function(){
      var idUsuario = document.getElementById("id_usuario").value;
      var nombre = document.getElementById("Nombre").value;
      var apellido = document.getElementById("Apellido").value;
      var despacho = document.getElementById("despacho").value;
      var correo = document.getElementById("Correo").value;
      var rol = document.getElementById("rol").value;

      // Puedes agregar validaciones adicionales antes de mostrar la confirmación
      if (!nombre || !apellido || !correo || !despacho || !rol || !document.getElementById("contrasena").value) {
        alert("Por favor, completa todos los campos.");
        return;
      }

      // Mostrar los datos en la sección de confirmación
      document.getElementById("confirmIdUsuario").innerText = "ID Usuario: " + (idUsuario ? idUsuario : "No especificado");
      document.getElementById("confirmNombre").innerText = "Nombre: " + nombre;
      document.getElementById("confirmApellido").innerText = "Apellido: " + apellido;
      document.getElementById("confirmDespacho").innerText = "Despacho: " + despacho;
      document.getElementById("confirmCorreo").innerText = "Correo: " + correo;
      document.getElementById("confirmRol").innerText = "Rol: " + rol;

      // Ocultar el formulario y mostrar la sección de confirmación
      document.getElementById("registrationForm").style.display = "none";
      document.getElementById("confirmation").style.display = "block";
    });

    // Botón "Editar": vuelve al formulario para hacer correcciones
    document.getElementById("editBtn").addEventListener("click", function(){
      document.getElementById("registrationForm").style.display = "block";
      document.getElementById("confirmation").style.display = "none";
    });

    // Botón "Confirmar Registro": envía el formulario
    document.getElementById("submitBtn").addEventListener("click", function(){
      document.getElementById("registrationForm").submit();
    });
  </script>
  <script src="../js/forms.js"></script>
</body>
</html>
