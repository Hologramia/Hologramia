<?php
sleep(1);
include("../../clases/conexion.php");
if($_REQUEST['username']) {
    $username = $_REQUEST['username'];
    $query = "select * from usuario where correo = '".$username."'";
    $results = mysql_query( $query,Conectar::conexion()) or die('ok');
	if(mysql_num_rows(@$results) > 0)
        echo '<strong><label id="Error"  style="color:red;font-size: 14px;"">Ya existe</label></strong>';
    else
        echo '<strong><label id="Success" style="color:green;font-size: 14px;"">Disponible</label></strong>';
}
?>