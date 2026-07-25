<?php
/*=========================================================
 buscarPedido.php
 Permite buscar compras registradas según
 el nombre del cliente o el ID de la compra.
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");
$busqueda = "";
$resultado = null;
/*=========================================================
 PROCESAR BÚSQUEDA
=========================================================*/
if(isset($_GET["buscar"])){
    $busqueda = trim($_GET["busqueda"]);
    $sql = "SELECT
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
            WHERE cl.nombre LIKE ?
               OR co.id_compra LIKE ?
            ORDER BY co.fecha DESC";

    $stmt = $conexion->prepare($sql);
    $textoBusqueda = "%" . $busqueda . "%";
    $stmt->bind_param("ss", $textoBusqueda, $textoBusqueda);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Pedido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Buscar Pedido</h1>
    <p>Consulte las compras registradas por cliente o número de compra.</p>
</header>

<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="registrarCompra.php">Registrar Compra</a>
    <a href="consultaAvanzada.php">Consulta Avanzada</a>
</nav>
<section>
<h2>Búsqueda de Compras</h2>
<form action="buscarPedido.php" method="GET">
<label for="busqueda">Cliente o ID de Compra</label>
<input
    type="text"
    id="busqueda"
    name="busqueda"
    value="<?= htmlspecialchars($busqueda) ?>"
    placeholder="Ingrese nombre o ID">
<button type="submit" name="buscar">Buscar</button>
</form>
</section>
<?php if($resultado){ ?>
<section>
<h2>Resultados</h2>
<?php if($resultado->num_rows > 0){ ?>
<table class="tablaCarrito">
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
<?php while($fila = $resultado->fetch_assoc()){ ?>
<tr>
    <td><?= $fila["id_compra"] ?></td>
    <td><?= htmlspecialchars($fila["cliente"]) ?></td>
    <td><?= htmlspecialchars($fila["producto"]) ?></td>
    <td><?= $fila["cantidad"] ?></td>
    <td>$ <?= number_format($fila["total"],0,",",".") ?></td>
    <td><?= date("d/m/Y H:i", strtotime($fila["fecha"])) ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }else{ ?>
<p>No se encontraron compras para la búsqueda realizada.</p>
<?php } ?>
</section>
<?php } ?>
<div class="botonesFormulario">
    <a href="index.php"><button type="button">Volver al Inicio</button></a>
</div>
</body>
</html>