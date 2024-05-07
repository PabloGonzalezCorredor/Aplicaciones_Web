<?php

require_once __DIR__.'/../DAO/NotificacionAsistenciaDAO.php';
require_once __DIR__.'/../TO/NotificacionAsistencia.php';
require_once __DIR__.'/../DAO/NotificacionEventoDAO.php';
require_once __DIR__.'/../TO/NotificacionEvento.php';
require_once __DIR__.'/../DAO/NotificacionSolicitudDAO.php';
require_once __DIR__.'/../TO/NotificacionSolicitud.php';

class NotificacionSA
{

    public static function notificarAsistencia($idUsuario, $idAsistencia){

        $notificacion = new NotificacionAsistencia($idUsuario, $idAsistencia, date("Y-m-d H:i:s"), 0);

        $notificacionAsistenciaDAO = new NotificacionAsistenciaDAO();
        $notificacionAsistenciaDAO->inserta($notificacion);
    }

    public static function notificarEvento($idUsuario, $idEvento){

        $notificacion = new NotificacionEvento($idUsuario, $idEvento, date("Y-m-d H:i:s"), 0);

        $notificacionEventoDAO = new NotificacionEventoDAO();
        $notificacionEventoDAO->inserta($notificacion);
    }

    public static function notificarSolicitud($idUsuario){

        $notificacion = new NotificacionSolicitud($idUsuario, $_SESSION['id'], date("Y-m-d H:i:s"), 0);

        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacionSolicitudDAO->inserta($notificacion);
    }
    public static function desnotificarSolicitud($idUsuario){
        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacion = $notificacionSolicitudDAO->obtenerNotificacion($idUsuario, $_SESSION['id']);
        $notificacionSolicitudDAO->borra($notificacion);
    }
    public static function obtenerNotificacionSolicitudPorId($id){
        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        return $notificacionSolicitudDAO->obtenerNotificacionPorId($id);
    }
    public static function comprobarSolicitud($idUsuario){
        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacion = $notificacionSolicitudDAO->obtenerNotificacion($idUsuario, $_SESSION['id']);
        $result = ($notificacion) ? true : false;
        return $result;
    }
    public static function eliminarSolicitud($idNotificacion){
        $notificacion = self::obtenerNotificacionSolicitudPorId($idNotificacion);
        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacionSolicitudDAO->borra($notificacion);
    }

    public static function obtenerNotificaciones(){
        $notificacionAsistenciaDAO = new NotificacionAsistenciaDAO();
        $notificacionesAsistencias = $notificacionAsistenciaDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        $notificacionEventoDAO = new NotificacionEventoDAO();
        $notificacionesEventos = $notificacionEventoDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacionesSolicitudes = $notificacionSolicitudDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        if ($notificacionesAsistencias === null) {
            $notificacionesAsistencias = array();
        }       
        if ($notificacionesEventos === null) {
            $notificacionesEventos = array();
        }
        if ($notificacionesSolicitudes === null) {
            $notificacionesSolicitudes = array();
        }
        $notificaciones = array_merge($notificacionesAsistencias, $notificacionesEventos, $notificacionesSolicitudes);

        return $notificaciones;
    }
   
    public static function marcarVista($notificacion){
        if ($notificacion instanceof NotificacionAsistencia){
            $notificacionAsistenciaDAO = new NotificacionAsistenciaDAO();
            return $notificacionAsistenciaDAO->actualiza($notificacion);
        } else if ($notificacion instanceof NotificacionEvento){
            $notificacionEventoDAO = new NotificacionEventoDAO();
            return $notificacionEventoDAO->actualiza($notificacion);
        } else if ($notificacion instanceof NotificacionSolicitud){
            $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
            return $notificacionSolicitudDAO->actualiza($notificacion);
        }
    }

    public static function tieneNuevas(){
        $notificacionAsistenciaDAO = new NotificacionAsistenciaDAO();
        $notificacionesAsistencias = $notificacionAsistenciaDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        $notificacionEventoDAO = new NotificacionEventoDAO();
        $notificacionesEventos = $notificacionEventoDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        $notificacionSolicitudDAO = new NotificacionSolicitudDAO();
        $notificacionesSolicitudes = $notificacionSolicitudDAO->obtenerNotificacionesUsuario($_SESSION['id']);

        if ($notificacionesAsistencias === null) {
            $notificacionesAsistencias = array();
        }       
        if ($notificacionesEventos === null) {
            $notificacionesEventos = array();
        }
        if ($notificacionesSolicitudes === null) {
            $notificacionesSolicitudes = array();
        }
        $notificaciones = array_merge($notificacionesAsistencias, $notificacionesEventos, $notificacionesSolicitudes);

        foreach ($notificaciones as $notificacion) {
            if ($notificacion->getVista() == 0) {
                return 1; 
            }
        }

        return 0; 
    }
}
