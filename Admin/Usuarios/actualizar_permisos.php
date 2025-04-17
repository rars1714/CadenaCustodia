<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new mysqli("localhost", "root", "", "cadena_custodia");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $id_rol = $_POST['id_rol'];
    $permisos = $_POST['permisos'];

    foreach ($permisos as $accion => $valor) {
        $valor_bool = $valor === '1' ? 1 : 0;

        $stmt = $conexion->prepare("SELECT id FROM permisos_roles WHERE id_rol = ? AND accion = ?");
        $stmt->bind_param("is", $id_rol, $accion);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $update = $conexion->prepare("UPDATE permisos_roles SET valor = ? WHERE id_rol = ? AND accion = ?");
            $update->bind_param("iis", $valor_bool, $id_rol, $accion);
            $update->execute();
        } else {
            $insert = $conexion->prepare("INSERT INTO permisos_roles (id_rol, accion, valor) VALUES (?, ?, ?)");
            $insert->bind_param("isi", $id_rol, $accion, $valor_bool);
            $insert->execute();
        }
    }

    echo "Permisos actualizados correctamente.";
}
?>
