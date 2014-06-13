<?php
require_once("conexion.php");
class Verificar {
protected $usuario;
protected $clave;

function __construct($usuario,$clave) {
$this->usuario = $usuario;
$this->clave = $clave;
}

function get_usuario() {
return $this->usuario;
}

function get_clave() {
return $this->clave;
}

public function session(){
session_start();
$sql = mysql_query("select correo,password from usuario where correo='".$this->get_usuario()."' and password='".$this->get_clave()."'",Conectar::conexion()) or die(mysql_error());  
$row = mysql_fetch_array($sql);
if($row['correo'])
{	
$_SESSION['usuario']=$row['correo'];
header("location:../../index.php");
}
else{
echo ("<script>alert('Usuario o Clave Incorrecta');</script>");
echo ("<script>location.href='../sesion/session.php'</script>");
}
}
}

class Seguridad {

function existe() {
session_start();
if (!isset($_SESSION['usuario'])){
header("location:../../index.php");
}
}
}

class Cerrar {

function destruir() 
{
session_start();
session_unset();
setcookie(session_name(), 0, 1 , ini_get("session.cookie_path"));
session_destroy();
header("Cache-Control: private",false);
header("Location:../../index.php");
}
}
?>