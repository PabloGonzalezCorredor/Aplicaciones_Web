<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/Tarifa.php';

class TarifaDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerTarifasEvento($idEvento)
    {        
        $query = "SELECT * FROM tarifas T WHERE T.idEvento='". mysqli_real_escape_string($this->mysqli, $idEvento) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $tarifa = new Tarifa($fila['idEvento'], $fila['informacion'], $fila['precio'], $fila['consumiciones'], $fila['id']);
                $result[] = $tarifa;
            }
        }
        return $result;   
    }

    public function inserta($tarifa)
    {
        $query="INSERT INTO tarifas(idEvento, informacion, precio, consumiciones) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $tarifa->getIdEvento()) . "',
            '". mysqli_real_escape_string($this->mysqli, $tarifa->getInformacion()) . "',
            '". mysqli_real_escape_string($this->mysqli, $tarifa->getPrecio()) . "',
            '". mysqli_real_escape_string($this->mysqli, $tarifa->getConsumiciones()) . "'
        )";
        $this->ejecutarComando($query);
    }

    public function borra($tarifa)
    {
        $query = "DELETE FROM tarifas WHERE id = '". $tarifa->id . "')";
        $rs = $this->ejecutarComando($query);
    }
}
