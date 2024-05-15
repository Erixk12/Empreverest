<?php
// Conexión a la base de datos
include("conexion.php");

$fotoperfil = $_FILES['fotoPerfil'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$codigoAlumno = $_POST['codigoAlumno'];
$tipoCuenta = $_POST['tipoCuenta'];
$centro = $_POST['centro'];
$correoElectronico = $_POST['correoElectronico'];
$contrasena = $_POST['contrasena'];



// Consulta para verificar si el usuario ya existe
$sql_check_user = "SELECT id, contraseña FROM Usuarios WHERE correoelectronico = ?"; // Usar parámetros para evitar inyección SQL

// Sanitizar las entradas, evitar inyecciones SQL
$stmt = $conexion->prepare($sql_check_user);
$stmt->bind_param("s", $correoelectronico);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) { // Validar si no existe un correo electrónico en la base de datos
    
}else{



}
$sql= "UPDATE FROM Usuarios where";


?>