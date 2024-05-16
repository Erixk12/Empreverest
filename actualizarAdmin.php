<?php
include("conexion.php");

$fotoperfil = $_POST['fotoPerfil'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$codigoAlumno = $_POST['codigoAlumno'];
$tipoCuenta = $_POST['tipoCuenta'];
$centro = $_POST['centro'];
$correoElectronico = $_POST['correoElectronico'];

// Preparar la consulta
$stmt = $conexion->prepare("SELECT idpeople FROM personas WHERE codigo_alumno = ?");
// Vincular parámetros
$stmt->bind_param("s", $codigoAlumno);
// Ejecutar la consulta
$stmt->execute();
// Almacenar el resultado de la consulta
$stmt->store_result();
// Obtener el número de filas devueltas por la consulta
$num_rows = $stmt->num_rows;
// Liberar el resultado
$stmt->free_result();
// Cerrar la consulta
$stmt->close();

#Funciones auxiliares para las validaciones 
function valCantidadCaracteresMin($password, $min) { //Validar si la contraseña contiene caracteres suficientes 
    $len = mb_strlen($password); //Obtener la longitud de caracteres 
    if ($len < $min) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene caracteres suficientes 
        return true;
    } //Fin de else 
} //Fin de la funcion 

function valCantidadCaracteresMax($password, $max) { //Validar si la contraseña se excede de caracteres
    $len = mb_strlen($password); //Obtener la longitud de caracteres 
    if ($len > $max) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene caracteres suficientes 
        return true;
    } //Fin de else 
} //Fin de la funcion 

function valCaracteres($caracteres) { //Funcion para validar correctamente las contraseñas 
    $permited_chars = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ'; //Caracteres permitidos para la contraseña
    $len = mb_strlen($caracteres); //Obtener la longitud de caracteres 

    if ($len < 3 || $len > 50) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene la cantidad adecuada de caracteres 
        for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
            $char = mb_substr($caracteres, $i, 1, "UTF-8");
            if (!in_array($char, str_split($permited_chars))) { //Validar cada caracteres
                return false; //Si existe un caracterer invalido, la funcion devuelve false y termina
            } //Fin de if
        } //Fin de for 
        return true; //Si el for no devolvio un false, entonces todos los caracteres son validos 
    } //Fin de else 
} //Fin de la funcion 

function valCaracteresCodigo($codigo) { //Funcion para validar correctamente las contraseñas 
    $permited_chars = '0123456789'; //Caracteres permitidos para la contraseña
    $len = mb_strlen($codigo); //Obtener la longitud de caracteres 

    if ($len < 9 || $len > 9) { //Validar que la contraseña contenga minimo 8 caracteres y maximo 20
        return false; //Devolver falso porque no contiene la cantidad adecuada de caracteres
    } else { //Entonces contiene la cantidad adecuada de caracteres 
        for ($i = 0; $i < $len; $i++) { //Recorrer cada palabra
            $char = mb_substr($codigo, $i, 1, "UTF-8");
            if (!in_array($char, str_split($permited_chars))) { //Validar cada caracteres
                return false; //Si existe un caracterer invalido, la funcion devuelve false y termina
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
        } else if($num_rows > 0) {
            header("location:DashboardAdmin.php?update_people=fail&code=repeated"); #Entonces mandar mensaje de exito
        } else { //Entonces todos los datos ingresados fueron correctos
        // Actualizar los datos en la tabla personas
        $actualizar = mysqli_query($conexion, "UPDATE personas SET nombre = '$nombre', apellido = '$apellidos', genero = '$genero', codigo_alumno = '$codigoAlumno', tipocuenta = '$tipoCuenta', centro = '$centro' WHERE idpeople = '$idpeople'");
        } //Fin de las validaciones para el codigo 

        if ($actualizar) { #Si los datos fueron actualizados 
            header("location:DashboardAdmin.php?update_people=successful"); #Entonces mandar mensaje de exito 
        } else { #Entonces hubo un error con la actualizacion 
            // Hubo un error al actualizar los datos
            echo "Error al actualizar los datos: " . mysqli_error($conexion);
        }
    } else {
        // No se encontró ningún registro en personas con el iduser correspondiente
        echo "No se encontró ningún registro en personas con el iduser correspondiente.";
    }
} else {
    // No se encontró ningún usuario con el correo electrónico dado
    echo "No se encontró ningún usuario con el correo electrónico dado.";
}
?>
