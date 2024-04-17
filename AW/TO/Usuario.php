<?php

class Usuario
{

    public const ADMIN_ROLE = 1;

    public const USER_ROLE = 2;

    private $id;

    private $imagen;

    private $nombreUsuario;

    private $password;

    private $nombre;

    private $rol;

    public function __construct($imagen, $nombreUsuario, $password, $nombre, $rol, $id = null)
    {
        $this->id = $id;
        $this->imagen = $imagen;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->rol = $rol;
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getImagen()
    {
        return $this->imagen;
    }
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRol()
    {
        return $this->rol;
    }

    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function setRol($rol)
    {
        $this->rol = $rol;
    }
}
