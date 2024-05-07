<?php

class Seguido
{
    private $idSeguidor;

    private $idSeguido;

    public function __construct($idSeguidor, $idSeguido)
    {
        $this->idSeguidor = $idSeguidor;
        $this->idSeguido = $idSeguido;
    }

    //Getters
    public function getIdSeguidor()
    {
        return $this->idSeguidor;
    }
    public function getIdSeguido()
    {
        return $this->idSeguido;
    }

    //Setters
    public function setIdSeguidor($idSeguidor)
    {
        $this->idSeguidor = $idSeguidor;
    }
    public function setIdSeguido($idSeguido)
    {
        $this->idSeguido = $idSeguido;
    }
}
