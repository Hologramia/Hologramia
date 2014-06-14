<?php
include ('conexion.php');
class Usuario {
var $nombre;
var $apellido;
var $correo;
var $password;
var $fecha_nacimiento;
var $noticias;

function __construct($nombre="",$apellido="",$correo="",$password="",$fecha_nacimiento="",$noticias="") {
$this->nombre = $nombre;
$this->apellido = $apellido;
$this->correo = $correo;
$this->password = $password;
$this->fecha_nacimiento = $fecha_nacimiento;
$this->noticias = $noticias;
}

public function get_nombre() {
return $this->nombre;
}

public function get_apellido() {
return $this->apellido;
}

public function get_correo() {
return $this->correo;
}

public function get_password() {
return $this->password;
}

public function get_fecha_nacimiento() {
return $this->fecha_nacimiento;
}

public function get_noticias() {
return $this->noticias;
}

public function insertar_usuario(){
$query = "INSERT INTO usuario VALUES ('".NULL."','".$this->get_nombre()."','".$this->get_apellido()."','".$this->get_correo()."','".$this->get_password()."','".$this->get_fecha_nacimiento()."','".$this->get_noticias()."')";
$insertar = mysql_query($query,Conectar::conexion());
}
}
