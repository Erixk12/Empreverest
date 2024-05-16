<?php
include("conexion.php");


$correo = $_POST['correo'];


$consulta = mysqli_query($conexion, "SELECT id FROM Usuarios WHERE correoelectronico = '$correo'");
if ($fila = mysqli_fetch_assoc($consulta)) {
    $iduser = $fila['id'];

    
        // Actualizar los datos en la tabla personas
        $actualizar = mysqli_query($conexion, "UPDATE Usuarios SET estado = 0 WHERE id = '$iduser'");


        if ($actualizar) {
            // Los datos se actualizaron correctamente
            header("location:DashboardAdmin.php?disabled_user=successful");
        } else {
            // Hubo un error al actualizar los datos
            echo "Error al actualizar los datos: " . mysqli_error($conexion);
        }
    
} else {
    // No se encontró ningún usuario con el correo electrónico dado
    echo "No se encontró ningún usuario con el correo electrónico dado.";
}
?>