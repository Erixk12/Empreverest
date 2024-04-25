<?php
ob_start();
session_start();
include("conexion.php");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} 

//$codigo = $_GET['cod']; // Obtener el código de la URL
if (isset($_SESSION['codigo'])) { //Obtener el codigo de una sesión iniciada en el script "val_correoElectronico"
    $codigo = $_SESSION['codigo']; // Obtener el código de la sesión
    //header("location: login.html?codigo=$codigo"); //Mostrar error

    $consulta = mysqli_query($conexion, "SELECT * FROM cambiarContraseña WHERE codigo = '$codigo'"); //Consulta de una tabla 

    // Buscar el ID y el Correo electronico de la base de datos
    while($fila = mysqli_fetch_array($consulta)){ //Encontrar variables de la tabla 
        $id = $fila['iduser']; //Obtener el ID del usuario del codigo ingresado 
        $codigoBD = $fila['codigo'];
    }

    if (!empty($_POST["cambiarContraseña"]) && !empty($_POST["confirmarContraseña"])) { // Verificar si las nuevas contraseñas no están vacías
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
            exit();
        } 
        else if ($confirmarContraseñaHash === $cambiarContraseñaHash) { 
            // Actualizar la contraseña en la base de datos
            $query = "UPDATE Usuarios SET contraseña = '$cambiarContraseñaHash' WHERE id = '$id' ";
            $resultado = mysqli_query($conexion, $query);

            if ($resultado) {
                // La consulta se ejecutó con éxito
                header("location: login.html?login=password_updated");
                exit();
            } else {
                // Hubo un error al ejecutar la consulta, capturarlo y manejarlo adecuadamente
                $error = mysqli_error($conexion);
                echo "Error al actualizar la contraseña: " . $error;
                exit();
            }
        }
        else {
            //header("Location: login.html?login=fail&error=wrong_passwords"); // Mostrar error
            header("location: login.html?&login=fail&error=same_passwords&cod=$codigoBD"); //Mostrar error
            exit();
        }
    } else {
        header("location: login.html?login=success&$codigo"); //Mostrar error
    }
} else { //Entonces el codigo no esta en el URL
    header("location: login.html?ayudaerror"); //Mostrar error
}

ob_end_flush();
?>
