<?php

require_once __DIR__.'/../DAO/UsuarioDAO.php';
require_once __DIR__.'/../DAO/SeguidoresDAO.php';

class UsuarioSA
{

    public static function login($nombreUsuario, $password){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuario($nombreUsuario);
        
        if ($usuario && password_verify($password, $usuario->getPassword())) 
        {
            return $usuario;
        }

        return false;
    }

    public static function crea($imagenBinaria, $nombreUsuario, $password, $nombre, $rol, $privacidad, $edad,$genero){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuario($nombreUsuario);
        
        if ($usuario){ return NULL; }
        
        $usuario = new Usuario($imagenBinaria, $nombreUsuario, password_hash($password, PASSWORD_DEFAULT), $nombre, $rol, $privacidad, $edad, $genero);
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

    public static function comprobarSeguido($idSeguidor, $idSeguido){
        $seguidoresDAO = new SeguidoresDAO();
        return $seguidoresDAO->comprueba($idSeguidor, $idSeguido);
    }

    public static function esPromotor($idUsuario){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuarioPorId($idUsuario);

        if ($usuario->getRol() == 1){
            return true;
        } else return false;
    }

    public static function esPrivado($idUsuario){
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscaUsuarioPorId($idUsuario);

        if ($usuario->getPrivacidad() == 0){
            return true;
        } else return false;
    }


    public static function seguir($idSeguidor, $idSeguido){
        $seguidoresDAO = new SeguidoresDAO();

        if (self::comprobarSeguido($idSeguidor, $idSeguido) == false){
            $seguidoresDAO->inserta($idSeguidor, $idSeguido);
        }
    }

    public static function dejarDeSeguir($idSeguidor, $idSeguido){
        $seguidoresDAO = new SeguidoresDAO();

        if (self::comprobarSeguido($idSeguidor, $idSeguido) == true){
            $seguidoresDAO->borra($idSeguidor, $idSeguido);
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

    public static function actualizaUsuario($usuario,$imagenBinaria, $nombreUsuario, $nombre, $password, $privacidad, $edad, $genero){
        $usuarioDAO = new UsuarioDAO();
        $usuario->setImagen($imagenBinaria);
        $usuario->setNombreUsuario($nombreUsuario);
        $usuario->setNombre($nombre);
        $usuario->setPassword($password);
        $usuario->setPrivacidad($privacidad);
        $usuario->setEdad($edad);
        $usuario->setGenero($genero);

        if ($_SESSION['id'] == $usuario->getId()){
            $usuarioDAO->actualiza($usuario);
        }  
    }
   
}
