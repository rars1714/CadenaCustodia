<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$id       = $_POST["id"];
$nombre   = $_POST["Nombre"];
$apellido = $_POST["Apellido"];
$despacho = $_POST["despacho"];
$correo   = $_POST["Correo"];
$rol_texto = $_POST["rol"]; // Este es el nombre del rol (ej. "abogado")

// Obtener el ID del rol a partir del nombre
$rol_stmt = $conexion->prepare("SELECT id_rol FROM roles WHERE nombre = ?");
$rol_stmt->bind_param("s", $rol_texto);
$rol_stmt->execute();
$rol_result = $rol_stmt->get_result();

if ($rol_result->num_rows === 0) {
    echo "Error: Rol no válido.";
    exit;
}

$rol_row = $rol_result->fetch_assoc();
$id_rol = $rol_row['id_rol'];
$rol_stmt->close();

// Actualizar datos del usuario
$sql = "UPDATE usuarios SET Nombre=?, Apellido=?, despacho=?, Correo=?, id_rol=? WHERE id_usuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssii", $nombre, $apellido, $despacho, $correo, $id_rol, $id);

if ($stmt->execute()) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "Error al actualizar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
