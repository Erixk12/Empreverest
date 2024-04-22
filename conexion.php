<?php

// Crear la conexión
$conexion = mysqli_connect("localhost", "root", "Mi_218643758", "Empreverest");

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa";
}
?>
