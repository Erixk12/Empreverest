<?php
include("conexion.php");
session_start();

// Función para insertar un registro en la tabla de registros
function insertarRegistro($conexion, $idUsuario, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion) {
    // Obtener la fecha y hora actual
    date_default_timezone_set('America/Mexico_City'); 
    $fecha = date('Y-m-d H:i:s');
    
    // Insertar el registro en la tabla de registros
    $sql = "INSERT INTO registro (iduser, usuario, fecha, accion, ip, navegador, estado, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isssssss", $idUsuario, $correoelectronico, $fecha, $accion, $ip, $navegador, $estado, $descripcion);
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
        return false;
    }
}

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    // Obtiene los datos del formulario
    $correoElectronico = $_POST['email'];
    $correoElectronico = strtolower($correoElectronico);
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
        $sql = "INSERT INTO Usuarios (correoelectronico, contraseña, estado, fecha, codigo) VALUES ('$correoElectronico', '$contrasena', '$estado', now(), '$codigo')";
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $navegador = substr($navegador, 0, 50);
        $accion = 'registro de usuario';
        $estado = 'Exitoso';
        $descripcion = "Correo: " . $correoElectronico . " Contraseña: " .  $contrasena;
        // Ejecuta la consulta
        if (mysqli_query($conexion, $sql)) {
            if (insertarRegistro($conexion, $idUsuario, $correoElectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
                header("Location: Registrarse.html?cod=$codigo");
                exit(); 
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
                exit(); // Detiene la ejecución del script
            }   
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        }      

    }

    // Cierra la conexión
    mysqli_close($conexion);
}
?>
