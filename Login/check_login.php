<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

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

    if (password_verify($contrasena, $usuario['contrasena_hash']) && $usuario['id_rol'] === 4) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_nombre'] = $usuario['rol_nombre'];
        $_SESSION['id_rol'] = $usuario['id_rol'];

        // registrar en historial
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $conexion->prepare("
        INSERT INTO historial_accesos 
        (id_usuario, accion, direccion_ip)
        VALUES (?, 'login', ?)
        ");
        $stmt->bind_param("is", $usuario['id_usuario'], $ip);
        $stmt->execute();
        header("Location: /cadenacustodia/home.php");
        exit();
    }

    if (password_verify($contrasena, $usuario['contrasena_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_nombre'] = $usuario['rol_nombre'];
        $_SESSION['id_rol'] = $usuario['id_rol'];

        // registrar en historial
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $conexion->prepare("
        INSERT INTO historial_accesos 
        (id_usuario, accion, direccion_ip)
        VALUES (?, 'login', ?)
        ");
        $stmt->bind_param("is", $usuario['id_usuario'], $ip);
        $stmt->execute();
        header("Location: /cadenacustodia/home.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Correo no registrado'); window.location.href='login.php';</script>";
}
?>