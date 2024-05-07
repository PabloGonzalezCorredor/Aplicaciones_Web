<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/Asistencia.php';

class AsistenciaDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerAsistenciasUsuario($idUsuario)
    {        
        $query = "SELECT * FROM asistencia A WHERE A.usuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $asistencia = new Asistencia($fila['codigo'], $fila['usuario'], $fila['evento'], $fila['tarifa'], $fila['validada'],$fila['id']);
                $result[] = $asistencia;
            }
        }
        return $result;   
    }

    public function obtenerAsistenciasEvento($idEvento)
    {        
        $query = "SELECT * FROM asistencia A WHERE A.evento='". mysqli_real_escape_string($this->mysqli, $idEvento) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $asistencia = new Asistencia($fila['codigo'], $fila['usuario'], $fila['evento'], $fila['tarifa'], $fila['validada'],$fila['id']);
                $result[] = $asistencia;
            }
        }
        return $result;   
    }

    public function obtenerAsistencia($idUsuario, $idEvento)
    {        
        $query = "SELECT * FROM asistencia A WHERE A.usuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "' AND A.evento='". mysqli_real_escape_string($this->mysqli, $idEvento) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) > 0) { 
            $fila = $rs[0];
            $result = new Asistencia($fila['codigo'], $fila['usuario'], $fila['evento'], $fila['tarifa'], $fila['validada'], $fila['id'] );
        }
        return $result;   
    }

    public function obtenerAsistenciaPorId($id)
    {        
        $query = "SELECT * FROM asistencia A WHERE A.id='". mysqli_real_escape_string($this->mysqli, $id) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) > 0) { 
            $fila = $rs[0];
            $result = new Asistencia($fila['codigo'], $fila['usuario'], $fila['evento'], $fila['tarifa'], $fila['validada'], $fila['id']);
        }
        return $result;   
    }

    public function comprobarEntrada($codigo, $idEvento)
    {        
        $query = "SELECT * FROM asistencia A WHERE A.codigo='". mysqli_real_escape_string($this->mysqli, $codigo) . "' AND A.evento='". mysqli_real_escape_string($this->mysqli, $idEvento) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) > 0) { 
            $fila = $rs[0];
            $result = new Asistencia($fila['codigo'], $fila['usuario'], $fila['evento'], $fila['tarifa'], $fila['validada'], $fila['id']);
        }
        return $result;   
    }

    public function inserta($asistencia)
    {
        $query="INSERT INTO asistencia(usuario, evento, tarifa, codigo, validada) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $asistencia->getIdUsuario()) . "',
            '". mysqli_real_escape_string($this->mysqli, $asistencia->getIdEvento()) . "',
            '". mysqli_real_escape_string($this->mysqli, $asistencia->getIdTarifa()) . "',
            '". mysqli_real_escape_string($this->mysqli, $asistencia->getCodigo()) . "',
            '". mysqli_real_escape_string($this->mysqli, $asistencia->getValidada()) . "'
        )";
        $this->ejecutarComando($query);
    }

    public function actualiza($idAsistencia)
    {
        $query = "UPDATE asistencia
        SET validada = 1
        WHERE id = '" . $idAsistencia . "'";
        
        $rs = $this->ejecutarComando($query);
    }

    public function borra($asistencia)
    {
        $query = "DELETE FROM asistencia WHERE id = '". $asistencia->getId() . "')";
        $rs = $this->ejecutarComando($query);
    }
}
