<?php
ob_start();
include("conexion.php");


 
// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario y sanitizarlos
$correoelectronico = isset($_POST["email"]) ? $conexion->real_escape_string($_POST["email"]) : '';

// Consulta para verificar si el usuario ya existe
$consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoelectronico'");

if (mysqli_num_rows($consulta) == 0) {
    header("location:index.php?valor=2");
    exit; // Terminar la ejecución del script después de redirigir
} 

while($fila = mysqli_fetch_array($consulta)){
    $correoelectronico = $fila['correoelectronico'];
    $id = $fila['id'];

}

if ($correoelectronico==NULL){header("location:index.php?valor=2");} 
else {
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permited_chars), 0, 10);
    $enlace = "https://fansdejennirivera2007.000webhostapp.com/ForgotPassword.html?cod=$codigo";
    echo $enlace;
    $consulta = mysqli_query($conexion, "insert into solcon (iduser, codigo, email) VALUES ('$id', '$codigo', '$correoelectronico')");

    header("location:login.html?cod=$codigo");  
    
}

// Cerrar la conexión
$conexion->close();

ob_end_flush();











// $para      = '$cor';
    // $titulo    = 'Recuperar contraseña';
    // $mensaje   = 'Recuperar';
    // $cabeceras = 'From: empreverest@gmail.com' . "\r\n" .
    //     'Reply-To: webmaster@empresa.com' . "\r\n" .
    //     'X-Mailer: PHP/' . phpversion();
    
    // mail($para, $titulo, $mensaje, $cabeceras);


?>



