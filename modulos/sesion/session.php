<?php
error_reporting(0);
include_once('../../clases/class_sesion.php'); 
if($_POST['boton']=='Enviar'){
$session = new Verificar($_POST['correo'],$_POST['password']);
$session->session();
$session->get_usuario();
}

?>
<?php 
session_start(); 
if($_SESSION['usuario']){ 
header("location:../../index.php"); 
}
?>


<!DOCTYPE html>
<html>
	<head>
		<title></title>
    	</head>
	<body>
     <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
      <div align="center">
        <table cellpadding="2" cellspacing="2">
          <tr><td colspan="2" align="center"><h1>Inicio Sesion</h1></td></tr>
          <tr><td>Usuario</td><td><input type="text" name="correo" required></td></tr>
          <tr><td>password</td><td><input type="password" name="password" required></td></tr>
          <tr><td align="center"><input type="submit" name="boton" value="Enviar"></td><td align="center"><a href="../../index.php">index</a></td></tr>
          <tr><td colspan="2"><a href="../registro/registro.php">No tienes una cuenta? <strong>Registrate ahora</strong></a></td></tr>
          </table>
        </div>
        </form>
        
	</body>
</html