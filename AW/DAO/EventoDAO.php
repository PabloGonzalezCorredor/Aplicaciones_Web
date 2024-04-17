<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/Evento.php';

class EventoDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerEventoPorId($eventoId)
    {
        $query = "SELECT * FROM eventos E WHERE E.id='". mysqli_real_escape_string($this->mysqli, $eventoId) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs) {
            $fila = $rs[0];

            if (count($rs) == 1) {
                $result = new Evento($fila['idPromotor'], $fila['imagen'], $fila['nombreEvento'], $fila['fecha'], $fila['horaIni'], $fila['horaFin'], $fila['localizacion'], $fila['id']);
            }
        }
        return $result;
    }

    public function existeEvento($nombreEvento, $fecha)
    {
        $query = "SELECT * FROM eventos E WHERE E.nombreEvento='". mysqli_real_escape_string($this->mysqli, $nombreEvento) . "' AND E.fecha='". mysqli_real_escape_string($this->mysqli, $fecha) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) > 0) {
            $result = true;
        }
        return $result;
    }

    //Obtiene todos los eventos proximos
    public function obtenerEventosSiguientes()
    {        
        $query = "SELECT * FROM eventos E WHERE E.fecha >= CURDATE()";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $evento = new Evento($fila['idPromotor'], $fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion'], $fila['id']);
                $result[] = $evento;
            }
        }
        return $result;   
    }

    //Obtiene todos los eventos pasados
    public function obtenerEventosPasados()
    {        
        $query = "SELECT * FROM eventos E WHERE E.fecha < CURDATE()";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $evento = new Evento($fila['idPromotor'], $fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion'], $fila['id']);
                $result[] = $evento;
            }
        }
        return $result;   
    }

    //Obtiene todos los eventos proximos que posee un promotor
    public function obtenerEventosSiguientesPromotor($idPromotor)
    {
        $query = "SELECT * FROM eventos E WHERE E.fecha >= CURDATE() AND E.idPromotor = '". $idPromotor . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $evento = new Evento($fila['idPromotor'], $fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion'], $fila['id']);
                $result[] = $evento;
            }
        }
        return $result;  
    }

    //Obtiene todos los eventos pasados que posee un promotor
    public function obtenerEventosPasadosPromotor($idPromotor)
    {
        $query = "SELECT * FROM eventos E WHERE E.fecha < CURDATE() AND E.idPromotor = '". $idPromotor . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $evento = new Evento($fila['idPromotor'], $fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion'], $fila['id']);
                $result[] = $evento;
            }
        }
        return $result;  
    }
    

    public function inserta($evento)
    {
        $query="INSERT INTO eventos(idPromotor, imagen, nombreEvento, fecha, horaIni, horaFin, localizacion) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $evento->getIdPromotor()) . "',
            '". $evento->getImagen() . "',
            '". mysqli_real_escape_string($this->mysqli, $evento->getNombreEvento()) . "',
            '". mysqli_real_escape_string($this->mysqli, $evento->getFecha()) . "',
            '". mysqli_real_escape_string($this->mysqli, $evento->getHoraIni()) . "',
            '". mysqli_real_escape_string($this->mysqli, $evento->getHoraFin()) . "',
            '". mysqli_real_escape_string($this->mysqli, $evento->getLocalizacion()) . "'
        )";
        $rs = $this->ejecutarComando($query);
        $idEventoInsertado = mysqli_insert_id($this->mysqli); //Obtener el id del evento insertado

        return $idEventoInsertado;
    }

    public function actualiza($evento)
    {
        $query = "UPDATE eventos 
        SET idPromotor = '" . mysqli_real_escape_string($this->mysqli, $evento->getIdPromotor()) . "',
            imagen = '" . $evento->getImagen() . "',
            nombreEvento = '" . mysqli_real_escape_string($this->mysqli, $evento->getNombreEvento()) . "',
            fecha = '" . mysqli_real_escape_string($this->mysqli, $evento->getFecha()) . "',
            horaIni = '" . mysqli_real_escape_string($this->mysqli, $evento->getHoraIni()) . "',
            horaFin = '" . mysqli_real_escape_string($this->mysqli, $evento->getHoraFin()) . "',
            localizacion = '" . mysqli_real_escape_string($this->mysqli, $evento->getLocalizacion()) . "'
        WHERE id = '" . $evento->getId() . "'";
        
        $rs = $this->ejecutarComando($query);
    }

    public function borra($evento)
    {
        $query = "DELETE FROM eventos WHERE id = '". $evento->getId() . "'";
        $rs = $this->ejecutarComando($query);
    }
}
