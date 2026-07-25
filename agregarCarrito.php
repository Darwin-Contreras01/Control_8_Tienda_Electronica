<?php
/*=========================================================
agregarCarrito.php
Agrega un producto al carrito de compras.
Si el producto ya existe en el carrito,
solamente aumenta su cantidad hasta el
stock disponible.
=========================================================*/
/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");

/*=========================================================
VERIFICAR QUE SE RECIBA EL PRODUCTO
=========================================================*/
if (!isset($_POST["idProducto"])) {

    $_SESSION["mensaje"] =
    "No se recibió el producto seleccionado.";

    header("Location: index.php");
    exit();
}
/*=========================================================
OBTENER EL ID DEL PRODUCTO
=========================================================*/
$idProducto =
(int) $_POST["idProducto"];

/*=========================================================
CONSULTAR EL PRODUCTO EN MYSQL
=========================================================*/
$sql = "
SELECT
id_producto,
nombre,
descripcion,
precio,
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
    header("Location: index.php");
    exit();
}
/*=========================================================
RECUPERAR DATOS DEL PRODUCTO
=========================================================*/
$producto =
$resultado->fetch_assoc();
/*=========================================================
CREAR EL CARRITO SI NO EXISTE
=========================================================*/
if (!isset($_SESSION["carrito"])) {

    $_SESSION["carrito"] = [];
}
if(isset($_SESSION["carrito"][$idProducto])){    
$cantidadActual =
    $_SESSION["carrito"]
    [$idProducto]["cantidad"];
    if (
    $cantidadActual <
    $producto["stock"]
    ) {
        $_SESSION["carrito"]
        [$idProducto]["cantidad"]++;
        $_SESSION["mensaje"] =
        "Se agregó una unidad más al carrito.";
    }
    else{
        $_SESSION["carrito"][$idProducto]=[
                "id_producto"=>$producto["id_producto"],
                "nombre"=>$producto["nombre"],
                "descripcion"=>$producto["descripcion"],
                "precio"=>$producto["precio"],
                "stock"=>$producto["stock"],
                "cantidad"=>1
            ];
            $_SESSION["mensaje"]="Producto agregado al carrito.";
    }
}
/*=========================================================
AGREGAR PRODUCTO NUEVO
=========================================================*/
else{
    $_SESSION["carrito"]
    [$idProducto] = [
        "id_producto" =>
        $producto["id_producto"],
        "nombre" =>
        $producto["nombre"],
        "descripcion" =>
        $producto["descripcion"],
        "precio" =>
        $producto["precio"],
        "stock" =>
        $producto["stock"],
        "cantidad" => 1
    ];
    $_SESSION["mensaje"] =
    "Producto agregado correctamente.";
}
/*=========================================================
CERRAR SENTENCIA
=========================================================*/
$stmt->close();
/*=========================================================
VOLVER AL INDEX
=========================================================*/
header("Location: index.php");
exit();
?>