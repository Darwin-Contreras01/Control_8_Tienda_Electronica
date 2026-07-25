<?php
/*=========================================================
consultarProductos.php
Muestra todos los productos registrados en la base de datos TIENDA.
=========================================================*/
/*=========================================================
CONFIGURACIÓN GENERAL
=========================================================*/
require_once(__DIR__ . "/includes/configSesion.php");

/*=========================================================
CONSULTAR PRODUCTOS
=========================================================*/
$sql = "
SELECT
    id_producto,
    nombre,
    descripcion,
    precio,
    stock
FROM PRODUCTO
ORDER BY nombre
";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Productos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!--=====================================================
ENCABEZADO
======================================================-->
<header>
    <h1>Consulta de Productos</h1>
    <p>Listado de productos registrados en la base de datos.</p>
</header>
<!--=====================================================
MENÚ
======================================================-->
<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="registrarProducto.php">Registrar Producto</a>
    <a href="registrarCliente.php">Registrar Cliente</a>
    <a href="registrarCompra.php">Registrar Compra</a>
</nav>
<!--=====================================================
TABLA DE PRODUCTOS
======================================================-->
<section>
    <h2>Productos Registrados</h2>
    <?php if ($resultado && $resultado->num_rows > 0) { ?>
        <table class="tablaCarrito">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $producto["id_producto"] ?></td>
                        <td><?= htmlspecialchars($producto["nombre"]) ?></td>
                        <td><?= htmlspecialchars($producto["descripcion"]) ?></td>
                        <td>$<?= number_format($producto["precio"], 0, ",", ".") ?></td>
                        <td><?= $producto["stock"] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No existen productos registrados.</p>
    <?php } ?>
</section>
<!--=====================================================
BOTONES
======================================================-->
<br>
<div class="botonesFormulario">
    <a href="registrarProducto.php">
        <button type="button">Nuevo Producto</button>
    </a>
    <a href="index.php">
        <button type="button">Volver al Inicio</button>
    </a>
</div>
</body>
</html>