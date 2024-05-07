<?php

require_once __DIR__.'/../includes/Aplicacion.php';

 class DAO 
 {
    public $mysqli;
  
    public function __construct()
    {
        if ( !$this->mysqli )
        {  
            $this->mysqli =  Aplicacion::getInstance()->getConexionBd();
            
        }  
    }     

    public function ejecutarConsulta($sql)
    {
        if($sql != "")
        {
            $consulta = $this->mysqli->query($sql) or die ($mysqli->error. " en la línea ".(__LINE__-1));
            
            $tablaDatos = array();
            
            while ($fila = mysqli_fetch_assoc($consulta))
            {  
                array_push($tablaDatos, $fila);
            }
                
            return $tablaDatos;
        } 
        else
        {
            return 0;
        }
    }

    public function ejecutarComando($sql)
    {
        $this->mysqli->query($sql) or die ($mysqli->error. " en la línea ".(__LINE__-1));

        return true;
    }
 }

 ?>