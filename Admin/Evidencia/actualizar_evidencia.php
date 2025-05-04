<?php

session_start(); // NECESARIO para acceder a $_SESSION

$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_POST["id"]; // id_evidencia
$id_caso = $_POST["id_caso"];
$id_usuario = $_POST["id_usuario"];
$tipo_evidencia = $_POST["tipo_evidencia"];
$descripcion = $_POST["descripcion"];

$sql = "UPDATE evidencias SET id_caso = ?, id_usuario = ?, tipo_evidencia = ?, descripcion = ? WHERE id_evidencia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissi", $id_caso, $id_usuario, $tipo_evidencia, $descripcion, $id);

if ($stmt->execute()) {
    $stmt->close();

    // Usuario que hace la modificación
    $usuario_modifica = $_SESSION['usuario_id'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO historial_accesos (id_usuario, id_evidencia, accion, direccion_ip)
            VALUES (?, ?, 'Modificación Evidencia', ?)";
    $stmt2 = $conexion->prepare($sql);
    $stmt2->bind_param("iis", $usuario_modifica, $id, $ip);
    $stmt2->execute();
    $stmt2->close();

    echo "Evidencia actualizada correctamente.";
} else {
    echo "Error al actualizar: " . $stmt->error;
}

$conexion->close();
?>
