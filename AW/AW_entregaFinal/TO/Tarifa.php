<?php

class Tarifa
{
    private $id;

    private $idEvento;

    private $informacion;

    private $precio;

    private $consumiciones;

    private $cantidad;

    public function __construct($idEvento, $informacion, $precio, $consumiciones, $cantidad, $id = null)
    {
        $this->id = $id;
        $this->idEvento = $idEvento;
        $this->informacion = $informacion;
        $this->precio = $precio;
        $this->consumiciones = $consumiciones;
        $this->cantidad = $cantidad;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getIdEvento()
    {
        return $this->idEvento;
    }
    public function getInformacion()
    {
        return $this->informacion;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getConsumiciones()
    {
        return $this->consumiciones;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }
    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setIdEvento($idEvento)
    {
        $this->idEvento = $idEvento;
    }
    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setConsumiciones($consumiciones)
    {
        $this->consumiciones = $consumiciones;
    }
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }
}
