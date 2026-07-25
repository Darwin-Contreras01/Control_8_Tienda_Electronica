<?php
/*=========================================================
mostrarCompras.php
Muestra todas las compras registradas
en la base de datos.
=========================================================*/
require_once(__DIR__."/includes/configSesion.php");
/*=========================================================
CONSULTA DE COMPRAS
=========================================================*/
$sql="
SELECT
co.id_compra,
cl.nombre AS cliente,
pr.nombre AS producto,
co.cantidad,
co.total,
co.fecha
FROM COMPRA co
INNER JOIN CLIENTE cl
ON co.id_cliente=cl.id_cliente
INNER JOIN PRODUCTO pr
ON co.id_producto=pr.id_producto
ORDER BY co.fecha DESC,
co.id_compra DESC
";
$resultadoCompras=
$conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width,initial-scale=1.0">
<title>Mostrar Compras</title>
<link
rel="stylesheet"
href="styles.css">
</head>
<body>
<!--=====================================================
ENCABEZADO
======================================================-->
<header>
<h1>Mostrar Compras</h1>
<p>Listado de todas las compras registradas en MySQL.</p>
</header>
<!--=====================================================
MENÚ
======================================================-->
<nav class="menuPrincipal">
<a href="index.php">Inicio</a>
<a href="consultarProductos.php">Productos</a>
<a href="consultarClientes.php">Clientes</a>
<a href="registrarCompra.php">Registrar Compra</a>
<a href="consultaAvanzada.php">Consulta Avanzada</a>
</nav>
<!--=====================================================
COMPRAS REGISTRADAS
======================================================-->
<section>
<h2>Compras Registradas</h2>
<p>Listado completo de las compras almacenadas en la base de datos.</p>
<?php
if(
$resultadoCompras &&
$resultadoCompras->num_rows>0
){
?>
<table
class="tablaCarrito">
<thead>
<tr>
<th>ID Compra</th>
<th>Cliente</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Total</th>
<th>Fecha</th>
</tr>
</thead>
<tbody>
<?php
while(
$compra=
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
<p>
No existen compras registradas
en la base de datos.
</p>
<?php
}
?>
</section>
<!--=====================================================
BOTONES
======================================================-->
<div
class="botonesFormulario">
<a
href="registrarCompra.php">
<button
type="button">
Registrar Compra
</button>
</a>
<a
href="index.php">
<button
type="button">
Volver al Inicio
</button>
</a>
</div>
<!--=====================================================
PIE DE PÁGINA
======================================================-->
<footer>
<p>
<strong>
Tienda de Comercio Electrónico
</strong>
</p>
<p>
© <?= date("Y") ?>
Todos los derechos reservados.
</p>
</footer>
</body>
</html>