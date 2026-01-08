<?php

interface UserInterface
{
    public function setUsername($username);
    public function setEmail($email);
    public function setNombre($nombre);
    public function setFechaCreacion($fecha_creacion);
    public function setActive($active);
    public function getId();
    public function getUsername();
    public function getEmail();
    public function getNombre();
    public function getFechaCreacion();
    public function isActive();
}

class User implements UserInterface
{
    private $id;
    private $username;
    private $email;
    private $nombre;
    private $fecha_creacion;
    private $activo;

    public function __construct($id, $username, $email, $nombre, $fecha_creacion, $activo)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->fecha_creacion = $fecha_creacion;
        $this->activo = $activo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    public function isActive()
    {
        return $this->activo == 1;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setFechaCreacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setActive($active)
    {
        $this->activo = $active ? 1 : 0;
    }
}

?>