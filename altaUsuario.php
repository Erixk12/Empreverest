<?php
session_start();

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear la conexión
    $conexion = mysqli_connect("localhost", "root", "chivas123", "empreverest");

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    } else {
        //echo "Conexión exitosa";
    }

    // Obtiene los datos del formulario
    $correoElectronico = $_POST['email'];
    $contrasena = $_POST['password'];
    $estado = true; // Hasta que el usuario no complete los demas datos no se pondra true
    $fecha = date("Y-m-d H:i:s"); // Obtiene la fecha y hora actual
    
    // Verificar si el correo electrónico contiene ".udg.mx" como dominio
    if (!preg_match("/@([a-zA-Z0-9-]+)\.udg\.mx$/", $correoElectronico)) {
        header("Location: Registrar_Usuario.html?registration=fail&error=invalid_email");
        exit(); // Terminar la ejecución del script después de redirigir
    }

    // Consulta para verificar si el usuario ya existe
    $consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoElectronico'");

    if (mysqli_num_rows($consulta) > 0) {
        // El correo electrónico ya existe en la base de datos
        header("location:Registrar_Usuario.html?registration=fail&error=existing_email");
        exit(); // Terminar la ejecución del script después de redirigir
    } elseif (strlen($contrasena) < 8) {
        // La contraseña es insegura
        header("location:Registrar_Usuario.html?registration=fail&error=insecure_password");
        exit(); // Terminar la ejecución del script después de redirigir
    } else {
        $contrasena = sha1($_POST['password']);
        $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = substr(str_shuffle($permited_chars), 0, 10);
        $_SESSION['codigo'] = $codigo;
    
        // Prepara la consulta SQL para insertar los datos en la tabla Usuarios
        $sql = "INSERT INTO Usuarios (correoelectronico, contraseña, estado, fecha, codigo) VALUES ('$correoElectronico', '$contrasena', '$estado', '$fecha', '$codigo')";
    
        // Ejecuta la consulta
        if (mysqli_query($conexion, $sql)) {
            header("Location: Registrarse.html?cod=$codigo");
            exit(); 
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        }

    }

    // Cierra la conexión
    mysqli_close($conexion);
}
?>
