<?php
error_reporting(0);
require_once('../../clases/class_usuario.php');
session_start();
$msg = '';
if(isset($_POST['boton'])){
	$original_captcha = $_SESSION["captcha"]; // session 'captcha' has been alreadey created in captcha.php file, if you want to rename the session open the captcha.php file and find and change the name of the session
	$user_captcha = $_POST["captcha"];
	if($user_captcha == $original_captcha){
		$msg = '<span class="success">Captcha Matched!</span>';
	}else{
		$msg = '<span class="error">Oops! Captcha Mismatched!</span>';
	}
}
if($_POST['boton']=='Enviar' and $msg=='<span class="success">Captcha Matched!</span>'){
$confirmar =mysql_query("select correo from usuario where correo='".$_POST['correo']."'",Conectar::conexion());
if($existe = mysql_fetch_array($confirmar))
{
$usuario = new Usuario($_POST['nombre'],$_POST['apellido'],$_POST['correo'],$_POST['password'],$_POST['fecha_nacimiento']);
$nombre=$usuario->get_nombre();
$apellido=$usuario->get_apellido();
$correo=$usuario->get_correo();
$fecha_nacimiento=$usuario->get_fecha_nacimiento();
$msg='<strong><label id="Error"  style="color:red;font-size: 14px;"">Ya se encuentra registrado</label></strong>';
}
else{
$usuario = new Usuario($_POST['nombre'],$_POST['apellido'],$_POST['correo'],$_POST['password'],$_POST['fecha_nacimiento'],$_POST['noticias']);
$usuario->insertar_usuario();
$msg='<strong><label id="Error"  style="color:green;font-size: 14px;"">Se a registrado con exito</label></strong>';
}
}
else if($_POST['boton']=='Enviar' and $msg=='<span class="error">Oops! Captcha Mismatched!</span>'){
$usuario = new Usuario($_POST['nombre'],$_POST['apellido'],$_POST['correo'],$_POST['password'],$_POST['fecha_nacimiento']);
$nombre=$usuario->get_nombre();
$apellido=$usuario->get_apellido();
$correo=$usuario->get_correo();
$fecha_nacimiento=$usuario->get_fecha_nacimiento();	
}
?>
<?php 
session_start(); 
if($_SESSION['usuario']){ 
header("location:../../index.php"); 
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../js/livevalidation_standalone.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
<script language="javaScript" type="text/javascript" src="../../js/jquery.ui.core.js"></script>
<script language="javaScript" type="text/javascript" src="../../js/jquery.ui.datepicker.js"></script>
<script language="javaScript" type="text/javascript" src="../../js/jquery.ui.widget.js"></script>
<link href="../../css/ui.core.css" rel="stylesheet" type="text/css">
<link href="../../css/ui.datepicker.css" rel="stylesheet" type="text/css">
<link href="../../css/ui.theme.css" rel="stylesheet" type="text/css">
<script type="" language="javascript">
$(function() {
$('#f5').datepicker({
showButtonPanel: true,
showAnim: "slideDown",
changeMonth: true,
changeYear: true,
dateFormat:'yy-mm-dd',
yearRange: '1914:2021'
});
$('#f6').datepicker({
showButtonPanel: true,
showAnim: "slideDown",
changeMonth: true,
changeYear: true,
dateFormat:'yy-mm-dd',
yearRange: '1914:2021'
});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$("#change_captcha").click(function(){
		$("#as_captcha").attr("src", "captcha.php?"+(new Date()).getTime()); // browser will save the captcha image in its cache so add '?some_unique_char' to set new URL for each click
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {    
    $('#username').blur(function(){

        $('#Info').fadeOut(1);

        var username = $(this).val();        
        var dataString = 'username='+username;

        $.ajax({
            type: "POST",
            url: "verificar_usuario.php",
            data: dataString,
            success: function(data) {
                $('#Info').fadeIn(1).html(data);
            }
        });
    });              
});    
</script>

<title></title>
</head>
<body>
<div align="center" class="wrapper">
  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="hform" id="test_form">
    <table cellpadding="2" cellspacing="2">
      <tr>
        <td colspan="2" align="center"><h1>Registro</h1></td>
      </tr>
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre" value="<?php echo $nombre; ?>"></td>
      </tr>
      <tr>
        <td>Apellido</td>
        <td><input type="text" name="apellido" value="<?php echo $apellido; ?>"></td>
      </tr>
      <tr>
        <td>Correo</td>
        <td><input type="text" name="correo" value="<?php echo $correo; ?>"  id="username"><label id="Info"></label></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><input type="password" name="password" value="" id="myPasswordField"></td>
        </tr>
      <tr>
        <td>Confirme Password</td>
        <td><input type="password" name="password2" value="" id="clave"></td>
      </tr>
      <tr>
        <td>Fecha de nacimiento</td>
        <td><input type="text" readonly id="f5" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>"></td>
      </tr>
      <tr>
      <td>Desea recibir noticias</td>
        <td><p>
          <label>
          si
            <input type="radio" name="noticias" value="si" checked="checked"/>
           </label>
          <label>
          no
            <input type="radio" name="noticias" value="no"/>
            </label>
        </p></td>
      </tr>
      <tr>
        <td><a class="a" id="change_captcha">Can't read? try another one</a></td>
        <td><img src="captcha.php" id="as_captcha" alt="Captcha" /></td>
      </tr>
      <tr>
      <td valign="bottom">Enter the captcha</td>
        <td><input type="text" name="captcha" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo $msg; ?></td>
      </tr>
      <tr>
        <td align="center"><input type="submit" name="boton" id="submit" value="Enviar"/></td><td align="center"><a href="../../index.php">index</a></td>
      </tr>
    </table>
  </form>
  <script type="text/javascript">
var clave = new LiveValidation( 'clave');
clave.add( Validate.Confirmation, { match: 'myPasswordField' } );
</script> 

</div>
</body>
</html>