<?php

require_once __DIR__.'/DAO.php';
require_once __DIR__.'/../TO/Usuario.php';

class UsuarioDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function buscaUsuario($nombreUsuario)
    {
        $query = "SELECT * FROM usuarios U WHERE U.nombreUsuario='". mysqli_real_escape_string($this->mysqli, $nombreUsuario) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs && count($rs) == 1) {
            $fila = $rs[0];
            
            $result = new Usuario($fila['imagen'],$fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['id']);
        } 
        return $result;
    }
    public function buscaUsuarioPorId($id)
    {
        $query = "SELECT * FROM usuarios U WHERE U.id='". mysqli_real_escape_string($this->mysqli, $id) . "'";
        $rs = $this->ejecutarConsulta($query);
        $result = false;
        if ($rs) {
            if (count($rs) == 1) {
                $fila = $rs[0];

                $result = new Usuario($fila['imagen'],$fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['id']);
            }
        } 
        return $result;
    }
    public function buscaUsuarios($cadena)
    {
        $query = "SELECT * FROM usuarios U WHERE U.nombreUsuario LIKE '" . mysqli_real_escape_string($this->mysqli, $cadena) . "%'";
        $rs = $this->ejecutarConsulta($query);
        $result = array(); 
    
        if ($rs && count($rs) > 0) { 
            foreach ($rs as $fila) { 
                $usuario = new Usuario($fila['imagen'],$fila['nombreUsuario'], $fila['password'], $fila['nombre'], $fila['rol'], $fila['id']);
                $result[] = $usuario; 
            }
        }

        return $result;
    }

    public function inserta($usuario)
    {
        $query="INSERT INTO usuarios(imagen, nombreUsuario, nombre, password, rol) VALUES 
        (
            '". mysqli_real_escape_string($this->mysqli, $usuario->getImagen()) . "',
            '". mysqli_real_escape_string($this->mysqli, $usuario->getNombreUsuario()) . "',
            '". mysqli_real_escape_string($this->mysqli, $usuario->getNombre()) . "',
            '". mysqli_real_escape_string($this->mysqli, $usuario->getPassword()) . "',
            '". mysqli_real_escape_string($this->mysqli, $usuario->getRol()) . "'
        )";
        $rs = $this->ejecutarComando($query);
    }

    public function actualiza($usuario)
    {
        $query="UPDATE usuarios U SET imagen = '". mysqli_real_escape_string($this->mysqli, $usuario->getImagen()) . "', nombreUsuario = '". mysqli_real_escape_string($this->mysqli, $usuario->getNombreUsuario()) . "', nombre='". mysqli_real_escape_string($this->mysqli, $usuario->getNombre()) . "', password='". mysqli_real_escape_string($this->mysqli, $usuario->getPassword()) . "', rol='". mysqli_real_escape_string($this->mysqli, $usuario->getRol()) . "' WHERE U.id='". mysqli_real_escape_string($this->mysqli, $usuario->getId()) . "'";
        $rs = $this->ejecutarComando($query);
    }

    public function borra($idUsuario)
    {
        $query = "DELETE FROM usuarios U WHERE U.id = '". mysqli_real_escape_string($this->mysqli, $idUsuario) . "')";
        $rs = $this->ejecutarComando($query);
    }
}
