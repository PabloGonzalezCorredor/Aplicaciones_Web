<?php

class NotificacionAsistencia
{
    private $id;

    private $idUsuario;

    private $idAsistencia;

    private $fecha;

    private $vista;

    public function __construct($idUsuario, $idAsistencia, $fecha, $vista, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idAsistencia = $idAsistencia;
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
    public function getIdAsistencia()
    {
        return $this->idAsistencia;
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
    public function setIdAsistencia($idAsistencia)
    {
        $this->idAsistencia = $idAsistencia;
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
