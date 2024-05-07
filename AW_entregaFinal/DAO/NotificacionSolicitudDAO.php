<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/NotificacionSolicitud.php';

class NotificacionSolicitudDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerNotificacionesUsuario($idUsuario)
    {        
        $query = "SELECT * FROM notificacionessolicitudes N WHERE N.idUsuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $notificacion = new NotificacionSolicitud($fila['idUsuario'], $fila['idSeguidor'], $fila['fecha'], $fila['vista'], $fila['id']);
                $result[] = $notificacion;
            }
        }
        return $result;   
    }

    public function obtenerNotificacion($idUsuario, $idSeguidor)
    {        
        $query = "SELECT * FROM notificacionessolicitudes N WHERE N.idUsuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "' AND N.idSeguidor='". mysqli_real_escape_string($this->mysqli, $idSeguidor) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs) {
            $fila = $rs[0];
            if (count($rs) == 1) {
                $result = new NotificacionSolicitud($fila['idUsuario'], $fila['idSeguidor'], $fila['fecha'], $fila['vista'], $fila['id']);
            }
        }
        return $result;   
    }

    public function obtenerNotificacionPorId($id)
    {        
        $query = "SELECT * FROM notificacionessolicitudes N WHERE N.id='". mysqli_real_escape_string($this->mysqli, $id) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs) {
            $fila = $rs[0];
            if (count($rs) == 1) {
                $result = new NotificacionSolicitud($fila['idUsuario'], $fila['idSeguidor'], $fila['fecha'], $fila['vista'], $fila['id']);
            }
        }
        return $result;   
    }

    public function inserta($notificacion)
    {
        $query="INSERT INTO notificacionessolicitudes(idUsuario, idSeguidor, fecha, vista) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdUsuario()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdSeguidor()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getFecha()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getVista()) . "'
        )";
        $this->ejecutarComando($query);
    }

    public function borra($notificacion)
    {
        $query = "DELETE FROM notificacionessolicitudes WHERE id = '". $notificacion->getId() ."'";
        $rs = $this->ejecutarComando($query);
    }

    public function actualiza($notificacion)
    {
        $query = "UPDATE notificacionessolicitudes
        SET vista = 1
        WHERE id = '" . $notificacion->getId() . "'";
        
        $rs = $this->ejecutarComando($query);
    }
}
