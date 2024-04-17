<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/Seguido.php';

class SeguidoresDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function comprueba($idSeguidor, $idSeguido){
        $query = "SELECT * FROM seguidores S WHERE S.seguidor='". mysqli_real_escape_string($this->mysqli, $idSeguidor) . "' AND S.seguido='". mysqli_real_escape_string($this->mysqli, $idSeguido) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) > 0) {
            $result = true;
        } 
        return $result;
    }

    public function obtenerSeguidores($idUsuario)
    {        
        $query = "SELECT * FROM seguidores WHERE seguido =  '". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $seguido = new Seguido($fila['seguidor'], $fila['seguido']);
                $result[] = $seguido;
            }
        }
        return $result;   
    }

    public function obtenerSeguidos($idUsuario)
    {        
        $query = "SELECT * FROM seguidores WHERE seguidor =  '". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $seguido = new Seguido($fila['seguidor'], $fila['seguido']);
                $result[] = $seguido;
            }
        }
        return $result;   
    }

    public function inserta($idSeguidor, $idSeguido)
    {
        $query="INSERT INTO seguidores(seguidor, seguido) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $idSeguidor) . "',
            '". mysqli_real_escape_string($this->mysqli, $idSeguido) . "'
        )";
        $rs = $this->ejecutarComando($query);
    }

    public function borra($idSeguidor, $idSeguido)
    {
        $query = "DELETE FROM seguidores WHERE 
        seguidor = '". mysqli_real_escape_string($this->mysqli, $idSeguidor) . "' AND 
        seguido = '". mysqli_real_escape_string($this->mysqli, $idSeguido) . "'";
        $rs = $this->ejecutarComando($query);
    }
    
}
