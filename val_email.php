<?php
ob_start();
session_start(); //Iniciar una sesión
include("conexion.php");

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

// Recibir los datos del formulario y sanitizarlos
$correoelectronico = isset($_POST["email"]) ? $conexion->real_escape_string($_POST["email"]) : '';

if ($correoelectronico === '') { // Verificar si el correo electrónico está vacío
    // Solicitud fallida: correo electronico vacio
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $accion = 'Envío de petición cambio de contraseña';
    $estado = 'Fallido';
    $descripcion = 'Correo electrónico vacío';
    $idUsuarido = 0;

    // Insertar el registro en la tabla de registros
    if (insertarRegistro($conexion, $idUsuarido, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
        // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
        header("location: email.html?login=fail&error=empty_email");
        exit(); // Terminar la ejecución del script después de redirigirt
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        exit(); // Detiene la ejecución del script
    }
    
}

// Consulta para verificar si el usuario ya existe
$consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoelectronico'");

if (mysqli_num_rows($consulta) == 0) { // Validar si existe un correo en la base de datos 
    // Solicitud fallida: no existe correo en la base de datos
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $accion = 'Envío de petición cambio de contraseña';
    $estado = 'Fallido';
    $descripcion = 'No existe correo electrónico en la base de datos';
    $idUsuarido = 0;

    // Insertar el registro en la tabla de registros
    if (insertarRegistro($conexion, $idUsuarido, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
        // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
        header("location: email.html?login=fail&error=empty_email");
        exit(); // Terminar la ejecución del script después de redirigir
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        exit(); // Detiene la ejecución del script
    }


    header("location:email.html?login=fail&error=empty_email");
    exit(); // Terminar la ejecución del script después de redirigir
} 

while($fila = mysqli_fetch_array($consulta)){ // Buscar el ID y el Correo electronico de la base de datos
    $correoelectronico = $fila['correoelectronico'];
    $id = $fila['id'];
}

if ($correoelectronico == NULL){ // Validar si el correo electronico es diferente de null, es decir si existe en la base de datos
    // Solicitud fallida: correo electronico vacio
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $accion = 'Envío de petición cambio de contraseña';
    $estado = 'Fallido';
    $descripcion = 'No existe correo electrónico en la base de datos o vacío';
    $idUsuarido = 0;

    // Insertar el registro en la tabla de registros
    if (insertarRegistro($conexion, $idUsuarido, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
        // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
        header("location:email.html?login=fail&error=empty_email");
        exit(); // Terminar la ejecución del script después de redirigir
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        exit(); // Detiene la ejecución del script
    }
    
} else {
    $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = substr(str_shuffle($permited_chars), 0, 10);
    $_SESSION['codigo'] = $codigo; //Guardar codigo en una sesión

    // Insertar datos en la tabla cambiarContraseña
    mysqli_query($conexion, "INSERT INTO cambiarContraseña (iduser, codigo, email) VALUES ('$id', '$codigo', '$correoelectronico')");
    //Solicitud exitosa
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $accion = 'Envío de petición cambio de contraseña';
    $estado = 'Exitoso';
    $descripcion = '-';

    // Insertar el registro en la tabla de registros
    if (insertarRegistro($conexion, $id, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
        // Redirigir al usuario a alguna página de error o mostrar un mensaje adecuado
        header("location:passwords.html?mail=success&cod=$codigo"); //Mostrar error
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        exit(); // Detiene la ejecución del script
    }
    
}

ob_end_flush();
?>
