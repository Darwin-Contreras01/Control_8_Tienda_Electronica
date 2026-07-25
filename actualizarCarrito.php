<?php
/*=========================================================
actualizarCarrito.php
Actualiza la cantidad de un producto
almacenado en el carrito de compras.
=========================================================*/
/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");

/*=========================================================
VERIFICAR DATOS RECIBIDOS
=========================================================*/
if (
    !isset($_POST["idProducto"]) ||
    !isset($_POST["cantidad"])
) {
    $_SESSION["mensaje"] =
    "No fue posible actualizar el carrito.";
    header("Location: index.php");
    exit();
}
/*=========================================================
RECUPERAR DATOS
=========================================================*/
$idProducto =
(int)$_POST["idProducto"];
$cantidad =
(int)$_POST["cantidad"];
/*=========================================================
VALIDAR CANTIDAD
=========================================================*/
if ($cantidad <= 0) {
    unset($_SESSION["carrito"][$idProducto]);
    $_SESSION["mensaje"] =
    "Producto eliminado del carrito.";
    header("Location: index.php");
    exit();
}
/*=========================================================
CONSULTAR PRODUCTO EN MYSQL
=========================================================*/
$sql = "
SELECT
id_producto,
stock
FROM PRODUCTO
WHERE id_producto = ?
";
$stmt =
$conexion->prepare($sql);
$stmt->bind_param(
"i",
$idProducto
);
$stmt->execute();
$resultado =
$stmt->get_result();
/*=========================================================
VERIFICAR EXISTENCIA
=========================================================*/
if ($resultado->num_rows == 0) {
    $_SESSION["mensaje"] =
    "El producto no existe.";
    $stmt->close();
    header("Location: index.php");
    exit();
}
/*=========================================================
OBTENER STOCK
=========================================================*/
$producto =
$resultado->fetch_assoc();
/*=========================================================
VALIDAR STOCK
=========================================================*/
if ($cantidad > $producto["stock"]) {
    $cantidad =
    $producto["stock"];
    $_SESSION["mensaje"] =
    "La cantidad fue ajustada al stock disponible.";
}
else{
    $_SESSION["mensaje"] =
    "Cantidad actualizada correctamente.";
}
/*=========================================================
ACTUALIZAR EL CARRITO
=========================================================*/
if (
isset($_SESSION["carrito"][$idProducto]["cantidad"])
) {
    $_SESSION["carrito"]
    [$idProducto]["cantidad"] =
    $cantidad;
}
/*=========================================================
CERRAR CONSULTA
=========================================================*/
$stmt->close();
/*=========================================================
VOLVER AL INDEX
=========================================================*/
header("Location: index.php");
exit();

?>