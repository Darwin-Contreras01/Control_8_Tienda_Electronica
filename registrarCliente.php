<?php
/*=========================================================
registrarCliente.php
Permite registrar clientes en la base
de datos TIENDA y visualizar el listado
de clientes registrados.
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
if($_SERVER["REQUEST_METHOD"]=="POST"){
    /*=============================================
    RECUPERAR DATOS
    =============================================*/
    $nombre =
    trim($_POST["nombre"]);
    $email =
    trim($_POST["email"]);
    $direccion =
    trim($_POST["direccion"]);
    /*=============================================
    VALIDACIONES
    =============================================*/
    if(
        empty($nombre) ||
        empty($email) ||
        empty($direccion)
    ){
        $mensaje =
        "Debe completar todos los campos.";
    }
    elseif(
        !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
        )
    ){
        $mensaje =
        "Debe ingresar un correo electrónico válido.";
    }
    else{
        /*=========================================
        INSERTAR CLIENTE
        =========================================*/
        $sql = "
        INSERT INTO CLIENTE(
        nombre,
        email,
        direccion
        )
        VALUES(
        ?,
        ?,
        ?
        )
        ";
        $stmt =
        $conexion->prepare($sql);
        $stmt->bind_param(
        "sss",
        $nombre,
        $email,
        $direccion
        );
        if($stmt->execute()){
            $mensaje =
            "Cliente registrado correctamente.";
        }
        else{
            $mensaje =
            "Error al registrar el cliente.";
        }
        $stmt->close();
    }
}
/*=========================================================
CONSULTAR CLIENTES
=========================================================*/
$sqlClientes = "
SELECT
id_cliente,
nombre,
email,
direccion
FROM CLIENTE
ORDER BY nombre
";
$resultadoClientes =
$conexion->query($sqlClientes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width, initial-scale=1.0">
<title> Registrar Cliente </title>
<link
rel="stylesheet"
href="styles.css">
<script>
function validarCliente(){
    let nombre =
    document.getElementById("nombre").value.trim();
    let email =
    document.getElementById("email").value.trim();
    let direccion =
    document.getElementById("direccion").value.trim();
    if(
        nombre=="" ||
        email=="" ||
        direccion==""
    ){
        alert(
        "Debe completar todos los campos."
        );
        return false;
    }
    let expresion =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(
    !expresion.test(email)
    ){
        alert(
        "Correo electrónico incorrecto."
        );
        return false;
    }
    return true;
}
</script>
</head>
<body>
<header>
<h1> Registrar Cliente </h1>
</header>
<nav class="menuPrincipal">
<a href="index.php">
Inicio
</a>

<a href="registrarProducto.php">
Productos
</a>
<a href="registrarCompra.php">
Compras
</a>
</nav>
<section>
<h2> Nuevo Cliente </h2>
<?php
if($mensaje!=""){
?>
<div class="mensaje">
<?= htmlspecialchars($mensaje) ?>
</div>
<?php
}
?>
<form
method="POST"
action="registrarCliente.php"
onsubmit="return validarCliente();"
>
<label> Nombre </label>
<input
type="text"
id="nombre"
name="nombre"
maxlength="100"
required>
<label> Correo Electrónico </label>
<input
type="email"
id="email"
name="email"
maxlength="100"
required>
<label> Dirección </label>
<input
type="text"
id="direccion"
name="direccion"
maxlength="200"
required>
<div
class="botonesFormulario">
<button
type="submit">
Guardar Cliente
</button>
<button
type="reset">
Limpiar
</button>
</div>
</form>
</section>
<section>
<h2> Clientes Registrados </h2>
<?php
if(
$resultadoClientes &&
$resultadoClientes->num_rows>0
){
?>
<table
class="tablaCarrito">
<tr>
<th> ID </th>
<th> Nombre </th>
<th> Correo </th>
<th> Dirección </th>
</tr>
<?php
while(
$cliente =
$resultadoClientes->fetch_assoc()
){
?>
<tr>
<td>
<?= $cliente["id_cliente"] ?>
</td>
<td>
<?= htmlspecialchars(
$cliente["nombre"]
) ?>
</td>
<td>
<?= htmlspecialchars(
$cliente["email"]
) ?>
</td>
<td>
<?= htmlspecialchars(
$cliente["direccion"]
) ?>
</td>
</tr>
<?php
}
?>
</table>
<?php
}
else{
?>
<p> No existen clientes registrados. </p>
<?php
}
?>
</section>
<br>
<a href="index.php">
<button> Volver al Inicio </button>
</a>
</body>
</html>