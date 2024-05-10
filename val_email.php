<?php
ob_start();
session_start(); //Iniciar una sesión
include("conexion.php");

// Recibir los datos del formulario y sanitizarlos
$correoelectronico = isset($_POST["email"]) ? $conexion->real_escape_string($_POST["email"]) : '';

if ($correoelectronico === '') { // Verificar si el correo electrónico está vacío
    // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
    header("location: email.html?login=fail&error=empty_email");
    exit(); // Terminar la ejecución del script después de redirigir
}

// Consulta para verificar si el usuario ya existe
$consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoelectronico'");

if (mysqli_num_rows($consulta) == 0) { // Validar si existe un correo en la base de datos 
    header("location:email.html?login=fail&error=empty_email");
    exit(); // Terminar la ejecución del script después de redirigir
} 

while($fila = mysqli_fetch_array($consulta)){ // Buscar el ID y el Correo electronico de la base de datos
    $correoelectronico = $fila['correoelectronico'];
    $id = $fila['id'];
}

if ($correoelectronico == NULL){ // Validar si el correo electronico es diferente de null, es decir si existe en la base de datos
    header("location:email.html?login=fail&error=empty_email");
    exit(); // Terminar la ejecución del script después de redirigir
} else {
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permited_chars), 0, 10);
    $_SESSION['codigo'] = $codigo; //Guardar codigo en una sesión

    // Insertar datos en la tabla cambiarContraseña
    mysqli_query($conexion, "INSERT INTO cambiarContraseña (iduser, codigo, email) VALUES ('$id', '$codigo', '$correoelectronico')");
    // Redirigir a donde necesites después de guardar el código en la base de datos
    header("location:passwords.html?mail=success&cod=$codigo"); //Mostrar error
    exit();
}

ob_end_flush();
?>
