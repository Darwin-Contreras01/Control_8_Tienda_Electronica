<?php
/*=========================================================
procesarResena.php
 Registra una reseña sobre un producto y muestra
 las reseñas almacenadas en la base de datos.
=========================================================*/

require_once(__DIR__."/includes/configSesion.php");
$mensaje="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $cliente=trim($_POST["cliente"]);
    $idProducto=(int)$_POST["idProducto"];
    $calificacion=(int)$_POST["calificacion"];
    $comentario=trim($_POST["comentario"]);
    if(empty($cliente)||$idProducto<=0||empty($comentario)){
        $mensaje="Debe completar todos los campos.";
    }elseif($calificacion<1||$calificacion>5){
        $mensaje="La calificación debe estar entre 1 y 5.";
    }else{
        $sql="INSERT INTO RESENA(cliente,id_producto,calificacion,comentario,fecha)
              VALUES(?,?,?,?,NOW())";
        $stmt=$conexion->prepare($sql);
        $stmt->bind_param("siis",$cliente,$idProducto,$calificacion,$comentario);
        if($stmt->execute()){
            $mensaje="Reseña registrada correctamente.";
        }else{
            $mensaje="Error al registrar la reseña.";
        }
        $stmt->close();
    }
}
/*=========================================================
 CONSULTAR PRODUCTOS
=========================================================*/
$sqlProductos="SELECT id_producto,nombre
               FROM PRODUCTO
               ORDER BY nombre";

$resultadoProductos=$conexion->query($sqlProductos);
/*=========================================================
 CONSULTAR RESEÑAS
=========================================================*/
$sqlResenas="SELECT
                r.id_resena,
                r.cliente,
                p.nombre AS producto,
                r.calificacion,
                r.comentario,
                r.fecha
             FROM RESENA r
             INNER JOIN PRODUCTO p
                ON r.id_producto=p.id_producto
             ORDER BY r.fecha DESC";
$resultadoResenas=$conexion->query($sqlResenas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Reseña</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    function validarResena(){
        let cliente=document.getElementById("cliente").value.trim();
        let producto=document.getElementById("idProducto").value;
        let comentario=document.getElementById("comentario").value.trim();
        if(cliente==""){
            alert("Ingrese el nombre del cliente.");
            return false;
        }
        if(producto==""){
            alert("Seleccione un producto.");
            return false;
        }
        if(comentario==""){
            alert("Ingrese un comentario.");
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<header>
    <h1>Reseñas de Productos</h1>
    <p>Registro y consulta de reseñas realizadas por los clientes.</p>
</header>
<nav class="menuPrincipal">
    <a href="index.php">Inicio</a>
    <a href="registrarProducto.php">Productos</a>
    <a href="registrarCompra.php">Compras</a>
</nav>
<section>
<h2>Nueva Reseña</h2>
<?php if($mensaje!=""){ ?>
<div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
<?php } ?>
<form action="procesarResena.php" method="POST" onsubmit="return validarResena();">
<label for="cliente">Cliente</label>
<input type="text" id="cliente" name="cliente" maxlength="100" required>
<label for="idProducto">Producto</label>
<select id="idProducto" name="idProducto" required>
    <option value="">Seleccione un producto</option>
    <?php while($producto=$resultadoProductos->fetch_assoc()){ ?>
        <option value="<?= $producto["id_producto"] ?>">
            <?= htmlspecialchars($producto["nombre"]) ?>
        </option>
    <?php } ?>
</select>
<label for="calificacion">Calificación</label>
<select id="calificacion" name="calificacion">
    <option value="5">5</option>
    <option value="4">4</option>
    <option value="3">3</option>
    <option value="2">2</option>
    <option value="1">1</option>
</select>
<label for="comentario">Comentario</label>
<textarea id="comentario" name="comentario" rows="4" required></textarea>
<div class="botonesFormulario">
    <button type="submit">Guardar Reseña</button>
    <button type="reset">Limpiar</button>
</div>
</form>
</section>
<section>
<h2>Reseñas Registradas</h2>
<?php if($resultadoResenas && $resultadoResenas->num_rows>0){ ?>
<table class="tablaCarrito">
<thead>
<tr>
    <th>Cliente</th>
    <th>Producto</th>
    <th>Calificación</th>
    <th>Comentario</th>
    <th>Fecha</th>
</tr>
</thead>
<tbody>
<?php while($resena=$resultadoResenas->fetch_assoc()){ ?>
<tr>
    <td><?= htmlspecialchars($resena["cliente"]) ?></td>
    <td><?= htmlspecialchars($resena["producto"]) ?></td>
    <td><?= $resena["calificacion"] ?></td>
    <td><?= htmlspecialchars($resena["comentario"]) ?></td>
    <td><?= date("d/m/Y H:i",strtotime($resena["fecha"])) ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php }else{ ?>
<p>No existen reseñas registradas.</p>
<?php } ?>
</section>
<div class="botonesFormulario">
    <a href="index.php"><button type="button">Volver al Inicio</button></a>
</div>
</body>
</html>