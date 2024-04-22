<?php
// Establecer la conexión con la base de datos
$conexion = new mysqli("localhost", "root", "Mi_218643758", "Empreverest");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$correoelectronico = $_POST["correoelectronico"]; //Obtener el correo electrónico ingresado por el usuario
$contraseña = $_POST["contraseña"]; // Obtener la contraseña ingresada por el usuario

// Consulta para verificar si el usuario ya existe
$sql_check_user = "SELECT contraseña FROM Usuarios WHERE correoelectronico = '$correoelectronico'"; //Obtener la contraseña correspondiente con el correo electrónico ingresado por el usuario
$result = $conexion->query($sql_check_user); //Hacer una consulta y guardar el resultado en una variable 

if ($result->num_rows > 0) { //Si la consulta devuelve por lo menos un resultado 
    // El usuario está registrado, obtener la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc(); //Guardar el resultado de la consulta en una variable 
    $contraseña_hash = sha1($contraseña); // Encriptar la contraseña ingresada

    $contraseña_db = $row["contraseña"]; // Obtener la contraseña hasheada almacenada en la base de datos

    if ($contraseña_hash === $contraseña_db) { //Validar si la contraseña ingresada es igual a la contraseña de la base de datos (se valida ambas contraseñas encriptadas)     
        header("Location: Dashboard.html"); // Inicio de sesión exitoso, mandar a Dashboard
        exit(); //Detiene la ejecución del script
    } else { //Entonces las contraseñas no son iguales
        // Contraseña incorrecta
        header("Location: login.html?login=fail&error=incorrect_password"); //Devolver error
        exit(); //Detiene la ejecución del script
    }
} else { //Entonces la consulta no devolvio ningun resultado    
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}\.[a-zA-Z]{2,}$/"; //Expresion regular
    if (!preg_match($pattern, $correoelectronico)) { //Validar si el correo electronico no coincide con la expresion regular
        header("Location: login.html?login=fail&error=invalid_email"); //Devolver error
        exit(); //Detiene la ejecución del script
    }
    else { //Entonces el correo electrónico si coincide con la expresion regular
        header("Location: login.html?login=fail&error=not_registered"); // Devolver error, el correo no esta registrado 
        exit(); //Detiene la ejecución del script
    }
}

// Cerrar la conexión
$conexion->close();
?>
