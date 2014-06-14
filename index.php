<?php include ("Clases/class_conexion.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hologramia</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
</head>

<body id="cuerpo">
<?php include ("includes/barra.php")?>

<div id="contenedor_izquierda">
<?php include ("modulos/principales/filtros.php");?>
<?php include ("modulos/principales/slider.php");?>
<?php include ("modulos/principales/slider.php");?>
</div>


<div id="contenedor_principal">
<?php include("modulos/principales/banner.php");?>
<?php include ("modulos/principales/catalogo.php");?>
</div>

<div id="contenedor_derecha">
<?php include ("modulos/carrito/carrito.php");?>

<div id="contenedor_pie">
<?php include ("includes/desarrollo.php");?>



</body>
</html>
