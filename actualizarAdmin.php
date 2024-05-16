<?php
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

$fotoperfil = $_POST['fotoPerfil'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$codigoAlumno = $_POST['codigoAlumno'];
$tipoCuenta = $_POST['tipoCuenta'];
$centro = $_POST['centro'];
$correoElectronico = $_POST['correoElectronico'];

// Preparar la consulta para seleccionar todos los campos de la tabla personas
$stmt = $conexion->prepare("SELECT * FROM personas WHERE codigo_alumno = ?");
// Vincular parámetros
$stmt->bind_param("s", $codigoAlumno);
// Ejecutar la consulta
$stmt->execute();
// Almacenar el resultado de la consulta
$stmt->store_result();
// Obtener el número de filas devueltas por la consulta
$num_rows = $stmt->num_rows;

if ($num_rows > 0) {
    // Vincular variables para almacenar los resultados
    $stmt->bind_result($idpeople, $iduser, $nombreAct, $apellidoAct, $generoAct, $tipocuentaAct, $codigo_alumnoAct, $centroAct, $fotoperfilAct);
    // Obtener los datos actuales
    $stmt->fetch();
} else {
    // No se encontró ningún registro con el código de alumno dado
    // Aquí puedes manejar este caso de acuerdo a tu lógica de negocio
}

// Liberar el resultado
$stmt->free_result();
// Cerrar la consulta
$stmt->close();

#Funciones auxiliares para las validaciones 
function valCantidadCaracteresMin($password, $min) { //Validar si el nombre contiene caracteres suficientes 
    $len = mb_strlen($password); //Obtener la longitud de caracteres 
    if ($len < $min) { //Validar que el nombre contenga minimo $min caracteres
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene caracteres suficientes 
        return true;
    } //Fin de else 
} //Fin de la funcion 

function valCantidadCaracteresMax($password, $max) { //Validar si el nombre se excede de caracteres
    $len = mb_strlen($password); //Obtener la longitud de caracteres 
    if ($len > $max) { //Validar que el nombre contenga maximo $max caracteres
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene caracteres suficientes 
        return true;
    } //Fin de else 
} //Fin de la funcion 

function valCaracteres($caracteres) { //Funcion para validar correctamente los caracteres 
    $permited_chars = 'abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ.'; //Caracteres permitidos para el nombre
    $len = mb_strlen($caracteres); //Obtener la longitud de caracteres 

    if ($len < 3 || $len > 50) { //Validar que el nombre contenga minimo 3 caracteres y maximo 50
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene la cantidad adecuada de caracteres 
        for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
            $char = mb_substr($caracteres, $i, 1, "UTF-8");
            if (!preg_match('/[a-zA-ZáéíóúÁÉÍÓÚñÑ. ]/u', $char)) { //Validar cada caracteres
                return false; //Si existe un caracter invalido, la funcion devuelve false y termina
            } //Fin de if
        } //Fin de for 
        return true; //Si el for no devolvio un false, entonces todos los caracteres son validos 
    } //Fin de else 
} //Fin de la funcion 

function valCaracteresCodigo($codigo) { //Funcion para validar correctamente las contraseñas 
    $permited_chars = '0123456789'; //Caracteres permitidos para el código
    $len = mb_strlen($codigo); //Obtener la longitud de caracteres 

    if ($len != 9) { //Validar que el código contenga exactamente 9 caracteres
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene la cantidad adecuada de caracteres 
        for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
            $char = mb_substr($codigo, $i, 1, "UTF-8");
            if (!in_array($char, str_split($permited_chars))) { //Validar cada caracteres
                return false; //Si existe un caracter invalido, la funcion devuelve false y termina
            } //Fin de if
        } //Fin de for 
        return true; //Si el for no devolvio un false, entonces todos los caracteres son validos 
    } //Fin de else 
} //Fin de la funcion

$consulta = mysqli_query($conexion, "SELECT id FROM Usuarios WHERE correoelectronico = '$correoElectronico'");
if ($fila = mysqli_fetch_assoc($consulta)) {
    $iduser = $fila['id']; #Obtener datos 

    // Obtener el idpeople asociado al iduser
    $getidpeople = mysqli_query($conexion, "SELECT idpeople FROM personas WHERE iduser = '$iduser'");
    if ($fila = mysqli_fetch_assoc($getidpeople)) {
        $idpeople = $fila['idpeople']; #Obtener ID people 

        #Validaciones para el nombre 
        if(!valCantidadCaracteresMin($nombre, $min = 3)) { #Validar si el nombre no contiene los caracteres minimos suficientes
            header("location:DashboardAdmin.php?update_people=fail&name=bad_entrymin"); #Entonces mandar mensaje de exito 
        } else if(!valCantidadCaracteresMax($nombre, $max = 40)) {
            header("location:DashboardAdmin.php?update_people=fail&name=bad_entrymax"); #Entonces mandar mensaje de exito
        } else if(!valCaracteres($nombre)) {
            header("location:DashboardAdmin.php?update_people=fail&name=bad_entry"); #Entonces mandar mensaje de exito
        } 

        #Validaciones para los apellidos 
        else if(!valCantidadCaracteresMin($apellidos, $min = 3)) { #Validar si el nombre no contiene los caracteres minimos suficientes
            header("location:DashboardAdmin.php?update_people=fail&lastnames=bad_entrymin"); #Entonces mandar mensaje de exito 
        } else if(!valCantidadCaracteresMax($apellidos, $max = 40)) {
            header("location:DashboardAdmin.php?update_people=fail&lastnames=bad_entrymax"); #Entonces mandar mensaje de exito
        } else if(!valCaracteres($apellidos)) {
            header("location:DashboardAdmin.php?update_people=fail&lastnames=bad_entry"); #Entonces mandar mensaje de exito
        } //Fin de las validaciones de apellidos 

        #Validaciones para el codigo 
        else if(!valCaracteresCodigo($codigoAlumno)) {
            header("location:DashboardAdmin.php?update_people=fail&code=bad_entry"); #Entonces mandar mensaje de exito
        }  else { //Entonces todos los datos ingresados fueron correctos
            // Actualizar los datos en la tabla personas
            $consultaDatosActuales = mysqli_query($conexion, "SELECT nombre, apellido, genero, tipocuenta, codigo_alumno, centro FROM personas WHERE idpeople = '$idpeople'");
            if ($datosActuales = mysqli_fetch_assoc($consultaDatosActuales)) {
                $nombreActual = $datosActuales['nombre'];
                $apellidoActual = $datosActuales['apellido'];
                $generoActual = $datosActuales['genero'];
                $tipoCuentaActual = $datosActuales['tipocuenta'];
                $codigoAlumnoActual = $datosActuales['codigo_alumno'];
                $centroActual = $datosActuales['centro'];
                
                $actualizar = mysqli_query($conexion, "UPDATE personas SET nombre = '$nombre', apellido = '$apellidos', genero = '$genero', codigo_alumno = '$codigoAlumno', tipocuenta = '$tipoCuenta', centro = '$centro' WHERE idpeople = '$idpeople'");
                
                if ($actualizar) { #Si los datos fueron actualizados 
                    // La consulta se ejecutó con éxito
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $navegador = $_SERVER['HTTP_USER_AGENT'];
                    $navegador = substr($navegador, 0, 50);
                    $accion = 'Actualización de usuario';
                    $estado = 'Exitoso';
                    $descripcion = 'Datos Actuales: ' . $nombreActual . ' ' . $apellidoActual . ', ' . $generoActual . ', ' . $tipoCuentaActual . ', ' . $codigoAlumnoActual . ', ' . $centroActual . ". Datos Nuevos: " . $nombre . ' ' . $apellidos . ', ' . $genero . ', ' . $tipoCuenta . ', ' . $codigoAlumno . ', ' . $centro;
                    
                    if (insertarRegistro($conexion, $iduser, $correoElectronico, $accion, $ip, $navegador, $estado, $descripcion)) {
                        // Redirigir al usuario a alguna página de éxito o mostrar un mensaje adecuado
                        header("location:DashboardAdmin.php?update_people=successful"); #Entonces mandar mensaje de éxito 
                        exit();
                    } else {
                        echo "Error al insertar el registro en la tabla de registros.";
                        exit();
                    }
                } else { #Entonces hubo un error con la actualización 
                    // Hubo un error al actualizar los datos
                    echo "Error al actualizar los datos: " . mysqli_error($conexion);
                    exit();
                }
            } else {
                echo "Error al obtener los datos actuales.";
                exit();
            }
        } //Fin de las validaciones para el codigo 
    } else {
        // No se encontró ningún registro en personas con el iduser correspondiente
        echo "No se encontró ningún registro en personas con el iduser correspondiente.";
        exit();
    }
} else {
    // No se encontró ningún usuario con el correo electrónico dado
    echo "No se encontró ningún usuario con el correo electrónico dado.";
    exit();
}
?>
