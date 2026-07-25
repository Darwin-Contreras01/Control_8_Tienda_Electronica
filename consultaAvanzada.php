<?php
/*=========================================================
 consultaAvanzada.php
 Muestra los clientes que poseen más de
 dos compras registradas.
=========================================================*/

require_once(__DIR__ . "/includes/configSesion.php");
/*=========================================================
 CONSULTA AVANZADA
=========================================================*/
$tipoBusqueda=
$_GET["tipoBusqueda"] ??
"cantidad";
$valorBusqueda=
trim($_GET["valorBusqueda"] ??
"2");
if($tipoBusqueda=="cantidad"){

$sql="
SELECT
c.id_cliente,
c.nombre,
c.email,
COUNT(co.id_compra) AS total_compras,
SUM(co.total) AS monto_total
FROM CLIENTE c
INNER JOIN COMPRA co
ON c.id_cliente=co.id_cliente
GROUP BY
c.id_cliente,
c.nombre,
c.email
HAVING COUNT(co.id_compra)>?
ORDER BY total_compras DESC";

$stmt=$conexion->prepare($sql);
$stmt->bind_param(
"i",
$valorBusqueda
);
}
elseif($tipoBusqueda=="nombre"){
$sql="
SELECT
c.id_cliente,
c.nombre,
c.email,
COUNT(co.id_compra) AS total_compras,
SUM(co.total) AS monto_total
FROM CLIENTE c
INNER JOIN COMPRA co
ON c.id_cliente=co.id_cliente
WHERE c.nombre LIKE ?
GROUP BY
c.id_cliente,
c.nombre,
c.email";
$stmt=$conexion->prepare($sql);
$buscar="%".$valorBusqueda."%";
$stmt->bind_param(
"s",
$buscar
);
}
elseif($tipoBusqueda=="correo"){

$sql="
SELECT
c.id_cliente,
c.nombre,
c.email,
COUNT(co.id_compra) AS total_compras,
SUM(co.total) AS monto_total
FROM CLIENTE c
INNER JOIN COMPRA co
ON c.id_cliente=co.id_cliente
WHERE c.email LIKE ?
GROUP BY
c.id_cliente,
c.nombre,
c.email";
$stmt=$conexion->prepare($sql);
$buscar="%".$valorBusqueda."%";
$stmt->bind_param(
"s",
$buscar
);
}
else{
$sql="
SELECT
c.id_cliente,
c.nombre,
c.email,
COUNT(co.id_compra) AS total_compras,
SUM(co.total) AS monto_total
FROM CLIENTE c
INNER JOIN COMPRA co
ON c.id_cliente=co.id_cliente
GROUP BY
c.id_cliente,
c.nombre,
c.email
HAVING SUM(co.total)>=?
ORDER BY monto_total DESC";
$stmt=$conexion->prepare($sql);
$stmt->bind_param(
"d",
$valorBusqueda
);
}
$stmt->execute();
$resultado= $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Avanzada</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Consulta Avanzada</h1>
    <p>Clientes con más de dos compras registradas.</p>
</header>
<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="consultarProductos.php">Productos</a>
    <a href="consultarClientes.php">Clientes</a>
    <a href="registrarCompra.php">Registrar Compra</a>
</nav>
<section>

<form action="consultaAvanzada.php" method="GET">
<label for="tipoBusqueda">Tipo de búsqueda</label>
<select id="tipoBusqueda" name="tipoBusqueda">
<option value="cantidad">Cantidad mínima de compras</option>
<option value="nombre">Nombre del cliente</option>
<option value="correo">Correo electrónico</option>
<option value="monto">Monto comprado</option>
</select>
<label for="valorBusqueda">Valor</label>
<input
type="text"
id="valorBusqueda"
name="valorBusqueda"
placeholder="Ingrese el valor de búsqueda">

<div class="botonesFormulario">
<button type="submit">
Buscar
</button>
</div>
</form>
<h2>Resultado de la Consulta</h2>
<?php if($resultado && $resultado->num_rows > 0){ ?>
<table class="tablaCarrito">
<thead>
<tr>
    <th>ID Cliente</th>
    <th>Nombre</th>
    <th>Correo Electrónico</th>
    <th>Total Compras</th>
    <th>Monto Comprado</th>
</tr>
</thead>
<tbody>
<?php while($fila = $resultado->fetch_assoc()){ ?>
<tr>
<td><?= $fila["id_cliente"] ?></td>
<td><?= htmlspecialchars($fila["nombre"]) ?></td>
<td><?= htmlspecialchars($fila["email"]) ?></td>
<td><?= $fila["total_compras"] ?></td>
<td>$ <?= number_format($fila["monto_total"],0,",",".") ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }else{ ?>
<p>No existen clientes con más de dos compras registradas.</p>
<?php } ?>
</section>
<div class="botonesFormulario">
<a href="registrarCompra.php">
    <button type="button">Registrar Compra</button>
</a>
<a href="index.php">
    <button type="button">Volver al Inicio</button>
</a>
</div>
</body>
</html>