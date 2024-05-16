<?php
include("conexion.php");
session_start();

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


    // Recupera los datos del formulario
    $correoElectronico = $_POST['correoElectronico'];
    $contrasena = $_POST['password'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $genero = $_POST['genero'];
    $centroUniversitario = $_POST['centro'];
    $codigoAlumno = $_POST['codigoAlumno'];
    $tipoCuenta = $_POST['tipoCuenta'];
    
    // Verificar si el correo electrónico contiene ".udg.mx" como dominio
    if (!preg_match("/@([a-zA-Z0-9-]+)\.udg\.mx$/", $correoElectronico)) {
        header("Location: DashboardAdmin.php?registration=fail&error=invalid_email&correoElectronico=$correoElectronico");

        exit(); // Terminar la ejecución del script después de redirigir
    }

    // Consulta para verificar si el usuario ya existe
    $consulta = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE correoelectronico = '$correoElectronico'");

    if (mysqli_num_rows($consulta) > 0) {
        // El correo electrónico ya existe en la base de datos
        header("location:DashboardAdmin.php?registration=fail&error=existing_email");
        exit(); // Terminar la ejecución del script después de redirigir
    } elseif (strlen($contrasena) < 8) {
        // La contraseña es insegura
        header("location:DashboardAdmin.php?registration=fail&error=insecure_password");
        exit(); // Terminar la ejecución del script después de redirigir
    } else {
        $contrasena = sha1($_POST['password']);
        $permited_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = substr(str_shuffle($permited_chars), 0, 10);
        $_SESSION['codigo'] = $codigo;
        $estado = 1;
    
        // Prepara la consulta SQL para insertar los datos en la tabla Usuarios
        $sql = "INSERT INTO Usuarios (correoelectronico, contraseña, estado, fecha, codigo) VALUES ('$correoElectronico', '$contrasena', '$estado', now(), '$codigo')";
        
        // Ejecuta la consulta
        if (mysqli_query($conexion, $sql)) {
            header("Location: Registrarse.html?cod=$codigo");             
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        }      

    
    


    // Obtiene el código almacenado en la sesión
    $codigo = $_SESSION['codigo'];

    // Evitar inyección SQL utilizando consultas preparadas
    $query = "SELECT id FROM Usuarios WHERE codigo = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $codigo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
   

    // Consulta para verificar si el usuario ya existe
    $consulta = mysqli_query($conexion, "SELECT * FROM personas WHERE codigo_alumno = '$codigoAlumno'");

    if (!preg_match("/^[\p{L} \.']+$/u", $nombre) || !preg_match("/^[\p{L} \.']+$/u", $apellidos)) {
        // Verificar si el nombre y el apellido contienen solo letras, espacios, puntos y comillas, incluyendo la "ñ" y las vocales con acentos
        header("location:DashboardAdmin.php?registration=fail&error=invalid_name_or_lastname&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }
    elseif (strlen($nombre) < 3) {
        // Verificar que el nombre tenga al menos 3 caracteres
        header("location:DashboardAdmin.php?registration=fail&error=short_name&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }
    elseif (strlen($apellidos) < 3) {
        // Verificar que el apellido tenga al menos 3 caracteres
        header("location:DashboardAdmin.php?registration=fail&error=error=short_name&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }
    elseif (mysqli_num_rows($consulta) > 0) {
        // El codigo de alumno ya existe en la base de datos
        header("location:DashboardAdmin.php?registration=fail&error=existing_studentcode&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }        
    elseif (!preg_match("/^\d{9}$/", $codigoAlumno)) {
        // Verificar que el código tenga exactamente 9 caracteres y que sean todos números
        header("location:DashboardAdmin.php?registration=fail&error=invalid_student_code&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }    
    else{
        // Verificar si se encontró un usuario con el código dado
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $iduser);
            mysqli_stmt_fetch($stmt);

            // Consulta SQL para obtener el correo del usuario
            $query_correo = "SELECT correoelectronico FROM Usuarios WHERE codigo = ?";
            $stmt_correo = mysqli_prepare($conexion, $query_correo);
            mysqli_stmt_bind_param($stmt_correo, "s", $codigo);
            mysqli_stmt_execute($stmt_correo);
            mysqli_stmt_store_result($stmt_correo);
            mysqli_stmt_bind_result($stmt_correo, $correo); 
            mysqli_stmt_fetch($stmt_correo); 

            // Consulta SQL para insertar en la tabla personas
            $sql = "INSERT INTO personas (iduser, nombre, apellido, genero, tipocuenta, codigo_alumno, centro) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt_insert, "issssss", $iduser, $nombre, $apellidos, $genero, $tipoCuenta, $codigoAlumno, $centroUniversitario);

            $ip = $_SERVER['REMOTE_ADDR'];
            $navegador = $_SERVER['HTTP_USER_AGENT'];
            $navegador = substr($navegador, 0, 50);
            $accion = 'Registro de datos personales';
            $estado = 'Exitoso';
            $descripcion = "Correo: " . $correoElectronico . " Contraseña: " .  $contrasena . " Nombre: " . $nombre . " Apelllidos: " .  $apellidos . " Género: " . $genero . " Centro: " . $centroUniversitario . " Codigo: ".  $codigoAlumno ;

            if (mysqli_stmt_execute($stmt_insert)) {                
                if (insertarRegistro($conexion, $iduser, $correo, $accion, $ip, $navegador, $estado, $descripcion)) {                   
                    header("location:DashboardAdmin.php?registration=successful");
                    exit(); // Salir del script después de la redirección
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
                    exit(); // Detener la ejecución del script
                }   
            } else {
                echo "Error al insertar datos: " . mysqli_error($conexion);
                exit(); // Detener la ejecución del script
            }
        } else {
            echo "No se encontró ningún usuario con el código proporcionado.";
            echo $codigo;
        }


    }

    // Cierra la conexión
    mysqli_close($conexion);
}
?>
