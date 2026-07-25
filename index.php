<?php
/*=========================================================
TIENDA DE COMERCIO ELECTRÓNICO
index.php: Archivo principal del sistema.
Permite visualizar los productos,
administrar el carrito de compras,
registrar clientes, productos y compras,
además de ejecutar consultas simples
y consultas avanzadas sobre MySQL.
=========================================================*/
/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");
/*=========================================================
CARGAR CLASE PEDIDO
=========================================================*/
require_once(__DIR__ . "/clases/Pedido.php");
/*=========================================================
OBTENER PRODUCTOS
=========================================================*/
$sqlProductos = "
SELECT
id_producto,
nombre,
descripcion,
precio,
stock
FROM PRODUCTO
ORDER BY nombre
";
$resultadoProductos =
$conexion->query($sqlProductos);
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
MENSAJE DEL SISTEMA
=========================================================*/
$mensaje = "";
if(isset($_SESSION["mensaje"])){
    $mensaje =
    $_SESSION["mensaje"];
    unset($_SESSION["mensaje"]);
}
/*=========================================================
TOTAL DE PRODUCTOS EN EL CARRITO
=========================================================*/
$cantidadCarrito = 0;
if(!empty($_SESSION["carrito"])){
    foreach(
        $_SESSION["carrito"]
        as
        $producto
    ){
        $cantidadCarrito +=
        $producto["cantidad"];
    }
}
/*=========================================================
TOTAL GENERAL DEL CARRITO
=========================================================*/
$totalCarrito = 0;
if(!empty($_SESSION["carrito"])){
    foreach(
        $_SESSION["carrito"]
        as
        $producto
    ){
        $subtotal =
        $producto["precio"] *
        $producto["cantidad"];
        $totalCarrito +=
        $subtotal;
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

<title> Tienda de Comercio Electrónico</title>
<link
rel="stylesheet"
href="styles.css">
</head>
<body>
<!--=====================================================
ENCABEZADO
======================================================-->
<header>
<h1>TIENDA DE COMERCIO ELECTRÓNICO</h1>
<p>Sistema de gestión de compras de TIENDA</p>
</header>
<!--=====================================================
MENÚ PRINCIPAL
======================================================-->
<nav class="menuPrincipal">
<a href="#productos">Productos</a>
<a href="#administracion">Administración</a>
<a href="#carrito">Ver Carrito</a>
<a href="consultarProductos.php">
Consultar Productos</a>
<a href="consultarClientes.php">
Consultar Clientes</a>
<a href="consultaAvanzada.php">
Consulta Avanzada</a>
<a href="#resenas">Reseñas</a>
<a href="buscarPedido.php">Buscar Pedido</a>
</nav>
<!--=====================================================
MENSAJE DEL SISTEMA
======================================================-->
<?php if($mensaje!=""){ ?>
<section
class="mensaje">
<?= htmlspecialchars($mensaje) ?>
</section>
<?php
}
?>
<!--=====================================================
RESUMEN DEL CARRITO
======================================================-->
<section id="estadoCarrito"
class="estadoCarrito">
<h2>Estado del Carrito</h2>
<p>Cantidad de productos:
<strong>
<?= $cantidadCarrito ?>
</strong>
</p>
<p>Total acumulado:
<strong>
$
<?= number_format(
$totalCarrito,
0,
",",
"."
) ?>
</strong>
</p>
</section>
<!--=====================================================
BUSCADOR
======================================================-->
<section
class="busqueda">
<h2>Buscar Productos</h2>
<div
class="busquedaControles">
<input
type="text"
id="txtBuscar"
placeholder="Ingrese el nombre del producto">
<button
id="btnBuscar"
type="button">Buscar</button>
</div>
</section>
<!--=====================================================
CATÁLOGO DE PRODUCTOS
======================================================-->
<section
id="productos">
<h2>Catálogo de Productos</h2>
<p>
Seleccione uno o más productos para agregarlos
al carrito de compras.
</p>
<div
id="contenedorProductos" class="contenedorProductos">
<?php
if(
$resultadoProductos &&
$resultadoProductos->num_rows > 0
){
while(
$producto =
$resultadoProductos->fetch_assoc()
){
?>
<div
class="tarjetaProducto">
<!--=========================================
NOMBRE
==========================================-->
<h3>
<?= htmlspecialchars(
$producto["nombre"]
) ?>
</h3>
<!--=========================================
DESCRIPCIÓN
==========================================-->
<p>
<?= nl2br(
htmlspecialchars(
$producto["descripcion"]
)
) ?>
</p>
<!--=========================================
PRECIO
==========================================-->
<p>
<strong>Precio</strong>
</p>
<p>
$
<?= number_format(
$producto["precio"],
0,
",",
"."
) ?>
</p>
<!--=========================================
STOCK
==========================================-->
<p>
<strong>
Stock disponible:
</strong>
<?= $producto["stock"] ?>
unidades
</p>
<!--=========================================
VALIDAR STOCK
==========================================-->
<?php
if(
$producto["stock"] > 0
){
?>
<form
action="agregarCarrito.php"
method="POST">
<input
type="hidden"
name="idProducto"
value="<?= $producto["id_producto"] ?>">
<button
type="submit">
Agregar al carrito
</button>
</form>
<?php
}
else{
?>
<button type="button" disabled> Producto sin stock </button>
<?php
}
?>
</div>
<?php
}
}
else{
?>
<div
class="carritoVacio">
<p>No existen productos registrados en la base de datos.
</p>
</div>
<?php
}
?>
</div>
</section>
<!--=====================================================
CARRITO DE COMPRAS
======================================================-->
<section
id="carrito"
class="carritoCompras">
<h2>
Carrito de Compras
</h2>
<?php
$totalCompra = 0;
if(
!empty($_SESSION["carrito"])
){
?>
<table
class="tablaCarrito">
<thead>
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php
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
<!--=========================================
NOMBRE
==========================================-->
<td>
<?= htmlspecialchars(
$producto["nombre"]
) ?>
</td>
<!--=========================================
PRECIO
==========================================-->
<td>
$
<?= number_format(
$producto["precio"],
0,
",",
"."
) ?>
</td>
<!--=========================================
ACTUALIZAR CANTIDAD
==========================================-->
<td>
<form
action="actualizarCarrito.php"
method="POST"
class="formCantidad">
<input
type="hidden"
name="idProducto"
value="<?= $producto["id_producto"] ?>">
<input
type="number"
name="cantidad"
min="1"
max="<?= $producto["stock"] ?>"
value="<?= $producto["cantidad"] ?>"
required>
<button type="submit"> Actualizar </button>
</form>
</td>
<!--=========================================
SUBTOTAL
==========================================-->
<td>
$
<?= number_format(
$subtotal,
0,
",",
"."
) ?>
</td>
<!--=========================================
ELIMINAR PRODUCTO
==========================================-->
<td>
<form
action="eliminarCarrito.php"
method="POST">
<input
type="hidden"
name="idProducto"
value="<?= $producto["id_producto"] ?>">
<button
type="submit"
class="btnEliminar"
onclick="return confirm(
'¿Desea eliminar este producto del carrito?'
);"> Eliminar </button>
</form>
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
<td>
&nbsp;
</td>
</tr>
</tfoot>
</table>
<!--=========================================
ACCIONES DEL CARRITO
==========================================-->
<div
class="accionesCarrito">
<a href="#compras">
<button
type="button"
class="btnPedido"> Registrar Compra </button>
</a>
<a
href="vaciarCarrito.php"
onclick="return confirm(
'¿Desea vaciar completamente el carrito?'
);">
<button
type="button"
class="btnVaciar"> Vaciar Carrito </button>
</a>
</div>
<?php
}
else{
?>
<div
class="carritoVacio">
<h3> El carrito está vacío </h3>
<p>
Seleccione uno o más productos del catálogo
para comenzar la compra.
</p>
<a href="#productos">
<button
type="button"> Ir al Catálogo </button>
</a>
</div>
<?php
}
?>
</section>
<!--=====================================================
CENTRO DE ADMINISTRACIÓN
======================================================-->
<section
id="administracion"
class="panelAdministracion">
<h2> Administración de la Tienda </h2>
<p>
Seleccione una opción para administrar
la información almacenada en MySQL.
</p>
<div
class="contenedorAdministracion">
<!--=========================================
REGISTRAR PRODUCTOS
==========================================-->
<div
class="tarjetaAdministracion">
<h3>
Productos
</h3>
<p>
Registrar nuevos productos
en la base de datos.
</p>
<a
href="registrarProducto.php">
<button
type="button">
Registrar Producto
</button>
</a>
</div>
<!--=========================================
REGISTRAR CLIENTES
==========================================-->
<div
class="tarjetaAdministracion">
<h3> Clientes </h3>
<p> Registrar nuevos clientes
de la tienda.</p>
<a
href="registrarCliente.php">
<button
type="button"> Registrar Cliente </button>
</a>
</div>
<!--=========================================
MOSTRAR COMPRAS
==========================================-->
<div
class="tarjetaAdministracion">
<h3> Compras </h3>
<p> Visualizar todas las compras
registradas en la base de datos.</p>
<a
href="mostrarCompras.php">
<button
type="button"> Mostrar Compras </button>
</a>
</div>
<!--=========================================
CONSULTAR PRODUCTOS
==========================================-->
<div
class="tarjetaAdministracion">
<h3> Consulta de Productos </h3>
<p> Visualizar todos los productos
registrados.</p>
<a
href="consultarProductos.php">
<button
type="button"> Consultar Productos </button>
</a>
</div>
<!--=========================================
CONSULTAR CLIENTES
==========================================-->
<div
class="tarjetaAdministracion">
<h3> Consulta de Clientes </h3>
<p> Visualizar todos los clientes
registrados.</p>
<a
href="consultarClientes.php">
<button
type="button"> Consultar Clientes </button>
</a>
</div>
<!--=========================================
CONSULTA AVANZADA
==========================================-->
<div
class="tarjetaAdministracion">
<h3> Consulta Avanzada </h3>
<p> Visualizar consultas avanzadas.</p>
<a
href="consultaAvanzada.php">
<button
type="button"> Consulta Avanzada </button>
</a>
</div>
</div>
</section>
<!--=====================================================
RESUMEN DEL SISTEMA
======================================================-->
<section
id="resumenSistema"
class="resumenSistema">
<h2> Resumen del Sistema </h2>
<?php
/*=========================================
TOTAL PRODUCTOS
==========================================*/
$sqlTotalProductos = "
SELECT COUNT(*) AS total
FROM PRODUCTO
";
$resultadoTotalProductos =
$conexion->query($sqlTotalProductos);
$totalProductos = 0;
if(
$resultadoTotalProductos &&
$resultadoTotalProductos->num_rows > 0
){
$totalProductos =
$resultadoTotalProductos
->fetch_assoc()["total"];
}
/*=========================================
TOTAL CLIENTES
==========================================*/
$sqlTotalClientes = "
SELECT COUNT(*) AS total
FROM CLIENTE
";
$resultadoTotalClientes =
$conexion->query($sqlTotalClientes);
$totalClientes = 0;
if(
$resultadoTotalClientes &&
$resultadoTotalClientes->num_rows > 0
){
$totalClientes =
$resultadoTotalClientes
->fetch_assoc()["total"];
}
/*=========================================
TOTAL COMPRAS
==========================================*/
$sqlTotalCompras = "
SELECT COUNT(*) AS total
FROM COMPRA
";
$resultadoTotalCompras =
$conexion->query($sqlTotalCompras);
$totalCompras = 0;
if(
$resultadoTotalCompras &&
$resultadoTotalCompras->num_rows > 0
){
$totalCompras =
$resultadoTotalCompras
->fetch_assoc()["total"];
}
/*=========================================
STOCK DISPONIBLE
==========================================*/
$sqlStock = "
SELECT SUM(stock) AS totalStock
FROM PRODUCTO
";
$resultadoStock =
$conexion->query($sqlStock);
$totalStock = 0;
if(
$resultadoStock &&
$resultadoStock->num_rows > 0
){
$filaStock =
$resultadoStock->fetch_assoc();
$totalStock =
$filaStock["totalStock"] ?? 0;
}
?>
<div class="contenedorResumen">
<div class="tarjetaResumen">
<h3> Productos </h3>
<p> <?= $totalProductos ?> </p>
</div>
<div
class="tarjetaResumen">
<h3> Clientes </h3>
<p> <?= $totalClientes ?> </p>
</div>
<div class="tarjetaResumen">
<h3> Compras </h3>
<p>
<?= $totalCompras ?>
</p>
</div>
<div class="tarjetaResumen">
<h3> Stock Disponible </h3>
<p> <?= $totalStock ?> </p>
</div>
</div>
</section>
<!--=====================================================
ÚLTIMAS COMPRAS
======================================================-->
<section
id="ultimasCompras">
<h2> Últimas Compras Registradas </h2>
<?php
$sqlUltimasCompras = "
SELECT
co.id_compra,
co.fecha,
cl.nombre
AS cliente,
pr.nombre
AS producto,
co.cantidad,
co.total
FROM COMPRA co
INNER JOIN CLIENTE cl
ON co.id_cliente =
cl.id_cliente
INNER JOIN PRODUCTO pr
ON co.id_producto =
pr.id_producto
ORDER BY
co.fecha DESC
LIMIT 5
";
$resultadoCompras =
$conexion->query(
$sqlUltimasCompras
);
if(
$resultadoCompras &&
$resultadoCompras->num_rows > 0
){
?>
<table
class="tablaCarrito">
<thead>
<tr> 
<th> Fecha </th>
<th> Cliente </th>
<th> Producto </th>
<th> Cantidad </th>
<th> Total </th>
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
<?= date(
"d/m/Y",
strtotime(
$compra["fecha"]
)
) ?>
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
<p> Todavía no existen compras registradas. </p>
<?php
}
?>
</section>
<!--=====================================================
ÚLTIMAS RESEÑAS
======================================================-->
<section
id="resenas"
class="ultimasResenas">
<h2> Últimas Reseñas </h2>
<?php
$sqlResenas = "
SELECT
r.id_resena,
r.cliente,
r.calificacion,
r.comentario,
r.fecha,
p.nombre
AS producto
FROM RESENA r
INNER JOIN PRODUCTO p
ON r.id_producto =
p.id_producto
ORDER BY
r.fecha DESC
LIMIT 5
";
$resultadoResenas =
$conexion->query(
$sqlResenas
);
if(
$resultadoResenas &&
$resultadoResenas->num_rows > 0
){
while(
$resena =
$resultadoResenas->fetch_assoc()
){
?>
<div
class="tarjetaResena">
<h3>
<?= htmlspecialchars(
$resena["producto"]
) ?>
</h3>
<p>
<strong> Cliente: 
</strong>
<?= htmlspecialchars(
$resena["cliente"]
) ?>
</p>
<p>
<strong> Calificación: </strong>
<?php
for(
$i=1;
$i<=5;
$i++
){
if(
$i <=
$resena["calificacion"]
){
echo "★";
}
else{
echo "☆";
}
}
?>
</p>
<p>
<?= nl2br(
htmlspecialchars(
$resena["comentario"]
)
) ?>
</p>
<p
class="fechaResena">
<?= date(

"d/m/Y H:i",

strtotime(
$resena["fecha"]
)
) ?>
</p>
</div>
<?php
}
}
else{
?>
<p> No existen reseñas registradas.</p>
<?php
}
?>
</section>
<!--=====================================================
PIE DE PÁGINA
======================================================-->
<footer>
<p>
<strong> Tienda de Comercio Electrónico </strong>
<p>
© <?= date("Y") ?>
Todos los derechos reservados.
</p>
</footer>
<!--=====================================================
ARCHIVO JAVASCRIPT
======================================================-->
<script
src="script.js">
</script>
</body>
</html>