
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-box img {
      width: 180px;
      margin-bottom: 30px;
    }

    .formbold-form-input {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    .formbold-btn {
      width: 100%;
      padding: 12px;
      background-color: #4a90e2;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    .formbold-btn:hover {
      background-color: #357ABD;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <a href="#">
      <img src="images/techlab.png" alt="Legal Tech">
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
      <button type="submit" class="formbold-btn">Entrar</button>
    </form>
  </div>
</body>
</html>
