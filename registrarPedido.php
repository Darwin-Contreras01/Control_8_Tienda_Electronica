<?php
/*=========================================================
registrarPedido.php
 Controla el proceso de registro de un pedido.
 Verifica la información necesaria y deriva el
 proceso al módulo registrarCompra.php.

Modificación realizada (Semana 8):
 Se actualizó la documentación del archivo para
 identificar la funcionalidad encargada del
 registro de pedidos dentro del proyecto
 Control_8 y facilitar su revisión mediante
 Pull Request en GitHub.
=========================================================*/
// Documentación revisada para la entrega final del Control 8.

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