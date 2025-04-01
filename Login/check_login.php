<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$query = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if ($correo === "admin@admin.com" && $contrasena === "admin") {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['correo']     = $usuario['correo'];
        $_SESSION['nombre']     = $usuario['nombre']; 
        header("Location: /cadenacustodia/Admin/Evidencia/agregar_evidencia_admin.php");
        exit();
    }
    if (password_verify($contrasena, $usuario['contrasena_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombre']; 

        header("Location: /cadenacustodia/Evidencia/agregar_evidencia.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Correo no registrado'); window.location.href='login.php';</script>";
}

?>
