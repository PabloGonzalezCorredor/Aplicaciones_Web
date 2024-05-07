<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/NotificacionEvento.php';

class NotificacionEventoDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerNotificacionesUsuario($idUsuario)
    {        
        $query = "SELECT * FROM notificacioneseventos N WHERE N.idUsuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $notificacion = new NotificacionEvento($fila['idUsuario'], $fila['idEvento'], $fila['fecha'], $fila['vista'], $fila['id']);
                $result[] = $notificacion;
            }
        }
        return $result;   
    }

    public function inserta($notificacion)
    {
        $query="INSERT INTO notificacioneseventos(idUsuario, idEvento, fecha, vista) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdUsuario()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdEvento()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getFecha()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getVista()) . "'
        )";
        $this->ejecutarComando($query);
    }

    public function borra($notificacion)
    {
        $query = "DELETE FROM notificacioneseventos WHERE id = '". $notificacion->getId() ."'";
        $rs = $this->ejecutarComando($query);
    }

    public function actualiza($notificacion)
    {
        $query = "UPDATE notificacioneseventos
        SET vista = 1
        WHERE id = '" . $notificacion->getId() . "'";
        
        $rs = $this->ejecutarComando($query);
    }
}
