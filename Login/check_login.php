<?php
session_start();

// Conexión
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
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

// Variables del formulario
$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Buscar el usuario
$query = "SELECT u.*, r.nombre as rol_nombre
          FROM usuarios u 
          JOIN roles r ON u.id_rol = r.id_rol 
          WHERE u.correo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($contrasena, $usuario['contrasena_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_nombre'] = $usuario['rol_nombre'];
        $_SESSION['id_rol'] = $usuario['id_rol'];

        // Registrar en historial
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $conexion->prepare("
            INSERT INTO historial_accesos 
            (id_usuario, accion, direccion_ip)
            VALUES (?, 'login', ?)
        ");
        $stmt->bind_param("is", $usuario['id_usuario'], $ip);
        $stmt->execute();

        // Redirigir
        header("Location: /cadenacustodia/home.php");
        exit();
    } else {
        mostrar_alerta('error', 'Contraseña Incorrecta', 'La contraseña ingresada es incorrecta.', 'login.php');
    }
} else {
    mostrar_alerta('error', 'Correo no registrado', 'El correo ingresado no existe en el sistema.', 'login.php');
}
?>
