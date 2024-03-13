<?php

class Evento
{
    
    public static function crea($imagen, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas)
    {
        $evento = new Evento($imagen, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas);
        $evento-> añadeTarifas($tarifas);
        return $evento->guarda();
    }

    public static function buscaEvento($nombreEvento, $fecha)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Eventos E WHERE E.nombreEvento='%s' AND E.fecha='%s'", $conn->real_escape_string($nombreEvento), $conn->real_escape_string($fecha));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Evento($fila['imagen'], $fila['nombreEvento'], $fila['fecha'], $fila['horaIni'], $fila['horaFin'], $fila['localizacion'], $fila['id']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function cargaTarifas($idEvento)
    {
        $tarifas=[];
            
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM tarifas T WHERE T.idEvento=%d", $idEvento);
        $rs = $conn->query($query);
        if ($rs) {
            while ($row = $rs->fetch_assoc()) {
                $tarifa = [
                    'id' => $row['id'],
                    'informacion' => $row['informacion'],
                    'precio' => $row['precio'],
                    'consumiciones' => $row['consumiciones']
                ];
                $tarifas[] = $tarifa;
            }
            $rs->free();
            return $tarifas;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function buscaPorId($idEvento)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Eventos WHERE id=%d", $idEvento);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Evento($fila['imagen'], $fila['nombreEvento'], $fila['fecha'], $fila['horaIni'], $fila['horaFin'], $fila['localizacion'], $fila['id']);
                $result->id = $idEvento;
                $tarifas = self::cargaTarifas($idEvento);
                $result-> añadeTarifas($tarifas);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }     
   
    private static function inserta($evento)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO Eventos(imagen, nombreEvento, fecha, horaIni, horaFin, localizacion) VALUES ('%s','%s', '%s', '%s', '%s','%s')"
            , $conn->real_escape_string($evento->imagen)
            , $conn->real_escape_string($evento->nombreEvento)
            , $conn->real_escape_string($evento->fecha)
            , $conn->real_escape_string($evento->horaIni)
            , $conn->real_escape_string($evento->horaFin)
            , $conn->real_escape_string($evento->localizacion)
        );
        if ( $conn->query($query) ) {
            $evento->id = $conn->insert_id;
            $result = self::insertaPosesion($evento->id);
            $result = self::insertaTarifas($evento);
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function insertaTarifas($evento)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        foreach($evento->tarifas as $tarifa) {
            $query = sprintf("INSERT INTO Tarifas(idEvento, informacion, precio, consumiciones) VALUES (%d, '%s', %d, %d)"
                , $evento->id
                , $conn->real_escape_string($tarifa['informacion'])
                , $tarifa['precio']
                , $tarifa['consumiciones']
            );
            if ( !$conn->query($query) ) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return $evento;
    }

    private static function insertaPosesion($eventoid)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO Posesion(evento, usuario) VALUES (%d, %d)"
            , $eventoid
            , $_SESSION["id"]
        );
        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $evento;
    }
    
    public static function actualiza($eventoid, $imagen, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE Eventos E SET imagen = '%s', nombreEvento = '%s', fecha='%s', horaIni='%s', horaFin='%s', localizacion='%s' WHERE E.id=%d"
            , $conn->real_escape_string($imagen)
            , $conn->real_escape_string($nombreEvento)
            , $conn->real_escape_string($fecha)
            , $conn->real_escape_string($horaIni)
            , $conn->real_escape_string($horaFin)
            , $conn->real_escape_string($localizacion)
            , $eventoid
        );

        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }
    
    private static function borra($evento)
    {
        return self::borraPorId($evento->id);
    }
    
    public static function borraPorId($idEvento)
    {
            if (!$idEvento) {
                return false;
            } 
        
            $conn = Aplicacion::getInstance()->getConexionBd();
        
            // Iniciar una transacción para asegurar la integridad de los datos
            $conn->begin_transaction();
        
            // Eliminar las tarifas asociadas al evento
            $queryTarifas = sprintf("DELETE FROM tarifas WHERE idEvento = %d", $idEvento);
            if (!$conn->query($queryTarifas)) {
                $conn->rollback();
                error_log("Error al eliminar las tarifas del evento ({$conn->errno}): {$conn->error}");
                return false;
            }
        
            // Eliminar el evento
            $queryEvento = sprintf("DELETE FROM eventos WHERE id = %d", $idEvento);
            if (!$conn->query($queryEvento)) {
                $conn->rollback();
                error_log("Error al eliminar el evento ({$conn->errno}): {$conn->error}");
                return false;
            }
        
            // Confirmar la transacción si todo fue exitoso
            $conn->commit();
        
            return true;
    }

    
    public static function obtenerEventosSiguientes()
    {
        $eventos = [];
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM Eventos E WHERE E.fecha >= CURDATE()";
        $rs = $conn->query($query);
        
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $evento = new Evento($fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion']);
                $evento->id = $fila['id'];
                $tarifas = self::cargaTarifas($fila['id']);
                $evento-> añadeTarifas($tarifas);
                $eventos[] = $evento;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $eventos;
    }

    public static function obtenerEventosSiguientesPromotor()
    {
        $eventos = [];
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT e.* FROM Eventos e INNER JOIN Posesion p ON e.id = p.evento WHERE e.fecha >= CURDATE() AND p.usuario = %d;" , $_SESSION["id"]);
        $rs = $conn->query($query);
        
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $evento = new Evento($fila['imagen'], $fila['nombreEvento'],$fila['fecha'],$fila['horaIni'], $fila['horaFin'],$fila['localizacion']);
                $evento->id = $fila['id'];
                $tarifas = self::cargaTarifas($fila['id']);
                $evento-> añadeTarifas($tarifas);
                $eventos[] = $evento;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $eventos;
    }

    private $id;

    private $imagen;

    private $nombreEvento;

    private $fecha;

    private $horaIni;

    private $horaFin;

    private $localizacion;

    private $tarifas;

    private function __construct($imagen, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas = [], $id = null)
    {
        $this->id = $id;
        $this->imagen = $imagen;
        $this->nombreEvento = $nombreEvento;
        $this->fecha = $fecha;
        $this->horaIni = $horaIni;
        $this->horaFin = $horaFin;
        $this->localizacion = $localizacion;
        $this->tarifas = $tarifas;
    }

    public function getId()
    {
        return $this->id;
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

    public function getTarifas()
    {
        return $this->tarifas;
    }

    public function añadeTarifas($tarifas)
    {
        $this->tarifas = $tarifas;
    }
    
    public function guarda()
    {
        return self::inserta($this);
    }
    
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
