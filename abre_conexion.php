<?php

// Parametros a configurar para la conexion de la base de datos
include_once("conn.conf");


// Fin de los parametros a configurar para la conexion de la base de datos

$conexion_db = mysqli_connect("$hotsdb","$usuariodb","$clavedb","$basededatos");
if (!$conexion_db) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>
