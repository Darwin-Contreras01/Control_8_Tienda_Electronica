<?php
/*=========================================================
consultarClientes.php
 Muestra todos los clientes registrados en la base TIENDA.
=========================================================*/

require_once(__DIR__ . "/includes/configSesion.php");
/*=========================================================
 CONSULTAR CLIENTES
=========================================================*/
$sql = "SELECT id_cliente, nombre, email, direccion
        FROM CLIENTE
        ORDER BY nombre";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Clientes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Consulta de Clientes</h1>
    <p>Listado de clientes registrados en la base de datos.</p>
</header>
<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="registrarProducto.php">Productos</a>
    <a href="registrarCliente.php">Registrar Cliente</a>
    <a href="registrarCompra.php">Registrar Compra</a>
</nav>
<section>
    <h2>Clientes Registrados</h2>
    <?php if($resultado && $resultado->num_rows > 0){ ?>
    <table class="tablaCarrito">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
        <?php while($cliente = $resultado->fetch_assoc()){ ?>
            <tr>
                <td><?= $cliente["id_cliente"] ?></td>
                <td><?= htmlspecialchars($cliente["nombre"]) ?></td>
                <td><?= htmlspecialchars($cliente["email"]) ?></td>
                <td><?= htmlspecialchars($cliente["direccion"]) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php }else{ ?>
        <p>No existen clientes registrados.</p>
    <?php } ?>
</section>
<div class="botonesFormulario">
    <a href="registrarCliente.php">
        <button type="button">Nuevo Cliente</button>
    </a>
    <a href="index.php">
        <button type="button">Volver al Inicio</button>
    </a>
</div>
</body>
</html>