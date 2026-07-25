<?php
/*=========================================================
registrarPedido.php
 Controla el proceso de registro de un pedido.
 Verifica la información necesaria y deriva el
 proceso al módulo registrarCompra.php.
=========================================================*/

require_once(__DIR__ . "/includes/configSesion.php");
/*=========================================================
 VERIFICAR CARRITO
=========================================================*/
if(!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])){

    $_SESSION["mensaje"] = "Debe agregar productos al carrito antes de registrar un pedido.";
    header("Location: index.php");
    exit();
}
/*=========================================================
 VERIFICAR CLIENTE
=========================================================*/
if(!isset($_POST["idCliente"]) || empty($_POST["idCliente"])){

    $_SESSION["mensaje"] = "Debe seleccionar un cliente para continuar.";
    header("Location: registrarCompra.php");
    exit();
}
/*=========================================================
 GUARDAR CLIENTE EN LA SESIÓN
=========================================================*/
$_SESSION["idCliente"] = (int)$_POST["idCliente"];

/*=========================================================
 REDIRIGIR AL REGISTRO DE LA COMPRA
=========================================================*/
header("Location: registrarCompra.php");
exit();
?>