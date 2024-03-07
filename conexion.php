<?php

// Crear la conexión
$conexion = mysqli_connect("localhost", "root", "root", "Empreverest");

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa";
}

?>