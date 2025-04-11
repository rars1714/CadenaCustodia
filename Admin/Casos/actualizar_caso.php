<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST["id_caso"];
$nombre = $_POST["nombre_caso"];
$fecha = $_POST["fecha_creacion"];
$usuario = $_POST["id_usuario"];
$descripcion = $_POST["descripcion"];
$estado = $_POST["estado"];

$sql = "UPDATE casos SET nombre_caso=?, fecha_creacion=?, id_usuario=?, descripcion=?, estado=? WHERE id_caso=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $fecha, $usuario, $descripcion, $estado, $id);

if ($stmt->execute()) {
    echo "Caso actualizado correctamente.";
} else {
    echo "Error al actualizar.";
}

$stmt->close();
$conexion->close();
?>
