<?php
// registro.php

// Configuración de la conexión a la base de datos
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "cadena_custodia";

// Conexión a la base de datos con mysqli
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Verificar que se envíe el formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar los datos del formulario
    $id_usuario_input = trim($_POST['id_usuario'] ?? '');
    $nombre    = trim($_POST['Nombre'] ?? '');
    $apellido  = trim($_POST['Apellido'] ?? '');
    $despacho  = trim($_POST['despacho'] ?? '');
    $correo    = trim($_POST['Correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol       = strtolower(trim($_POST['rol'] ?? ''));

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($despacho) || empty($correo) || empty($contrasena) || empty($rol)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo no es válido.";
        exit;
    }

    // Validar que el rol esté entre los permitidos
    $roles_permitidos = ['abogado', 'perito', 'juez', 'fiscal', 'admin'];
    if (!in_array($rol, $roles_permitidos)) {
        echo "El rol ingresado no es válido.";
        exit;
    }

    // Encriptar la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Si se envió un id_usuario manualmente, se puede usar; de lo contrario, la base de datos lo generará automáticamente
    // Preparar la sentencia SQL para insertar el nuevo usuario
    // Se omite el campo id_usuario si se deja que sea autoincrementable
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, despacho, correo, contrasena_hash, rol) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error en la preparación: " . $conn->error;
        exit;
    }

    // Asociar parámetros a la sentencia preparada
    $stmt->bind_param("ssssss", $nombre, $apellido, $despacho, $correo, $contrasena_hash, $rol);

    // Ejecutar la sentencia y verificar que se inserte correctamente
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        // Si hay error, por ejemplo, si el correo ya existe (violación de UNIQUE)
        echo "Error en el registro: " . $stmt->error;
    }

    // Cerrar la sentencia
    $stmt->close();
} else {
    echo "Acceso no autorizado.";
}

// Cerrar la conexión
$conn->close();
?>
