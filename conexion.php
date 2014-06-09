<?php
class Conectar{
	public static function conexion(){
	$con = mysql_connect("localhost","root","");
	mysql_select_db("hologramia");
	return $con;
	}
}
?>