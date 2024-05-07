<?php

class Asistencia
{
    private $id;

    private $idUsuario;

    private $idEvento;

    private $idTarifa;

    private $codigo;

    private $validada;

    public function __construct($codigo, $idUsuario, $idEvento, $idTarifa, $validada, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idEvento = $idEvento;
        $this->idTarifa = $idTarifa;
        $this->codigo = $codigo;
        $this->validada = $validada;
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
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getValidada()
    {
        return $this->validada;
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
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }
    public function setValidada($validada)
    {
        $this->validada = $validada;
    }
}
