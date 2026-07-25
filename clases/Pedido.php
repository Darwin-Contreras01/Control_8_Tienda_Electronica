<?php
/*=========================================================
Pedido: Representa un pedido realizado por un cliente
en la tienda de comercio electrónico.
=========================================================*/
class Pedido
{
    /*=====================================================
    ATRIBUTOS
    =====================================================*/
    private $idCliente;
    private $productos;
    private $cantidadTotal;
    private $total;
    private $tipoEntrega;
    private $direccionEntrega;
    private $observaciones;
    private $fecha;
    /*=====================================================
    CONSTRUCTOR
    =====================================================*/
    public function __construct(
        $idCliente,
        $productos,
        $tipoEntrega,
        $direccionEntrega,
        $observaciones
    ){
        $this->idCliente = $idCliente;
        $this->productos = $productos;
        $this->tipoEntrega = $tipoEntrega;
        $this->direccionEntrega = $direccionEntrega;
        $this->observaciones = $observaciones;
        $this->fecha = date("Y-m-d H:i:s");
        $this->cantidadTotal =
        $this->calcularCantidadTotal();
        $this->total =
        $this->calcularTotalCompra();
    }
    /*=====================================================
    CALCULAR CANTIDAD TOTAL
    =====================================================*/
    private function calcularCantidadTotal()
    {
        $cantidad = 0;
        foreach($this->productos as $producto){
            $cantidad +=
            $producto["cantidad"];
        }
        return $cantidad;
    }
    /*=====================================================
    CALCULAR TOTAL DE LA COMPRA
    =====================================================*/
    private function calcularTotalCompra()
    {
        $total = 0;
        foreach($this->productos as $producto){
        $subtotal =
        $producto["precio"] *
        $producto["cantidad"];
        $total +=
        $subtotal;
            $total +=
            $subtotal;
        }
        return $total;
    }
    /*=====================================================
    OBTENER RESUMEN DEL PEDIDO
    =====================================================*/
    public function obtenerResumen()
    {
        $texto = "";
        foreach($this->productos as $producto){
            $texto .=
            $producto["nombre"] .
            " | Cantidad: " .
            $producto["cantidad"] .
            " | Precio: $" .
            number_format(
                $producto["precio"],
                0,
                ",",
                "."
            ) .
            PHP_EOL;
        }
        return $texto;
    }
    /*=====================================================
    GETTERS
    =====================================================*/
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function getProductos()
    {
        return $this->productos;
    }
    public function getCantidadTotal()
    {
        return $this->cantidadTotal;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getTipoEntrega()
    {
        return $this->tipoEntrega;
    }
    public function getDireccionEntrega()
    {
        return $this->direccionEntrega;
    }
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    /*=====================================================
    SETTERS
    =====================================================*/
    public function setTipoEntrega($tipoEntrega)
    {
        $this->tipoEntrega = $tipoEntrega;
    }
    public function setDireccionEntrega($direccionEntrega)
    {
        $this->direccionEntrega = $direccionEntrega;
    }
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }
    /*=====================================================
    DEVOLVER LOS DATOS DEL PEDIDO
    =====================================================*/
    public function obtenerDatos()
    {
        return [
            "idCliente" => $this->idCliente,
            "fecha" => $this->fecha,
            "cantidadTotal" => $this->cantidadTotal,
            "total" => $this->total,
            "tipoEntrega" => $this->tipoEntrega,
            "direccionEntrega" => $this->direccionEntrega,
            "observaciones" => $this->observaciones
        ];
    }
}
?>