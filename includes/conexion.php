<?php
/*=========================================================
conexion.php: Establece la conexión entre la aplicación
y la base de datos MySQL utilizando MySQLi.
=========================================================*/

/*=========================================================
CONFIGURACIÓN DE LA BASE DE DATOS
=========================================================*/
$HOST = "localhost";
$USUARIO = "root";
$PASSWORD = "";
$BASE_DATOS = "TIENDA";
$PUERTO = 3307;
/*=========================================================
CREAR LA CONEXIÓN
=========================================================*/
$conexion = new mysqli(
    $HOST,
    $USUARIO,
    $PASSWORD,
    $BASE_DATOS,
    $PUERTO
);
/*=========================================================
VERIFICAR LA CONEXIÓN
=========================================================*/
if ($conexion->connect_errno) {
    die("
    <h2>Error de conexión con MySQL</h2>
    <p>
    No fue posible establecer la conexión con la base de datos.
    </p>
    <p>
    <strong>Código del error:</strong>
    {$conexion->connect_errno}
    </p>
    <p>
    <strong>Descripción:</strong>
    {$conexion->connect_error}
    </p>
    ");
}
/*=========================================================
CONFIGURAR EL JUEGO DE CARACTERES
=========================================================*/
if (!$conexion->set_charset("utf8mb4")) {
    die("
    <h2>Error</h2>
    <p>
    No fue posible configurar la codificación UTF-8.
    </p>
    ");
}
/*=========================================================
CONFIGURAR ZONA HORARIA
=========================================================*/
date_default_timezone_set("America/Santiago");
/*=========================================================
CONEXIÓN LISTA PARA SER UTILIZADA
=========================================================*/
// La variable $conexion queda disponible para todos
// los archivos que incluyan este archivo.
?>