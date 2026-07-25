/*=========================================================
script.js
 Funciones JavaScript utilizadas por la aplicación.
=========================================================*/
/*=========================================================
 CONFIRMAR ELIMINACIÓN DEL PRODUCTO
=========================================================*/
function confirmarEliminarProducto(){
    return confirm("¿Desea eliminar este producto del carrito?");
}
/*=========================================================
 CONFIRMAR VACIAR CARRITO
=========================================================*/
function confirmarVaciarCarrito(){
    return confirm("¿Desea vaciar completamente el carrito?");
}
/*=========================================================
 CONFIRMAR REGISTRO DE COMPRA
=========================================================*/
function confirmarCompra(){
    return confirm("¿Confirma el registro de la compra?");
}
/*=========================================================
 VALIDAR PRODUCTO
=========================================================*/
function validarProducto(){
    let nombre=document.getElementById("nombre").value.trim();
    let descripcion=document.getElementById("descripcion").value.trim();
    let precio=document.getElementById("precio").value;
    let stock=document.getElementById("stock").value;
    if(nombre==""){
        alert("Ingrese el nombre del producto.");
        return false;
    }
    if(descripcion==""){
        alert("Ingrese la descripción.");
        return false;
    }
    if(precio==""||parseFloat(precio)<=0){
        alert("El precio debe ser mayor que cero.");
        return false;
    }
    if(stock==""||parseInt(stock)<0){
        alert("El stock debe ser igual o mayor que cero.");
        return false;
    }
    return true;
}
/*=========================================================
 VALIDAR CLIENTE
=========================================================*/
function validarCliente(){
    let nombre=document.getElementById("nombre").value.trim();
    let email=document.getElementById("email").value.trim();
    let direccion=document.getElementById("direccion").value.trim();
    let expresion=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(nombre==""){
        alert("Ingrese el nombre del cliente.");
        return false;
    }
    if(!expresion.test(email)){
        alert("Ingrese un correo electrónico válido.");
        return false;
    }
    if(direccion==""){
        alert("Ingrese la dirección.");
        return false;
    }
    return true;
}
/*=========================================================
 VALIDAR COMPRA
=========================================================*/
function validarCompra(){
    let cliente=document.getElementById("idCliente").value;
    if(cliente==""){
        alert("Debe seleccionar un cliente.");
        return false;
    }
    return confirmarCompra();
}
/*=========================================================
 VALIDAR RESEÑA
=========================================================*/
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
/*=========================================================
BUSCADOR DE PRODUCTOS
=========================================================*/

document.addEventListener("DOMContentLoaded",function(){
    const boton=document.getElementById("btnBuscar");
    const caja=document.getElementById("txtBuscar");
    if(boton){
        boton.addEventListener("click",buscarProductos);
    }
    caja.addEventListener("keyup",function(e){
        if(e.key==="Enter"){
            buscarProductos();
        }
    });
});
function buscarProductos(){
    let texto=document.getElementById("txtBuscar").value.toLowerCase();
    let tarjetas=document.querySelectorAll(".tarjetaProducto");
    tarjetas.forEach(function(tarjeta){
        let contenido=tarjeta.textContent.toLowerCase();
        if(contenido.includes(texto)){
            tarjeta.style.display="flex";
        }else{
            tarjeta.style.display="none";
        }
    });
}