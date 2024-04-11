<?php
include("con.php")
// Establecer la conexión con la base de datos
$conexion = new mysqli("localhost", "root", "Mi_218643758", "Empreverest");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$correoelectronico = $_POST["email"];

// Consulta para verificar si el usuario ya existe
$sql_check_user = "SELECT contraseña FROM Usuarios WHERE correoelectronico = '$correoelectronico'";
$result = $conexion->query($sql_check_user);

if ($result->num_rows > 0) {
    
} else {
    // El usuario no está registrado
    header("Location: login.html?login=fail&error=not_registered");
    exit();
}

// Cerrar la conexión
$conexion->close();
?>