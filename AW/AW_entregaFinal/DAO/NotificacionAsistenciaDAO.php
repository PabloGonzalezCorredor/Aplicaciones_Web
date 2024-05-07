<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/NotificacionAsistencia.php';

class NotificacionAsistenciaDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerNotificacionesUsuario($idUsuario)
    {        
        $query = "SELECT * FROM notificacionesasistencias N WHERE N.idUsuario='". mysqli_real_escape_string($this->mysqli, $idUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $notificacion = new NotificacionAsistencia($fila['idUsuario'], $fila['idAsistencia'], $fila['fecha'], $fila['vista'], $fila['id']);
                $result[] = $notificacion;
            }
        }
        return $result;   
    }

    public function inserta($notificacion)
    {
        $query="INSERT INTO notificacionesasistencias(idUsuario, idAsistencia, fecha, vista) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdUsuario()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getIdAsistencia()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getFecha()) . "',
            '". mysqli_real_escape_string($this->mysqli, $notificacion->getVista()) . "'
        )";
        $this->ejecutarComando($query);
    }

    public function borra($notificacion)
    {
        $query = "DELETE FROM notificacionesasistencias WHERE id = '". $notificacion->getId() ."'";
        $rs = $this->ejecutarComando($query);
    }

    public function actualiza($notificacion)
    {
        $query = "UPDATE notificacionesasistencias 
        SET vista = 1
        WHERE id = '" . $notificacion->getId() . "'";
        
        $rs = $this->ejecutarComando($query);
    }
}
