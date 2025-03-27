<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST["id"];
$nombre = $_POST["Nombre"];
$apellido = $_POST["Apellido"];
$despacho = $_POST["despacho"];
$correo = $_POST["Correo"];
$rol = $_POST["rol"];

$sql = "UPDATE usuarios SET Nombre=?, Apellido=?, despacho=?, Correo=?, rol=? WHERE id_usuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $apellido, $despacho, $correo, $rol, $id);

if ($stmt->execute()) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "Error al actualizar.";
}

$stmt->close();
$conexion->close();
?>
