<?php

class NotificacionEvento
{
    private $id;

    private $idUsuario;

    private $idEvento;

    private $fecha;

    private $vista;

    public function __construct($idUsuario, $idEvento, $fecha, $vista, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idEvento = $idEvento;
        $this->fecha = $fecha;
        $this->vista = $vista;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    public function getIdEvento()
    {
        return $this->idEvento;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getVista()
    {
        return $this->vista;
    }

    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
    public function setIdEvento($idEvento)
    {
        $this->idEvento = $idEvento;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setVista($vista)
    {
        $this->vista = $vista;
    }
}
