<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login/login.css">
</head>
<body>
  <div class="login-box">
    <a href="#">
      <img src="../images/techlab.png" alt="Legal Tech">
    </a>
    <form action="check_login.php" method="POST">
      <input
        type="text"
        name="correo"
        id="correo"
        placeholder="Correo electrónico"
        class="formbold-form-input"
        required
      />
      <input
        type="password"
        name="contrasena"
        id="contrasena"
        placeholder="Contraseña"
        class="formbold-form-input"
        required
      />
      <div style="display: flex; flex-direction: column; gap: 10px;">
        <button type="submit" class="formbold-btn">Entrar</button>
        <button
          type="button"
          class="formbold-btn"
          onclick="window.location.href='../Usuarios/agregar_usuario.php'">
          Crear Usuario
        </button>
      </div>
    </form>
  </div>
</body>
</html>
