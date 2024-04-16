<?php
include("conexion.php");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$correoelectronico = $_POST["email"];

// Consulta para verificar si el usuario ya existe
$consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoelectronico'");

while($fila = mysqli_fetch_array($consulta)){
    $correoelectronico = $fila['correoelectronico'];
    $id = $fila['id'];

}

if ($correoelectronico==NULL){header("location:index.php?valor=2");} 
else {
    $permited_chars
}

// Cerrar la conexión
$conexion->close();
?>