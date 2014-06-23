<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CATALOGO</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php 
$key_words = 'verde pantalon rojo corbata azul corbata verde chaqueta morado pantalon blusa franela franelilla zapatos camisa ';
 
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

<div align="center">
  <h1><strong>PRODUCTOS DESTACADOS</strong></h1>

<?php
$contador = 1; 
for ($x=1;$x<6; $x++){
while ($row = mysql_fetch_array($sql))
{
if ($contador >6) {
echo "<tr></tr>";
}
?>

<div id="caja">
  <div align="center">
    <table width="203" border="0">
      <tr>
        <td width="198"><div align="left"><a href="index.php?prod=<?php echo $row['id_producto']; ?>"><img src="<?php echo $row['imagen'];?>" width="133" height="170" /></a><img src="Imagens/ecommerce.jpg" width="52" height="44" align="top" />
            <div id="comprar"></div>
            <table width="197" border="0">
              <tr>
                <td width="58"><strong>Art&iacute;culo:</strong></td>
                <td width="129"><strong><?php echo $row['descripcion']; ?></strong></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td><strong>Precio:</strong></td>
                <td><?php echo $row['precio']; ?></td>
              </tr>
            </table>
            <div align="center"><img src="Imagens/H2.png" width="100" height="100" /></div>
            </div>
        </td>
      </tr>
    </table>
  </div>
  <p align="center"></p>
</div>

<?php
$contador++;

}
}
?> 
      
</body>
</html>