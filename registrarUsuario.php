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
        echo "Conexión exitosa";
    }

    // Recupera los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $genero = $_POST['genero'];
    $centroUniversitario = $_POST['centroUniversitario'];
    $codigoAlumno = $_POST['codigoAlumno'];
    $tipoCuenta = $_POST['tipoCuenta'];

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

    if (!preg_match("/^[a-zA-Z\s]+$/", $nombre) || !preg_match("/^[a-zA-Z\s]+$/", $apellidos)) {
        // Verificar si el nombre y el apellido contienen solo letras y espacios
        header("location:Registrarse.html?registration=fail&error=invalid_name_or_lastname&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }   
    elseif (strlen($nombre) < 3) {
        // Verificar que el nombre tenga al menos 3 caracteres
        header("location:Registrarse.html?registration=fail&error=short_name&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }
    elseif (strlen($apellidos) < 3) {
        // Verificar que el apellido tenga al menos 3 caracteres
        header("location:Registrarse.html?registration=fail&error=error=short_name&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }
    elseif (mysqli_num_rows($consulta) > 0) {
        // El codigo de alumno ya existe en la base de datos
        header("location:Registrarse.html?registration=fail&error=existing_studentcode&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }        
    elseif (!preg_match("/^\d{9}$/", $codigoAlumno)) {
        // Verificar que el código tenga exactamente 9 caracteres y que sean todos números
        header("location:Registrarse.html?registration=fail&error=invalid_student_code&cod=$codigo");
        exit(); // Terminar la ejecución del script después de redirigir
    }    
    else{
        // Verificar si se encontró un usuario con el código dado
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $iduser);
            mysqli_stmt_fetch($stmt);
    
            // Consulta SQL para insertar en la tabla personas
            $sql = "INSERT INTO personas (iduser, nombre, apellido, genero, tipocuenta, codigo_alumno, centro) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "issssss", $iduser, $nombre, $apellidos, $genero, $tipoCuenta, $codigoAlumno, $centroUniversitario);
    
            if (mysqli_stmt_execute($stmt)) {
                echo "Éxito";
            } else {
                echo "Error al insertar datos: " . mysqli_error($conexion);
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
