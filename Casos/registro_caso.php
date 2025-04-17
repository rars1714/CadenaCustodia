<?php
// registro_caso.php

// Configuración de la conexión a la base de datos
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "cadena_custodia";

// Conexión a la base de datos
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que se envíe el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se ignoran 'id_caso' y 'fecha_inicio' pues la base de datos asigna automáticamente estos valores.
    $nombre_caso = trim($_POST['nombre_caso'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = strtolower(trim($_POST['estado'] ?? ''));
    $id_usuario = trim($_POST['usuario'] ?? '');

    // Validar que los campos requeridos estén completos
    if (empty($nombre_caso) || empty($estado) || empty($id_usuario)) {
        echo "Los campos Nombre del Caso, Estado y Usuario son requeridos.";
        exit;
    }

    // Validar que el estado esté entre los permitidos
    $estados_permitidos = ['abierto', 'en proceso', 'cerrado'];
    if (!in_array($estado, $estados_permitidos)) {
        echo "El estado no es válido.";
        exit;
    }

    // Preparar la sentencia SQL para insertar el caso en la base de datos
    $stmt = $conn->prepare("INSERT INTO casos (nombre_caso, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error en la preparación: " . $conn->error;
        exit;
    }

    // Se asume que id_usuario es un número entero
    $stmt->bind_param("sssi", $nombre_caso, $descripcion, $estado, $id_usuario);

    // Ejecutar la sentencia y verificar el resultado
    if ($stmt->execute()) {
        echo "Caso registrado exitosamente.";
    } else {
        echo "Error en el registro del caso: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Acceso no autorizado.";
}

$conn->close();
?>
