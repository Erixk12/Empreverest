val_passwords.php
<?php
ob_start();
session_start();
include("conexion.php");
//$codigo = $_GET['cod']; // Obtener el código de la URL

function insertarRegistro($conexion, $idUsuario, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion) {
    // Obtener la fecha y hora actual
    date_default_timezone_set('America/Mexico_City'); 
    $fecha = date('Y-m-d H:i:s');
    
    // Insertar el registro en la tabla de registros
    $sql = "INSERT INTO registro (iduser, usuario, fecha, accion, ip, navegador, estado, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isssssss", $idUsuario, $correoelectronico, $fecha, $accion, $ip, $navegador, $estado, $descripcion);
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
        return false;
    }
}


if (isset($_SESSION['codigo'])) { //Obtener el codigo de una sesión iniciada en el script "val_correoElectronico"
    $codigo = $_SESSION['codigo']; // Obtener el código de la sesión

    $consulta = mysqli_query($conexion, "SELECT * FROM cambiarContraseña WHERE codigo = '$codigo'"); //Consulta de una tabla 

    // Buscar el ID y el Correo electronico de la base de datos
    while($fila = mysqli_fetch_array($consulta)){ //Encontrar variables de la tabla 
        $id = $fila['iduser']; //Obtener el ID del usuario del codigo ingresado 
        $codigoBD = $fila['codigo'];
        $correoelectronico = $fila['email'];
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
            header("location: login.html?&login=fail&error=wrong_cod&cod=$codigo");  //Mostrar error
        } // Verificar si la nueva contraseña coincide con la contraseña actual
        else if($confirmarContraseñaHash == $contraseñaActual && $cambiarContraseñaHash == $contraseñaActual) { // Validar si la nueva contraseña es igual a la contraseña anterior
            header("location: passwords.html?&login=fail&error=repeated_passwords&cod=$codigoBD"); 
            exit();
        } 
        else if ($confirmarContraseñaHash === $cambiarContraseñaHash) { //Validar si las contraseñas son válidas 

            function valCantidadCaracteresMin($password) { //Validar si la contraseña contiene caracteres suficientes 
                $len = mb_strlen($password); //Obtener la longitud de caracteres 
                if ($len < 8) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
                    return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
                } else { //Entonces contiene caracteres suficientes 
                    return true;
                } //Fin de else 
            } //Fin de la funcion 

            function valCantidadCaracteresMax($password) { //Validar si la contraseña se excede de caracteres
                $len = mb_strlen($password); //Obtener la longitud de caracteres 
                if ($len > 20) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
                    return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
                } else { //Entonces contiene caracteres suficientes 
                    return true;
                } //Fin de else 
            } //Fin de la funcion 

            function valCaracteresContraseña($password) { //Funcion para validar correctamente las contraseñas 
                $permited_chars = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789@#$._!'; //Caracteres permitidos para la contraseña
                $len = mb_strlen($password); //Obtener la longitud de caracteres 
            
                if ($len < 8 || $len > 20) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
                    return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
                } else { //Entonces contiene la cantidad adecuada de caracteres 
                    for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
                        $char = mb_substr($password, $i, 1, "UTF-8");
                        if (!in_array($char, str_split($permited_chars))) { //Validar cada caracteres
                            return false; //Si existe un caracterer invalido, la funcion devuelve false y termina
                        } //Fin de if
                    } //Fin de for 
                    return true; //Si el for no devolvio un false, entonces todos los caracteres son validos 
                } //Fin de else 
            } //Fin de la funcion 

            //Documentacion | Validacion de las contraseñas 
            
            //1. Validacion de ambas contraseñas ingresadas
            //Hasta este punto, ya se valido si las dos contraseñas son iguales, por lo que es posible, obtener
            //cualquiera de las contraseñas para realizar nuestras validacaiones. 
            //Es importante tomar las contraseñas sin el cifrado sha1, puesto que la contraseña se valida
            //sin este cifrado, se validan los caracteres ingresados por el usuario

            //2. Validar la cantidad de caracteres ingresados en la contraseña
            //Unicamente es necesario validar una de las contraseñas, la funcion devulve False si la contraseña contiene
            //menos de 8 caracteres o mas de 20. Estos valores pueden ser modificados

            //3. Validar los caracteres de la contraseña
            //Nuevamente se valida una de las contraseñas para validar que caracteres ingresó el usuario, la funcion
            //devuleve un false si exsite un caracter no valido
            //Caracteres validos:
                // - Letras minusculas y mayusculas (se incluye la ñ)  
                // - Caracteres especiales permitidos: @#$._!
            //Estos valores pueden ser modificados

            //4. Cambiar la contraseña
            //Si ambas validaciones pasan, entonces se puede modificar la contraseña, caso contario, la plaforma
            //devulve toasts indicando cada uno de los errores

            if(!valCantidadCaracteresMin($cambiarContraseña)) {//Validar si la nueva contraseña contiene el minimo de caracteres
                header("location: passwords.html?login=fail&error=bad_cantidadCharsMin"); //Devolver error
                exit();
            } else if(!valCantidadCaracteresMax($cambiarContraseña)) {//Validar si la nueva contraseña no excede de un limite de caracteres 
                header("location: passwords.html?login=fail&error=bad_cantidadCharsMax"); //Devolver error
            } else if(!valCaracteresContraseña($cambiarContraseña)) { //Validar que las contraseñas contengan caracteres que no estan permitidos
                header("location: passwords.html?login=fail&error=bad_passwords"); //Devolver error
            } else { //Entonces los caracteres ingresados si son validos 
                // Actualizar la contraseña en la base de datos
                $query = "UPDATE Usuarios SET contraseña = '$cambiarContraseñaHash' WHERE id = '$id' ";
                $resultado = mysqli_query($conexion, $query);

                if ($resultado) { //La contraseña se actualizo con exito
                    // La consulta se ejecutó con éxito
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $navegador = $_SERVER['HTTP_USER_AGENT'];
                    $navegador = substr($navegador, 0, 50);
                    $accion = 'Cambio de contraseña';
                    $estado = 'Exitoso';
                    $descripcion = '-';

                    // Insertar el registro en la tabla de registros
                    if (insertarRegistro($conexion, $id, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
                        // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
                        header("location: login.html?login=password_updated");
                        exit();
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
                        exit(); // Detiene la ejecución del script
                    }                    

                } else { //Surgio un problema para actualizar la base de datos 
                    // Hubo un error al ejecutar la consulta, capturarlo y manejarlo adecuadamente
                    $error = mysqli_error($conexion);
                    echo "Error al actualizar la contraseña: " . $error;
                    exit();
                }
            }      
        } else { //Entonces la nueva contraseña ingresada no son iguales 
            header("location: passwords.html?&login=fail&error=wrong_passwords&cod=$codigoBD"); //Mostrar error
        }
    } else {
        header("location: passwords.html?&login=fail&error=wrong_passwords&cod=$codigoBD");
    }
} else { //Entonces el codigo no esta en el URL    
    header("location: login.html?login=fail&cod=not_found"); //Mostrar error    
}   

ob_end_flush();
?>
