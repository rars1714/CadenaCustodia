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
    $rol_nombre       = strtolower(trim($_POST['rol'] ?? ''));

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($despacho) || empty($correo) || empty($contrasena) || empty($rol_nombre)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo no es válido.";
        exit;
    }


    // Encriptar la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Buscar el ID del rol en la tabla roles
    $rol_stmt = $conn->prepare("SELECT id_rol FROM roles WHERE nombre = ?");
    $rol_stmt->bind_param("s", $rol_nombre);
    $rol_stmt->execute();
    $rol_result = $rol_stmt->get_result();

    if ($rol_result->num_rows === 0) {
        echo "El rol especificado no existe.";
        exit;
    }

    $rol_row = $rol_result->fetch_assoc();
    $id_rol = $rol_row['id_rol'];
    $rol_stmt->close();

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, despacho, correo, contrasena_hash, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo "Error en la preparación: " . $conn->error;
            exit;
        }

        $stmt->bind_param("sssssi", $nombre, $apellido, $despacho, $correo, $contrasena_hash, $id_rol);

        if ($stmt->execute()) {
            echo "Registro exitoso.";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Acceso no autorizado.";
    }

// Cerrar la conexión
$conn->close();
?>
