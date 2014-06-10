<?php include ("Clases/conexion.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hologramia</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.Estilo2 {font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
</style>
<link href="css/estilos (2).css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php include ("includes/barra.php");?>


<div id="contenedor_izquierda">
<div id="filtros"> <?php include ("modulos/principales/filtros.php");?><?php include ("modulos/principales/slider.php");?> </div>
<div><?php include ("includes/menu_estilos.php");?></div>
</div>

<div id="contenedor_principal"><?php include("modulos/principales/banner.php");?><?php include ("modulos/principales/catalogo.php");?></div>


<div id="contenedor_derecha"><?php include ("modulos/principales/publicidad.php");?>  </div>

<div id="contenedor_pie">Pie</div>
</body>
</html>
