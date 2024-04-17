<?php

class Asistencia
{
    private $id;

    private $idUsuario;

    private $idEvento;

    private $idTarifa;

    public function __construct($id, $idUsuario, $idEvento, $idTarifa)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idEvento = $idEvento;
        $this->idTarifa = $idTarifa;
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
    public function getIdTarifa()
    {
        return $this->idTarifa;
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
    public function setIdTarifa($idTarifa)
    {
        $this->idTarifa = $idTarifa;
    }
}
