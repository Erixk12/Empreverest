<?php
// Establecer la conexión con la base de datos
include("conexion.php");

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


// Recibir los datos del formulario
$correoelectronico = $_POST["correoelectronico"]; // Obtener el correo electrónico ingresado por el usuario
$contraseña = $_POST["contraseña"]; // Obtener la contraseña ingresada por el usuario

// Consulta para verificar si el usuario ya existe y obtener su estado
$sql_check_user = "SELECT id, contraseña, estado FROM Usuarios WHERE correoelectronico = ?"; // Usar parámetros para evitar inyección SQL

// Sanitizar las entradas, evitar inyecciones SQL
$stmt = $conexion->prepare($sql_check_user);
$stmt->bind_param("s", $correoelectronico);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) { // Validar si no existe un correo electrónico en la base de datos
    
    $idUsuarido = 0;

    // Datos para la tabla de registro
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $navegador = substr($navegador, 0, 50);
    $accion = 'Inicio de sesión';
    $estado = 'Fallido';
    $descripcion = 'No existe cuenta asociada a este correo electrónico';

    // Insertar el registro en la tabla de registros
    if (insertarRegistro($conexion, $idUsuarido, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
        header("Location: login.html?login=fail&error=not_registered");
        exit(); // Detiene la ejecución del script
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        exit(); // Detiene la ejecución del script
    }

} elseif ($result->num_rows > 0) { // Si la consulta devuelve por lo menos un resultado



    // El usuario está registrado, obtener la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc(); // Guardar el resultado de la consulta en una variable
    $idUsuario = $row['id'];
    $estado = $row['estado'];

    if($estado == 0){
        header("Location: login.html?login=fail&error=disabled_user"); // Devolver error
        exit();
    }
    
    

    // Validar la contraseña
    if (strlen($contraseña) < 8) { // Validar si la contraseña tiene por lo menos 8 caracteres
        // Datos para la tabla de registro
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $navegador = substr($navegador, 0, 50);
        $accion = 'Inicio de sesión';
        $estado = 'Fallido';
        $descripcion = 'Contraseña menor a 8 caracteres';

        // Insertar el registro en la tabla de registros con el ID de usuario obtenido de la consulta
        if (insertarRegistro($conexion, $idUsuario, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
            header("Location: login.html?login=fail&error=short_password"); // Devolver error
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
            exit(); // Detiene la ejecución del script
        }  
    }

    // Encriptar la contraseña ingresada
    $contraseña_hash = sha1($contraseña);

    // Comparar la contraseña ingresada con la contraseña almacenada en la base de datos
    if ($contraseña_hash === $row["contraseña"]) { // Validar si la contraseña ingresada es igual a la contraseña de la base de datos (se valida ambas contraseñas encriptadas)
        // Datos para la tabla de registro
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $navegador = substr($navegador, 0, 50);
        $accion = 'Inicio de sesión';
        $estado = 'Exitoso';
        $descripcion = '-';

        // Insertar el registro en la tabla de registros
        if (insertarRegistro($conexion, $idUsuario, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
            $sql_checkAccount = "SELECT tipocuenta FROM personas WHERE iduser = ?";
            // Sanitizar las entradas, evitar inyecciones SQL
            $stmt_account = $conexion->prepare($sql_checkAccount);
            $stmt_account->bind_param("i", $idUsuario);
            $stmt_account->execute();
            $result_account = $stmt_account->get_result();
    
            if ($result_account->num_rows > 0) {
                // Obtener el tipo de cuenta del usuario
                $row_account = $result_account->fetch_assoc();
                $tipo_cuenta = $row_account['tipocuenta'];
    
                if($tipo_cuenta == "Administrador"){
                    header("Location: DashboardAdm.html"); // Inicio de sesión exitoso, mandar a Dashboard
                    exit();
                }else{
                    header("Location: Dashboard.html"); // Inicio de sesión exitoso, mandar a Dashboard
                    exit();
                }            
            }            
        } else {
            exit(); // Detiene la ejecución del script
        }

    } else { // Las contraseñas no coinciden
        // Datos para la tabla de registro
        $ip = $_SERVER['REMOTE_ADDR'];
        $navegador = $_SERVER['HTTP_USER_AGENT'];
        $navegador = substr($navegador, 0, 50);
        $accion = 'Inicio de sesión';
        $estado = 'Fallido';
        $descripcion = 'Contraseña incorrecta';

        // Insertar el registro en la tabla de registros con el ID de usuario obtenido de la consulta
        if (insertarRegistro($conexion, $idUsuario, $correoelectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
            header("Location: login.html?login=fail&error=incorrect_credentials"); // Devolver error
            exit(); // Detiene la ejecución del script
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
            exit(); // Detiene la ejecución del script
        }        
    }
}

// Cerrar la conexión
$conexion->close();
?>
