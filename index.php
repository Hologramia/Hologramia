<?php include ("conexion.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hologramia</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.Estilo2 {font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
</style>
</head>
<body>
<?php include ("barra.php");?>
<div id="contenedor_izquierda">

  <td colspan="2"><?php include ("filtros.php")?><div align="center">
        <p><strong>ESTILO</strong></p>
    <p><?php include ("menu_estilos.php");?></p>
      </div></td>
    </tr>
  </table>
 
  <p><strong><strong>PUBLICIDAD</strong></strong></p>
  <p><?php include ("publicidad.php");?></p>
 
</div>
<div id="contenedor_secundario"><?php include "banner.php";?></div>
<div id="contenedor_principal"><?php include("filtro.php");?></div>
<div id="contenedor_derecha"><?php include "carrito.php";?></div>

<div id="contenedor_pie">Pie</div>
</body>
</html>
