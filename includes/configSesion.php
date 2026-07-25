<?php
/*=========================================================
configSesion.php
Configura la sesión de la aplicación,
controla el tiempo de inactividad
y carga automáticamente la conexión
con la base de datos MySQL.
=========================================================*/

/*=========================================================
CONFIGURAR EL TIEMPO DE VIDA DE LA SESIÓN
(30 MINUTOS)
=========================================================*/
ini_set(
    "session.gc_maxlifetime",
    1800
);
/*=========================================================
CONFIGURAR LA COOKIE DE SESIÓN
=========================================================*/
session_set_cookie_params([
    "lifetime" => 1800,
    "path" => "/",
    "secure" => false,
    "httponly" => true,
    "samesite" => "Lax"
]);
/*=========================================================
INICIAR LA SESIÓN
=========================================================*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*=========================================================
REGENERAR EL ID DE LA SESIÓN
SOLO LA PRIMERA VEZ
=========================================================*/
if (!isset($_SESSION["sesionIniciada"])) {
    session_regenerate_id(true);
    $_SESSION["sesionIniciada"] = true;
}
/*=========================================================
CONTROLAR EL TIEMPO DE INACTIVIDAD
=========================================================*/
if (isset($_SESSION["ultimoMovimiento"])) {
    $tiempoInactivo =
        time() - $_SESSION["ultimoMovimiento"];
    if ($tiempoInactivo > 1800) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION["mensaje"] =
        "La sesión expiró por inactividad.";
    }
}
/*=========================================================
ACTUALIZAR EL ÚLTIMO MOVIMIENTO
=========================================================*/
$_SESSION["ultimoMovimiento"] = time();
/*=========================================================
CREAR LAS VARIABLES DE SESIÓN
=========================================================*/
/*-----------------------------------------
CARRITO DE COMPRAS
-----------------------------------------*/
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}
/*-----------------------------------------
CLIENTE SELECCIONADO
-----------------------------------------*/
if (!isset($_SESSION["cliente"])) {
    $_SESSION["cliente"] = null;
}
/*-----------------------------------------
USUARIO
-----------------------------------------*/
if (!isset($_SESSION["usuario"])) {
    $_SESSION["usuario"] = null;
}
/*-----------------------------------------
MENSAJES DEL SISTEMA
-----------------------------------------*/
if (!isset($_SESSION["mensaje"])) {
    $_SESSION["mensaje"] = "";
}
/*=========================================================
CARGAR LA CONEXIÓN CON MYSQL
=========================================================*/
require_once(__DIR__ . "/conexion.php");
?>