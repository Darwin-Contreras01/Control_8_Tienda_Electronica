<?php
/*=========================================================
eliminarCarrito.php
Elimina una unidad de un producto del
carrito de compras. Si solamente queda una unidad,
el producto se elimina completamente del carrito.
=========================================================*/

/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");

/*=========================================================
VERIFICAR QUE SE RECIBA EL ID DEL PRODUCTO
=========================================================*/
if (!isset($_POST["idProducto"])) {
    $_SESSION["mensaje"] =
    "No fue posible eliminar el producto.";
    header("Location: index.php");
    exit();
}
/*=========================================================
RECUPERAR EL ID DEL PRODUCTO
=========================================================*/
$idProducto =
(int)$_POST["idProducto"];
/*=========================================================
VERIFICAR QUE EL PRODUCTO EXISTA EN EL CARRITO
=========================================================*/
if (
isset($_SESSION["carrito"][$idProducto])
) {
    /*=============================================
    DISMINUIR LA CANTIDAD
    =============================================*/
    if (
    $_SESSION["carrito"][$idProducto]["cantidad"] > 1
    ) {
        $_SESSION["carrito"][$idProducto]["cantidad"]--;
        $_SESSION["mensaje"] =
        "Se eliminó una unidad del producto.";
    }
    /*=============================================
    ELIMINAR EL PRODUCTO COMPLETO
    =============================================*/
    else {
        unset(
        $_SESSION["carrito"][$idProducto]
        );
        $_SESSION["mensaje"] =
        "Producto eliminado del carrito.";
    }
}
/*=========================================================
PRODUCTO NO EXISTE EN EL CARRITO
=========================================================*/
else {
    $_SESSION["mensaje"] =
    "El producto no se encuentra en el carrito.";
}
/*=========================================================
SI EL CARRITO QUEDA VACÍO
ELIMINAR EL ARREGLO
=========================================================*/
if (
empty($_SESSION["carrito"])
) {
    unset($_SESSION["carrito"]);
    $_SESSION["carrito"] = [];
}
/*=========================================================
VOLVER A LA PÁGINA PRINCIPAL
=========================================================*/
header("Location: index.php");
exit();
?>