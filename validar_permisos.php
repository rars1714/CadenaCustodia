<?php
function tiene_permiso($conexion, $id_rol, $accion) {
    $sql = "SELECT valor FROM permisos_roles WHERE id_rol = ? AND accion = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $id_rol, $accion);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $fila = $resultado->fetch_assoc()) {
        return $fila['valor'] == 1;
    }
    return false;
}

?>
