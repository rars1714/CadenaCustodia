<?php
session_start();

// Función para mostrar alerta con SweetAlert2
function mostrar_alerta($tipo, $titulo, $mensaje, $redireccion = 'agregar_usuario.php') {
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
                text: '$mensaje',
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

// Conexión a la base de datos
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "cadena_custodia";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    mostrar_alerta('error', 'Error de conexión', $conn->connect_error);
}

// Determinar redirección por rol
$id_rol_sesion = $_SESSION['id_rol'] ?? null;
$redireccion = ($id_rol_sesion == 4) ? 'agregar_usuario_admin.php' : 'agregar_usuario.php';

// Verificar que se envíe por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_input = trim($_POST['id_usuario'] ?? '');
    $nombre    = trim($_POST['Nombre'] ?? '');
    $apellido  = trim($_POST['Apellido'] ?? '');
    $despacho  = trim($_POST['despacho'] ?? '');
    $correo    = trim($_POST['Correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol_nombre = strtolower(trim($_POST['rol'] ?? ''));

    // Validaciones
    if (empty($id_usuario_input) || empty($nombre) || empty($apellido) || empty($despacho) || empty($correo) || empty($contrasena) || empty($rol_nombre)) {
        mostrar_alerta('error', 'Campos incompletos', 'Todos los campos son requeridos.', $redireccion);
    }

    if (!ctype_digit($id_usuario_input)) {
        mostrar_alerta('error', 'ID no válido', 'El ID del usuario debe ser solo numérico.', $redireccion);
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        mostrar_alerta('error', 'Correo inválido', 'El formato del correo no es válido.', $redireccion);
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $rol_stmt = $conn->prepare("SELECT id_rol FROM roles WHERE nombre = ?");
    $rol_stmt->bind_param("s", $rol_nombre);
    $rol_stmt->execute();
    $rol_result = $rol_stmt->get_result();

    if ($rol_result->num_rows === 0) {
        mostrar_alerta('error', 'Rol no válido', 'El rol especificado no existe.', $redireccion);
    }

    $rol_row = $rol_result->fetch_assoc();
    $id_rol = $rol_row['id_rol'];
    $rol_stmt->close();

    $stmt = $conn->prepare("INSERT INTO usuarios (id_usuario, nombre, apellido, despacho, correo, contrasena_hash, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        mostrar_alerta('error', 'Error al preparar', $conn->error, $redireccion);
    }

    $id_usuario_int = intval($id_usuario_input);
    $stmt->bind_param("isssssi", $id_usuario_int, $nombre, $apellido, $despacho, $correo, $contrasena_hash, $id_rol);

    if ($stmt->execute()) {
        mostrar_alerta('success', 'Registro exitoso', 'El usuario fue registrado correctamente.', $redireccion);
    } else {
        mostrar_alerta('error', 'Error en el registro', $stmt->error, $redireccion);
    }

    $stmt->close();
} else {
    mostrar_alerta('error', 'Acceso no autorizado', 'Este archivo solo acepta solicitudes POST.', $redireccion);
}

$conn->close();
?>
