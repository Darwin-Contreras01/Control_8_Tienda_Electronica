<?php
/*=========================================================
registrarProducto.php
Permite registrar productos en la base de datos TIENDA y
visualizar el listado de productos registrados.
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
PROCESAR FORMULARIO
=========================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*=============================================
    RECUPERAR DATOS
    =============================================*/
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = trim($_POST["precio"]);
    $stock = trim($_POST["stock"]);
    /*=============================================
    VALIDACIONES
    =============================================*/
    if (
        empty($nombre) ||
        empty($descripcion) ||
        empty($precio) ||
        empty($stock)
    ) {
        $mensaje = "Debe completar todos los campos.";
    } elseif (!is_numeric($precio) || $precio <= 0) {
        $mensaje = "El precio debe ser mayor que cero.";
    } elseif (!is_numeric($stock) || $stock < 0) {
        $mensaje = "El stock es incorrecto.";
    } else {
        /*=========================================
        INSERTAR PRODUCTO
        =========================================*/
        $sql = "
        INSERT INTO PRODUCTO (
            nombre,
            descripcion,
            precio,
            stock
        )
        VALUES (
            ?,
            ?,
            ?,
            ?
        )";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "ssdi",
            $nombre,
            $descripcion,
            $precio,
            $stock
        );
        if ($stmt->execute()) {
            $mensaje = "Producto registrado correctamente.";
        } else {
            $mensaje = "Error al registrar el producto.";
        }
        $stmt->close();
    }
}
/*=========================================================
CONSULTAR PRODUCTOS
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
$resultadoProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    /*=========================================================
    VALIDAR FORMULARIO
    =========================================================*/
    function validarProducto() {
        let nombre = document.getElementById("nombre").value.trim();
        let descripcion = document.getElementById("descripcion").value.trim();
        let precio = document.getElementById("precio").value;
        let stock = document.getElementById("stock").value;
        if (nombre == "" || descripcion == "") {
            alert("Debe completar todos los campos.");
            return false;
        }
        if (precio <= 0) {
            alert("El precio debe ser mayor que cero.");
            return false;
        }
        if (stock < 0) {
            alert("El stock debe ser igual o mayor que cero.");
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<!--=====================================================
ENCABEZADO
======================================================-->
<header>
    <h1>Registrar Producto</h1>
</header>
<!--=====================================================
MENÚ PRINCIPAL
======================================================-->
<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="registrarCliente.php">Clientes</a>
    <a href="registrarCompra.php">Compras</a>
</nav>
<!--=====================================================
FORMULARIO
======================================================-->
<section>
    <h2>Nuevo Producto</h2>
    <?php if ($mensaje != "") { ?>
        <div class="mensaje">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php } ?>
    <form method="POST"
          action="registrarProducto.php"
          onsubmit="return validarProducto();">
        <label for="nombre">Nombre</label>
        <input type="text"
               id="nombre"
               name="nombre"
               maxlength="100"
               required>
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion"
                  name="descripcion"
                  rows="4"
                  required></textarea>
        <label for="precio">Precio</label>
        <input type="number"
               id="precio"
               name="precio"
               min="1"
               step="1"
               required>
        <label for="stock">Stock</label>
        <input type="number"
               id="stock"
               name="stock"
               min="0"
               required>
        <div class="botonesFormulario">
            <button type="submit">Guardar Producto</button>
            <button type="reset">Limpiar</button>
        </div>
    </form>
</section>
<!--=====================================================
LISTADO DE PRODUCTOS
======================================================-->
<section>
    <h2>Productos Registrados</h2>
    <?php if ($resultadoProductos && $resultadoProductos->num_rows > 0) { ?>
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
                <?php while ($producto = $resultadoProductos->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $producto["id_producto"] ?></td>
                        <td>
                            <?= htmlspecialchars($producto["nombre"]) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($producto["descripcion"]) ?>
                        </td>
                        <td>
                            $<?= number_format($producto["precio"], 0, ",", ".") ?>
                        </td>
                        <td>
                            <?= $producto["stock"] ?>
                        </td>
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
<div class="botonesFormulario">
    <a href="index.php">
        <button type="button">
            Volver al Inicio
        </button>
    </a>
</div>
</body>
</html>