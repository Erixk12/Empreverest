<?php
// Establecer la conexión con la base de datos
include("conexion.php");

// Recibir los datos del formulario
$correoelectronico = $_POST["correoelectronico"]; //Obtener el correo electrónico ingresado por el usuario
$contraseña = $_POST["contraseña"]; // Obtener la contraseña ingresada por el usuario

// Consulta para verificar si el usuario ya existe
$sql_check_user = "SELECT contraseña FROM Usuarios WHERE correoelectronico = '$correoelectronico'"; //Obtener la contraseña correspondiente con el correo electrónico ingresado por el usuario

//Sanitizar las entradas, evitar inyecciones SQL
$stmt = $conexion->prepare($sql_check_user);
$stmt->bind_param("s", $correoelectronico);
$stmt->execute();
$result = $stmt->get_result();

$result = $conexion->query($sql_check_user); //Hacer una consulta y guardar el resultado en una variable 

//Funcion para validar si la contraseña tiene caracteres invalidos 
function valCaracteresContraseña($password) { //Funcion para validar correctamente las contraseñas 
    $permited_chars = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789@#$._!'; //Caracteres permitidos para la contraseña
    $len = mb_strlen($password); //Obtener la longitud de caracteres 

        //Entonces contiene la cantidad adecuada de caracteres 
        for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
            $char = mb_substr($password, $i, 1, "UTF-8");
            if (!in_array($char, str_split($permited_chars))) { //Validar cada caracteres
                return false; //Si existe un caracterer invalido, la funcion devuelve false y termina
            } //Fin de if
        } //Fin de for 
        return true; //Si el for no devolvio un false, entonces todos los caracteres son validos 
} //Fin de la funcion 

if (!valCaracteresContraseña($contraseña)) {
    header("location: login.html?login=fail&error=bad_passwords"); //Devolver error
    exit();
}

if ($result->num_rows == 0) { //Validar si no existe un correo electronico en la base de datos 
    header("Location:login.html?login=fail&error=not_registered"); //Devolver error
    exit(); //Detiene la ejecución del script
} else if ($result->num_rows > 0) { //Si la consulta devuelve por lo menos un resultado 
    // El usuario está registrado, obtener la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc(); //Guardar el resultado de la consulta en una variable 
    $contraseña_hash = sha1($contraseña); // Encriptar la contraseña ingresada

    $contraseña_db = $row["contraseña"]; // Obtener la contraseña hasheada almacenada en la base de datos

    if ($contraseña_hash === $contraseña_db) { //Validar si la contraseña ingresada es igual a la contraseña de la base de datos (se valida ambas contraseñas encriptadas)     
        header("Location:Dashboard.html"); // Inicio de sesión exitoso, mandar a Dashboard
        exit(); //Detiene la ejecución del script
    } else { //Entonces las contraseñas no son iguales
        if(strlen($contraseña) < 8) { //Validar si la contraseña tiene por lo menos 8 caracteres
            header("Location:login.html?login=fail&error=short_password"); //Devolver error
            exit();
        } else { //Entonces hugo otr error de contraseña
        // Contraseña incorrecta
        header("Location:login.html?login=fail&error=incorrect_credentials"); //Devolver error
        exit(); //Detiene la ejecución del script
        }
    }

} else { //Entonces la consulta no devolvio ningun resultado    
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}\.[a-zA-Z]{2,}$/"; //Expresion regular
    if (!preg_match($pattern, $correoelectronico)) { //Validar si el correo electronico no coincide con la expresion regular
        header("Location:login.html?login=fail&error=invalid_email"); //Devolver error
        exit(); //Detiene la ejecución del script
    } else { //Entonces el correo electrónico si coincide con la expresion regular
        header("Location:login.html?login=fail&error=invalid_email"); // Devolver error, el correo no esta registrado 
        exit(); //Detiene la ejecución del script
    }
}

// Cerrar la conexión
$conexion->close();
?>
