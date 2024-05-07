<?php

class NotificacionSolicitud
{
    private $id;

    private $idUsuario;

    private $idSeguidor;

    private $fecha;

    private $vista;

    public function __construct($idUsuario, $idSeguidor, $fecha, $vista, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idSeguidor = $idSeguidor;
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
    public function getIdSeguidor()
    {
        return $this->idSeguidor;
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
    public function setIdSeguidor($idSeguidor)
    {
        $this->idSeguidor = $idSeguidor;
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
