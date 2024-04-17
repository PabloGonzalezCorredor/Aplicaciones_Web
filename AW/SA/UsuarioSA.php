<?php

require_once __DIR__.'/../DAO/UsuarioDAO.php';
require_once __DIR__.'/../DAO/SeguidoresDAO.php';

class UsuarioSA
{

    public static function login($nombreUsuario, $password)
    {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuario($nombreUsuario);
        
        if ($usuario && password_verify($password, $usuario->getPassword())) 
        {
            return $usuario;
        }

        return false;
    }

    public static function crea($imagenBinaria, $nombreUsuario, $password, $nombre, $rol){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuario($nombreUsuario);
        
        if ($usuario){ return NULL; }
        
        $usuario = new Usuario($imagenBinaria, $nombreUsuario, password_hash($password, PASSWORD_DEFAULT), $nombre, $rol);
        $usuarioDAO->inserta($usuario);

        return self::login($nombreUsuario, $password);
    }

    public static function buscaUsuarioPorId($idUsuario){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->buscaUsuarioPorId($idUsuario);
    }

    public static function buscaUsuarios($cadena){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->buscaUsuarios($cadena);
    }

    public static function comprobarSeguido($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();
        return $seguidoresDAO->comprueba($_SESSION['id'], $idUsuario);
    }

    public static function seguir($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();

        if (self::comprobarSeguido($idUsuario) == false){
            $seguidoresDAO->inserta($_SESSION['id'], $idUsuario);
        }
    }

    public static function borra($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();

        if (self::comprobarSeguido($idUsuario) == true){
            $seguidoresDAO->borra($_SESSION['id'], $idUsuario);
        }
    }

    public static function contarSeguidores($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();
        return count($seguidoresDAO->obtenerSeguidores($idUsuario));
    }

    public static function contarSeguidos($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();
        return count($seguidoresDAO->obtenerSeguidos($idUsuario));
    }

    public static function obtenerSeguidores($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();
        $seguidos = $seguidoresDAO->obtenerSeguidores($idUsuario);
        $result = array(); 
        foreach ($seguidos as $seguido){
            $usuarioID = $seguido->getIdSeguidor();
            $usuario = self::buscaUsuarioPorId($usuarioID);
            $result[] = $usuario;
        }
        return $result;
    }

    public static function obtenerSeguidos($idUsuario){
        $seguidoresDAO = new SeguidoresDAO();
        $seguidos = $seguidoresDAO->obtenerSeguidos($idUsuario);
        $result = array(); 
        foreach ($seguidos as $seguido){
            $usuarioID = $seguido->getIdSeguido();
            $usuario = self::buscaUsuarioPorId($usuarioID);
            $result[] = $usuario;
        }
        return $result;
    }

    public static function actualizaUsuario($usuario,$imagenBinaria, $nombreUsuario, $nombre, $password){
        $usuarioDAO = new UsuarioDAO();
        $usuario->setImagen($imagenBinaria);
        $usuario->setNombreUsuario($nombreUsuario);
        $usuario->setNombre($nombre);
        $usuario->setPassword($password);

        if ($_SESSION['id'] == $usuario->getId()){
            $usuarioDAO->actualiza($usuario);
        }  
    }

    public static function esPromotor($idUsuario){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuarioPorId($idUsuario);

        if ($usuario->getRol() == 1){
            return true;
        } else return false;
    }
   
}
