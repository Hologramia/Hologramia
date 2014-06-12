<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../css/estilos (2).css" rel="stylesheet" type="text/css" />

</head>

<body>
<div align="center">
  <?php 
$key_words    = 'verde pantalon rojo corbata azul corbata verde chaqueta morado pantalon blusa franela franelilla zapatos camisa ';
 
if(strlen($key_words)>1){//No realizamos búsqueda si la palabra es de un solo caracter
            if($key_words){
           
                        //Contamos el numero de palabras que incluye la búsqueda.
                        $frac    = explode(' ',$key_words);
                        $no                  = count($frac);
                       
                        //Si la búsqueda tiene una palabra utilizamos LIKE sino MATCH AGAINST.
                        if($no == 1){
$sql = mysql_query("SELECT id_producto,nombre,precio,imagen,descripcion FROM productos WHERE nombre LIKE '%$key_words%' OR precio LIKE '%$key_words%' OR descripcion LIKE '%$key_words%'",Conectar::conexion()) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
								   
                        }else{
$sql = mysql_query("SELECT id_producto,nombre,precio,imagen,descripcion, MATCH ( nombre,precio,descripcion ) AGAINST ( '$key_words' ) AS Score FROM productos WHERE MATCH ( nombre,precio,descripcion ) AGAINST ( '$key_words' ) ORDER BY Score DESC LIMIT 50",Conectar::conexion()) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
}
}
						}
?>
    
</div>
<h1 align="center"><strong>PRODUCTOS DESTACADOS</strong></h1>
<div align="center">
  <table width="100" border="1">
    <tr>
      <?php
$contador = 1; 
for ($x=1;$x<6; $x++){
while ($row = mysql_fetch_array($sql))
{
if ($contador > 6) {
echo "</tr><tr>";
}
?>
      <td width="150"><div align="center">
        <div align="right"><a href="javascript:alert('proximo a funcionar')"><img src="Imagens/carrito-de-compras.jpg" width="39" height="37" />
        </div>
        <div align="center"></div>
        <div align="right" id="redes">
        <div class="addthis_sharing_toolbox"></div></div>
        <p align="left"><a href="index.php?prod=<?php echo $row['id_producto']; ?>"> <img src="<?php echo $row['imagen'];?>" width="160" height="200" /></a></p>
        <div>
          <div align="left" id=""><strong>Art&iacute;culo:  <?php echo $row['descripcion']; ?></strong></div>
           </div>
        <div>
          <div align="left" id=""><strong>Precio:</strong>  <?php echo $row['precio']; ?></div>
           <div align="center"><a href="javascript:alert('proximo a funcionar')"><img src="Imagens/activo.png" width="80" height="75" /></a></div>
        </div>
          
        <?php
$contador++;
}
}
?>
          
  </tr></table>
</div>
</body>
</html>