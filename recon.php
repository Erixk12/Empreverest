<?php
ob_start();
include("conexion.php");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario y sanitizarlos
$correoelectronico = isset($_POST["email"]) ? $conexion->real_escape_string($_POST["email"]) : '';

// Verificar si el correo electrónico está vacío
if ($correoelectronico === '') {
    // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
    header("location: login.html?login=fail&error=empty_email");
    exit; // Terminar la ejecución del script después de redirigir
}

// Consulta para verificar si el usuario ya existe
$consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoelectronico'");

// Validar si existe un correo en la base de datos 
if (mysqli_num_rows($consulta) == 0) {
    header("location:login.html?login=fail&error=empty_email");
    exit; // Terminar la ejecución del script después de redirigir
} 

// Buscar el ID y el Correo electronico de la base de datos
while($fila = mysqli_fetch_array($consulta)){
    $correoelectronico = $fila['correoelectronico'];
    $id = $fila['id'];
}

// Validar si el correo electronico es diferente de null, es decir si existe en la base de datos
if ($correoelectronico == NULL){
    header("location:login.html?login=fail&error=empty_email");
    exit; // Terminar la ejecución del script después de redirigir
} else {
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permited_chars), 0, 10);

    // Insertar datos en la tabla cambiarContraseña
    mysqli_query($conexion, "INSERT INTO cambiarContraseña (iduser, codigo, email) VALUES ('$id', '$codigo', '$correoelectronico')");
    // Redirigir a donde necesites después de guardar el código en la base de datos
    header("location:login.html?cod=$codigo");
}

ob_end_flush();
?>
