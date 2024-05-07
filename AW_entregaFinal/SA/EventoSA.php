<?php

require_once __DIR__.'/../DAO/EventoDAO.php';
require_once __DIR__.'/../DAO/AsistenciaDAO.php';
require_once __DIR__.'/../DAO/TarifaDAO.php';
require_once __DIR__.'/../TO/Asistencia.php';
require_once __DIR__.'/../TO/Tarifa.php';
require_once __DIR__.'/../TO/Evento.php';
require_once __DIR__.'/../includes/config.php';

class EventoSA 
{
    public static function obtenerEventoPorId($idEvento){
        $eventoDAO = new EventoDAO();
        $tarifaDAO = new TarifaDAO();
        
        $evento = $eventoDAO->obtenerEventoPorId($idEvento);
        $tarifas = $tarifaDAO->obtenerTarifasEvento($idEvento);

        return array($evento, $tarifas);
    }

    public static function obtenerEvento($nombreEvento, $fecha){
        $eventoDAO = new EventoDAO();
        return $eventoDAO->obtenerEvento($nombreEvento, $fecha);
    }

    public static function obtenerEventosSiguientes(){
        $eventoDAO = new EventoDAO();
        return $eventoDAO->obtenerEventosSiguientes();
    }
   
    public static function obtenerEventosSiguientesUsuario($idUsuario){
        $asistenciaDAO = new AsistenciaDAO();
        $asistencias = $asistenciaDAO->obtenerAsistenciasUsuario($idUsuario);
        $eventos = self::obtenerEventosSiguientes();

        $eventosFiltrados = array();

        foreach ($eventos as $evento) {
            $idEvento = $evento->getId();

            // Verificamos si el ID del evento está presente en el array de asistencias
            if (in_array($idEvento, array_map(function($asistencia) { return $asistencia->getIdEvento(); }, $asistencias))) {
                $eventosFiltrados[] = $evento; 
            }
        }

        return $eventosFiltrados;
    }

    public static function obtenerEventosSiguientesPromotor($idUsuario){
        $eventoDAO = new EventoDAO();
        return $eventoDAO->obtenerEventosSiguientesPromotor($idUsuario);
    }

    public static function obtenerEventosPasados(){
        $eventoDAO = new EventoDAO();
        return $eventoDAO->obtenerEventosPasados();
    }

    public static function obtenerEventosPasadosUsuario($idUsuario){
        $asistenciaDAO = new AsistenciaDAO();
        $asistencias = $asistenciaDAO->obtenerAsistenciasUsuario($idUsuario);
        $eventos = self::obtenerEventosPasados();

        $eventosFiltrados = array();

        foreach ($eventos as $evento) {
            $idEvento = $evento->getId();

            // Verificamos si el ID del evento está presente en el array de asistencias
            if (in_array($idEvento, array_map(function($asistencia) { return $asistencia->getIdEvento(); }, $asistencias))) {
                $eventosFiltrados[] = $evento; 
            }
        }

        return $eventosFiltrados;
    }

    public static function obtenerEventosPasadosPromotor($idUsuario){
        $eventoDAO = new EventoDAO();
        return $eventoDAO->obtenerEventosPasadosPromotor($idUsuario);
    }

    public static function obtenerTarifaPorId($id){
        $tarifaDAO = new TarifaDAO();
        return $tarifaDAO->obtenerTarifaPorId($id);
    }
    
    public static function obtenerAsistentes($idEvento){
        $asistenciaDAO = new AsistenciaDAO();
        return $asistenciaDAO->obtenerAsistenciasEvento($idEvento);
    }

    public static function obtenerAsistentesSeguidos($idEvento, $usuarios){
        $asistenciaDAO = new AsistenciaDAO();
        $usuariosAsistentes = array();

        foreach ($usuarios as $usuario) {
            if (self::obtenerAsistencia($usuario->getId(), $idEvento)) {
                $usuariosAsistentes[] = $usuario;
            }
        }
    
        return $usuariosAsistentes;
    }

    public static function contarAsistentesSeguidos($idEvento, $usuarios){
        $usuarios = self::obtenerAsistentesSeguidos($idEvento, $usuarios);
        return count($usuarios);
    }

    public static function existeEvento($nombreEvento, $fecha){
        $eventoDAO = new EventoDAO();
        $existe = ($eventoDAO->obtenerEvento($nombreEvento, $fecha)) ? true : false;
        return $existe;
    }

    public static function obtenerAsistencia($idUsuario, $idEvento){
        $asistenciaDAO = new AsistenciaDAO();
        return $asistenciaDAO->obtenerAsistencia($idUsuario, $idEvento);
    }

    public static function obtenerAsistenciaPorId($id){
        $asistenciaDAO = new AsistenciaDAO();
        return $asistenciaDAO->obtenerAsistenciaPorId($id);
    }

    public static function creaEvento($idPromotor, $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas){
        $eventoDAO = new EventoDAO();
        $tarifasDAO = new TarifaDAO();

        $evento = new Evento($idPromotor, $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion);
        $idEvento = $eventoDAO->inserta($evento);

        foreach ($tarifas as $tarifa) {
           $tarifa = new Tarifa($idEvento, $tarifa['informacion'], $tarifa['precio'], $tarifa['consumiciones'], $tarifa['cantidad']);
           $tarifasDAO->inserta($tarifa);
        }
    }

    public static function borraEvento($idEvento){
        $eventoDAO = new EventoDAO();
        $evento = $eventoDAO->obtenerEventoPorId($idEvento);

        if ($_SESSION['id'] == $evento->getIdPromotor()){
            $eventoDAO->borra($evento);
        }
    }

    public static function actualizarEvento($evento, $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion){
        $eventoDAO = new EventoDAO();
        $evento->setImagen($imagenBinaria);
        $evento->setNombreEvento($nombreEvento);
        $evento->setFecha($fecha);
        $evento->setHoraIni($horaIni);
        $evento->setHoraFin($horaFin);
        $evento->setLocalizacion($localizacion);

        if ($_SESSION['id'] == $evento->getIdPromotor()){
            $eventoDAO->actualiza($evento);
        }   
    }

    public static function insertaAsistencia($idEvento, $idTarifa){

        if (!self::obtenerAsistencia($_SESSION['id'], $idEvento)){ 
            $tarifaDAO = new TarifaDAO();
            $tarifaDAO->actualiza($idTarifa);

            $codigo = self::genera_codigo();

            $asistencia = new Asistencia($codigo, $_SESSION['id'], $idEvento, $idTarifa, '0');

            $asistenciaDAO = new AsistenciaDAO();
            $asistenciaDAO->inserta($asistencia);
            return true;
        } else return false;
    }

    public static function comprobarEntrada($entrada, $idEvento){

        $asistenciaDAO = new AsistenciaDAO();
        return $asistenciaDAO->comprobarEntrada($entrada, $idEvento);
    }

    public static function obtenerEntrada($idEvento){

        if (self::obtenerAsistencia($_SESSION['id'], $idEvento)){ 
            $asistenciaDAO = new AsistenciaDAO();
            $asistencia = $asistenciaDAO->obtenerAsistencia($_SESSION['id'], $idEvento);
            return $asistencia->getCodigo();
        }
    }
    static function genera_codigo(){
        $caracteres = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $codigo = '';
    
        for ($i = 1; $i <= 13; $i++) {
            $codigo .= $caracteres[rand(0, 35)];
        }
    
        return $codigo;
    }

    public static function validarEntrada($idAsistencia){
        $asistenciaDAO = new AsistenciaDAO();
        $asistenciaDAO->actualiza($idAsistencia);
    }


}
