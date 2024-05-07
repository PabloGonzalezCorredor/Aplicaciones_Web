<?php

class Evento
{

    private $id;

    private $idPromotor;

    private $imagen;

    private $nombreEvento;

    private $fecha;

    private $horaIni;

    private $horaFin;

    private $localizacion;

    public function __construct($idPromotor, $imagen, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $id = null)
    {
        $this->id = $id;
        $this->idPromotor = $idPromotor;
        $this->imagen = $imagen;
        $this->nombreEvento = $nombreEvento;
        $this->fecha = $fecha;
        $this->horaIni = $horaIni;
        $this->horaFin = $horaFin;
        $this->localizacion = $localizacion;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getIdPromotor()
    {
        return $this->idPromotor;
    }
    public function getImagen()
    {
        return $this->imagen;
    }
    public function getNombreEvento()
    {
        return $this->nombreEvento;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHoraIni()
    {
        return $this->horaIni;
    }
    public function getHoraFin()
    {
        return $this->horaFin;
    }
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    //Setters
    public function setId()
    {
        $this->id = $id;
    }
    public function setIdPromotor()
    {
        $this->idPromotor = $idPromotor;
    }
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    public function setNombreEvento($nombreEvento)
    {
        $this->nombreEvento = $nombreEvento;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setHoraIni($horaIni)
    {
        $this->horaIni = $horaIni;
    }
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;
    }
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;
    }
}
