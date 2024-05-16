<?php
include("conexion.php");


$fotoperfil = $_POST['fotoPerfil'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$codigoAlumno = $_POST['codigoAlumno'];
$tipoCuenta = $_POST['tipoCuenta'];
$centro = $_POST['centro'];
$correoElectronico = $_POST['correoElectronico'];


$consulta = mysqli_query($conexion, "SELECT id FROM Usuarios WHERE correoelectronico = '$correoElectronico'");
if ($fila = mysqli_fetch_assoc($consulta)) {
    $iduser = $fila['id'];

    // Obtener el idpeople asociado al iduser
    $getidpeople = mysqli_query($conexion, "SELECT idpeople FROM personas WHERE iduser = '$iduser'");
    if ($fila = mysqli_fetch_assoc($getidpeople)) {
        $idpeople = $fila['idpeople'];

        // Actualizar los datos en la tabla personas
        $actualizar = mysqli_query($conexion, "UPDATE personas SET nombre = '$nombre', apellido = '$apellidos', genero = '$genero', codigo_alumno = '$codigoAlumno', tipocuenta = '$tipoCuenta', centro = '$centro' WHERE idpeople = '$idpeople'");

        if ($actualizar) {
            // Los datos se actualizaron correctamente
            header("location:DashboardAdmin.php?update_people=successful");
        } else {
            // Hubo un error al actualizar los datos
            echo "Error al actualizar los datos: " . mysqli_error($conexion);
        }
    } else {
        // No se encontró ningún registro en personas con el iduser correspondiente
        echo "No se encontró ningún registro en personas con el iduser correspondiente.";
    }
} else {
    // No se encontró ningún usuario con el correo electrónico dado
    echo "No se encontró ningún usuario con el correo electrónico dado.";
}
?>