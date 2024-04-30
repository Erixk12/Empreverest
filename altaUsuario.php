<?php
session_start();

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear la conexión
    $conexion = mysqli_connect("localhost", "root", "12345678", "empreverest");

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    } else {
        //echo "Conexión exitosa";
    }

    // Obtiene los datos del formulario
    $correoElectronico = $_POST['email'];
    $contrasena = sha1($_POST['password']);
    $estado = true; // Hasta que el usuario no complete los demas datos no se pondra true
    $fecha = date("Y-m-d H:i:s"); // Obtiene la fecha y hora actual
    
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permited_chars), 0, 10);
    $_SESSION['codigo'] = $codigo;

    // Prepara la consulta SQL para insertar los datos en la tabla Usuarios
    $sql = "INSERT INTO Usuarios (correoelectronico, contraseña, estado, fecha, codigo) VALUES ('$correoElectronico', '$contrasena', '$estado', '$fecha', '$codigo')";

    // Ejecuta la consulta
    if (mysqli_query($conexion, $sql)) {
        header("Location: Registrarse.html?cod=$codigo");
        exit(); // Asegura que el script se detenga después de la redirección
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }

    // Cierra la conexión
    mysqli_close($conexion);
}
?>
