<?php
// Establecer la conexión con la base de datos
$conexion = new mysqli("localhost", "root", "Mi_218643758", "Empreverest");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$correoelectronico = $_POST["correoelectronico"];
$contraseña = $_POST["contraseña"];

// Consulta para verificar si el usuario ya existe
$sql_check_user = "SELECT contraseña FROM Usuarios WHERE correoelectronico = '$correoelectronico'";
$result = $conexion->query($sql_check_user);

if ($result->num_rows > 0) {
    // El usuario está registrado, obtener la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc();
    $contraseña_hash = sha1($contraseña); // Encriptar la contraseña ingresada

    // Obtener la contraseña hasheada almacenada en la base de datos
    $contraseña_db = $row["contraseña"];

    // Comparar la contraseña ingresada con la contraseña almacenada en la base de datos
    if ($contraseña_hash === $contraseña_db) {
        echo "Inicio de sesión exitoso";
    } else {
        echo "La contraseña es incorrecta";
    }
} else {
    echo "El usuario no está registrado.";
}

// Cerrar la conexión
$conexion->close();
?>