<?php
/*=========================================================
registrarCompra.php
Permite registrar las compras realizadas
por un cliente utilizando los productos
almacenados en el carrito de compras.
=========================================================*/
/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");
/*=========================================================
VARIABLE MENSAJE
=========================================================*/
$mensaje = "";
/*=========================================================
VERIFICAR QUE EL CARRITO NO ESTÉ VACÍO
=========================================================*/
if(
    !isset($_SESSION["carrito"]) ||
    count($_SESSION["carrito"]) == 0
){
    $_SESSION["mensaje"] =
    "Debe agregar productos al carrito antes de registrar la compra.";
    header("Location: index.php");
    exit();
}
/*=========================================================
OBTENER CLIENTES
=========================================================*/
$sqlClientes = "
SELECT
id_cliente,
nombre
FROM CLIENTE
ORDER BY nombre
";
$resultadoClientes =
$conexion->query($sqlClientes);
/*=========================================================
PROCESAR EL FORMULARIO
=========================================================*/
if($_SERVER["REQUEST_METHOD"]=="POST"){
    /*=============================================
    RECUPERAR CLIENTE
    =============================================*/
    $idCliente =
    (int)$_POST["idCliente"];
    if($idCliente<=0){
        $mensaje =
        "Debe seleccionar un cliente.";
    }
    else{
        /*=========================================
        VERIFICAR CLIENTE
        =========================================*/
        $sqlCliente = "
        SELECT
        id_cliente
        FROM CLIENTE
        WHERE id_cliente = ?
        ";
        $stmt =
        $conexion->prepare($sqlCliente);
        $stmt->bind_param(
        "i",
        $idCliente
        );
        $stmt->execute();
        $resultado =
        $stmt->get_result();
        if($resultado->num_rows==0){
            $mensaje =
            "El cliente seleccionado no existe.";
            $stmt->close();
        }
        else{
            $stmt->close();
            /*=====================================
            INICIAR TRANSACCIÓN
            =====================================*/
            $conexion->begin_transaction();
            try{
                foreach(
                    $_SESSION["carrito"]
                    as
                    $producto
                ){
                    /*=============================
                    CONSULTAR STOCK
                    =============================*/
                    $sqlStock = "
                    SELECT
                    stock
                    FROM PRODUCTO
                    WHERE id_producto = ?
                    ";
                    $stmt =
                    $conexion->prepare($sqlStock);
                    $stmt->bind_param(
                    "i",
                    $producto["id_producto"]
                    );
                    $stmt->execute();
                    $resultadoStock =
                    $stmt->get_result();
                    $datosStock =
                    $resultadoStock->fetch_assoc();
                    $stmt->close();
                    /*=============================
                    VALIDAR STOCK
                    =============================*/
                    if(
                    $datosStock["stock"] <
                    $producto["cantidad"]
                    ){
                        throw new Exception(
                        "Stock insuficiente."
                        );
                    }
                    /*=============================
                    CALCULAR TOTAL
                    =============================*/
                    $total =
                    $producto["precio"] *
                    $producto["cantidad"];
                    /*=============================
                    INSERTAR COMPRA
                    =============================*/
                    $sqlCompra = "
                    INSERT INTO COMPRA(
                    cantidad,
                    total,
                    fecha,
                    id_producto,
                    id_cliente
                    )
                    VALUES(
                    ?,
                    ?,
                    NOW(),
                    ?,
                    ?
                    )
                    ";
                    $stmt =
                    $conexion->prepare(
                    $sqlCompra
                    );
                    $stmt->bind_param(
                    "idii",
                    $producto["cantidad"],
                    $total,
                    $producto["id_producto"],
                    $idCliente
                    );
                    $stmt->execute();
                    $stmt->close();
                    /*=============================
                    DESCONTAR STOCK
                    =============================*/
                    $sqlActualizar = "
                    UPDATE PRODUCTO
                    SET stock =
                    stock - ?
                    WHERE id_producto = ?
                    ";
                    $stmt =
                    $conexion->prepare(
                    $sqlActualizar
                    );
                    $stmt->bind_param(
                    "ii",
                    $producto["cantidad"],
                    $producto["id_producto"]
                    );
                    $stmt->execute();
                    $stmt->close();
                }
                /*=============================
                CONFIRMAR CAMBIOS
                =============================*/
                $conexion->commit();
                /*=============================
                VACIAR CARRITO
                =============================*/
                $_SESSION["carrito"] = [];

                $mensaje =
                "Compra registrada correctamente.";
            }
            catch(Exception $e){
                $conexion->rollback();
                $mensaje =
                "Error al registrar la compra.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width, initial-scale=1.0">
<title> Registrar Compra </title>
<link
rel="stylesheet"
href="styles.css">
<script>
/*=========================================================
VALIDAR FORMULARIO
=========================================================*/
function validarCompra(){
    let cliente =
    document.getElementById(
    "idCliente"
    ).value;
    if(cliente==""){
        alert(
        "Debe seleccionar un cliente."
        );
        return false;
    }
    return confirm(
    "¿Desea registrar esta compra?"
    );
}
</script>
</head>
<body>
<!--=====================================================
ENCABEZADO
======================================================-->
<header>
<h1> Registrar Compra </h1>
<p> Seleccione el cliente y confirme la compra. </p>
</header>
<!--=====================================================
MENÚ
======================================================-->
<nav class="menuPrincipal">

<a href="index.php">
Inicio </a>
<a href="registrarProducto.php"> Productos </a>
<a href="registrarCliente.php"> Clientes </a>
</nav>
<!--=====================================================
MENSAJE
======================================================-->
<?php
if($mensaje!=""){
?>
<div class="mensaje">
<?= htmlspecialchars($mensaje) ?>
</div>
<?php
}
?>
<!--=====================================================
FORMULARIO
======================================================-->
<section
class="formularioCompra">
<h2> Datos de la Compra </h2>
<form
action="registrarCompra.php"
method="POST"
onsubmit="return validarCompra();"
>
<label> Cliente </label>
<select
name="idCliente"
id="idCliente"
required>
<option value=""> Seleccione un cliente </option>
<?php
while(
$cliente =
$resultadoClientes->fetch_assoc()
){
?>
<option
value="<?= $cliente["id_cliente"] ?>">
<?= htmlspecialchars(
$cliente["nombre"]
) ?>
</option>
<?php
}
?>
</select>
<br><br>
<button
type="submit"> Registrar Compra </button>
<button
type="reset"> Cancelar </button>
</form>
</section>
<!--=====================================================
RESUMEN DEL CARRITO
======================================================-->
<section>
<h2> Productos de la Compra </h2>
<table
class="tablaCarrito">
<thead>
<tr>
<th> Producto </th>
<th> Precio </th>
<th> Cantidad </th>
<th> Subtotal </th>
</tr>
</thead>
<tbody>
<?php
$totalCompra = 0;
foreach(
$_SESSION["carrito"]
as
$producto
){
$subtotal =
$producto["precio"] *
$producto["cantidad"];
$totalCompra +=
$subtotal;
?>
<tr>
<td>
<?= htmlspecialchars(
$producto["nombre"]
) ?>
</td>
<td>
$
<?= number_format(
$producto["precio"],
0,
",",
"."
) ?>
</td>
<td>
<?= $producto["cantidad"] ?>
</td>
<td>
$
<?= number_format(
$subtotal,
0,
",",
"."
) ?>
</td>
</tr>
<?php
}
?>
</tbody>
<tfoot>
<tr>
<td colspan="3">
<strong> TOTAL GENERAL </strong>
</td>
<td>
<strong>
$
<?= number_format(
$totalCompra,
0,
",",
"."
) ?>
</strong>
</td>
</tr>
</tfoot>
</table>
</section>
<!--=====================================================
CONSULTA SIMPLE DE COMPRAS
======================================================-->
<?php
$sqlCompras = "
SELECT
co.id_compra,
cl.nombre AS cliente,
pr.nombre AS producto,
co.cantidad,
co.total,
co.fecha
FROM COMPRA co
INNER JOIN CLIENTE cl
ON co.id_cliente = cl.id_cliente
INNER JOIN PRODUCTO pr
ON co.id_producto = pr.id_producto
ORDER BY co.fecha DESC
";
$resultadoCompras =
$conexion->query($sqlCompras);
?>
<section>
<h2> Compras Registradas </h2>
<?php
if(
$resultadoCompras &&
$resultadoCompras->num_rows>0
){
?>
<table class="tablaCarrito">
<thead>
<tr>
<th> ID </th>
<th> Cliente </th>
<th> Producto </th>
<th> Cantidad </th>
<th> Total </th>
<th> Fecha </th>
</tr>
</thead>
<tbody>
<?php
while(
$compra =
$resultadoCompras->fetch_assoc()
){
?>
<tr>
<td>
<?= $compra["id_compra"] ?>
</td>
<td>
<?= htmlspecialchars(
$compra["cliente"]
) ?>
</td>
<td>
<?= htmlspecialchars(
$compra["producto"]
) ?>
</td>
<td>
<?= $compra["cantidad"] ?>
</td>
<td>
$
<?= number_format(
$compra["total"],
0,
",",
"."
) ?>
</td>
<td>
<?= date(
"d/m/Y H:i",
strtotime(
$compra["fecha"]
)
) ?>
</td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php
}
else{
?>
<p> No existen compras registradas. </p>
<?php
}
?>
</section>
<!--=====================================================
BOTONES
======================================================-->
<br>
<div
class="botonesFormulario">
<a href="index.php">
<button type="button">
Volver al Inicio
</button> </a>
<a href="consultaAvanzada.php">
<button
type="button">
Consulta Avanzada
</button> </a>
</div>
</body>
</html>