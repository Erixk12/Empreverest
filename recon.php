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
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numale = substr(str_shuffle($permited_chars), 0, 10);
    $enlace = "https://fansdejennirivera2007.000webhostapp.com/ForgotPassword.html?cod='$codigo'";
    echo $enlace;
    $consulta = mysqli_query($conexion, "insert into solcon (idu, codigo)");

    <?php
    $para      = '$cor';
    $titulo    = 'Recuperar contraseña';
    $mensaje   = 'Recuperar';
    $cabeceras = 'From: chido@empresa.com' . "\r\n" .
        'Reply-To: webmaster@empresa.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    
    mail($para, $titulo, $mensaje, $cabeceras);
    ?>
}

// Cerrar la conexión
$conexion->close();
?>