<?php
ob_start();
include("conexion.php");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$codigo = isset($_POST["codigo"]) ? $conexion->real_escape_string($_POST["codigo"]) : ''; //Obtener el codigo ingresado por el usuario
$consulta = mysqli_query($conexion, "SELECT * FROM cambiarContraseña WHERE codigo = '$codigo'"); //Consulta de una tabla 

// Buscar el ID y el Correo electronico de la base de datos
while($fila = mysqli_fetch_array($consulta)){ //Encontrar variables de la tabla 
    $id = $fila['iduser']; //Obtener el ID del usuario del codigo ingresado 
    $codigoBD = $fila['codigo'];
}

// Verificar si las nuevas contraseñas no están vacías
if (!empty($_POST["cambiarContraseña"]) && !empty($_POST["confirmarContraseña"])) {
    $cambiarContraseña = $_POST["cambiarContraseña"];
    $confirmarContraseña = $_POST["confirmarContraseña"];

    // Cifrar las contraseñas utilizando SHA1
    $cambiarContraseñaHash = sha1($cambiarContraseña);
    $confirmarContraseñaHash = sha1($confirmarContraseña);

    // Consulta para obtener la contraseña actual almacenada en la base de datos
    $consultaContraseña = mysqli_query($conexion, "SELECT contraseña FROM Usuarios WHERE id = $id");
    $filaContraseña = mysqli_fetch_assoc($consultaContraseña);
    $contraseñaActual = $filaContraseña['contraseña']; //Obtener la contraseña actual (la contraseña ya esta cifrada en Hash1)

    if($codigo != $codigoBD) { // Verificar si el codigo ingresado es igual al de la base de datos
        header("location: login.html?login=success&cod=$codigo&login=fail&error=wrong_passwords");  //Mostrar error
    } // Verificar si la nueva contraseña coincide con la contraseña actual
    else if($confirmarContraseñaHash === $contraseñaActual && $cambiarContraseñaHash === $contraseñaActual) { // Validar si la nueva contraseña es igual a la contraseña anterior
        header("location: login.html?&login=fail&error=same_passwords&cod=$codigoBD"); 
    } 
    else if ($confirmarContraseñaHash === $cambiarContraseñaHash) { 
        // Actualizar la contraseña en la base de datos
        $query = "UPDATE Usuarios SET contraseña = '$cambiarContraseñaHash' WHERE id = '$id' ";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            // La consulta se ejecutó con éxito
            header("location: login.html?login=password_updated");
        } else {
            // Hubo un error al ejecutar la consulta, capturarlo y manejarlo adecuadamente
            $error = mysqli_error($conexion);
            echo "Error al actualizar la contraseña: " . $error;
        }
    }
    else {
        //header("Location: login.html?login=fail&error=wrong_passwords"); // Mostrar error
        header("location: login.html?&login=fail&error=same_passwords&cod=$codigoBD");   //Mostrar error
    }
}

ob_end_flush();
?>
