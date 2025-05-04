<?php
// registro_admin.php

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

// Función para mostrar SweetAlert2
function mostrar_alerta($tipo, $titulo, $texto, $redireccion) {
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$tipo',
                title: '$titulo',
                text: '$texto',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '$redireccion';
            });
        </script>
    </body>
    </html>
    ";
    exit;
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
    $rol_nombre = strtolower(trim($_POST['rol'] ?? ''));

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($despacho) || empty($correo) || empty($contrasena) || empty($rol_nombre)) {
        mostrar_alerta('error', 'Campos Requeridos', 'Todos los campos son obligatorios.', 'agregar_usuario_admin.php');
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        mostrar_alerta('error', 'Correo Inválido', 'El correo no tiene un formato válido.', 'agregar_usuario_admin.php');
    }

    // Validar que el rol esté entre los permitidos
    $roles_permitidos = ['abogado', 'perito', 'juez', 'fiscal', 'admin'];
    if (!in_array($rol_nombre, $roles_permitidos)) {
        mostrar_alerta('error', 'Rol No Válido', 'El rol ingresado no es válido.', 'agregar_usuario_admin.php');
    }

    // Encriptar la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Buscar ID del rol en tabla 'roles'
    $rol_stmt = $conn->prepare("SELECT id_rol FROM roles WHERE nombre = ?");
    $rol_stmt->bind_param("s", $rol_nombre);
    $rol_stmt->execute();
    $rol_result = $rol_stmt->get_result();

    if ($rol_result->num_rows === 0) {
        mostrar_alerta('error', 'Rol No Existente', 'El rol especificado no existe en el sistema.', 'agregar_usuario_admin.php');
    }

    $rol_row = $rol_result->fetch_assoc();
    $id_rol = $rol_row['id_rol'];
    $rol_stmt->close();

    // Preparar el INSERT (permitiendo id_usuario si se usa manualmente)
    if ($id_usuario_input !== '') {
        $stmt = $conn->prepare("INSERT INTO usuarios (id_usuario, nombre, apellido, despacho, correo, contrasena_hash, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            mostrar_alerta('error', 'Error en Preparación', 'Error al preparar el registro: ' . $conn->error, 'agregar_usuario_admin.php');
        }
        $stmt->bind_param("isssssi", $id_usuario_input, $nombre, $apellido, $despacho, $correo, $contrasena_hash, $id_rol);
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, despacho, correo, contrasena_hash, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            mostrar_alerta('error', 'Error en Preparación', 'Error al preparar el registro: ' . $conn->error, 'agregar_usuario_admin.php');
        }
        $stmt->bind_param("sssssi", $nombre, $apellido, $despacho, $correo, $contrasena_hash, $id_rol);
    }

    if ($stmt->execute()) {
        mostrar_alerta('success', 'Registro Exitoso', 'El usuario fue registrado correctamente.', 'agregar_usuario_admin.php');
    } else {
        mostrar_alerta('error', 'Error en Registro', 'Hubo un problema al registrar el usuario: ' . $stmt->error, 'agregar_usuario_admin.php');
    }

    $stmt->close();
} else {
    mostrar_alerta('error', 'Acceso No Autorizado', 'Acceso no permitido.', 'agregar_usuario_admin.php');
}

// Cerrar conexión
$conn->close();
?>
