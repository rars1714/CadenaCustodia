<?php
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST["id"]; // id_evidencia
$id_caso = $_POST["id_caso"];
$id_usuario = $_POST["id_usuario"];
$tipo_evidencia = $_POST["tipo_evidencia"];
$descripcion = $_POST["descripcion"]; // Asegúrate de que el name coincide
$nombre_archivo = $_POST["nombre_archivo"];

$sql = "UPDATE evidencias SET id_caso = ?, id_usuario = ?, tipo_evidencia = ?, descripcion = ?, nombre_archivo = ? WHERE id_evidencia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iisssi", $id_caso, $id_usuario, $tipo_evidencia, $descripcion, $nombre_archivo, $id);

if ($stmt->execute()) {
    echo "Evidencia actualizada correctamente.";
} else {
    echo "Error al actualizar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
