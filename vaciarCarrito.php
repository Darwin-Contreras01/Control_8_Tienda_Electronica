<?php
/*=========================================================
vaciarCarrito.php
Elimina todos los productos almacenados
en el carrito de compras.
=========================================================*/

/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");

/*=========================================================
VERIFICAR SI EXISTE EL CARRITO
=========================================================*/
if (
isset($_SESSION["carrito"]) &&
count($_SESSION["carrito"]) > 0
){
    /*=============================================
    VACIAR EL CARRITO
    =============================================*/
    $_SESSION["carrito"] = [];

    $_SESSION["mensaje"] =
    "El carrito de compras fue vaciado correctamente.";
}
else{
    $_SESSION["mensaje"] =
    "El carrito ya se encontraba vacío.";
}
/*=========================================================
VOLVER A LA PÁGINA PRINCIPAL
=========================================================*/
header("Location: index.php");
exit();
?>