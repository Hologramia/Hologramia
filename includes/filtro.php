<?php 

$key_words    = 'verde pantalon rojo corbata azul corbata verde chaqueta morado pantalon vestido zapatos ';
 
if(strlen($key_words)>1){//No realizamos búsqueda si la palabra es de un solo caracter
            if($key_words){
           
                        //Contamos el numero de palabras que incluye la búsqueda.
                        $frac    = explode(' ',$key_words);
                        $no                  = count($frac);
                       
                        //Si la búsqueda tiene una palabra utilizamos LIKE sino MATCH AGAINST.
                        if($no == 1){
$sql = mysql_query("SELECT id_producto,nombre,precio,imagen,descripcion FROM producto WHERE nombre LIKE '%$key_words%' OR precio LIKE '%$key_words%' OR descripcion LIKE '%$key_words%'",Conectar::conexion()) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
								   
                        }else{
$sql = mysql_query("SELECT id_producto,nombre,precio,imagen,descripcion, MATCH ( nombre,precio,descripcion ) AGAINST ( '$key_words' ) AS Score FROM producto WHERE MATCH ( nombre,precio,descripcion ) AGAINST ( '$key_words' ) ORDER BY Score DESC LIMIT 50",Conectar::conexion()) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
}
}
						}
?>

<div align="center">
  <table width="100%" border="0" cellpadding="2">
    <tr>
      <td bgcolor="#F3F3F3"><div align="center">
        <h1><strong>Su Art&iacute;culo</strong></h1>
      </div></td>
    </tr>
  </table>
  <table border="2">
    <tr>
  <?php
$contador = 1; 
for ($x=1;$x<=7; $x++){
while ($row = mysql_fetch_array($sql))
{
if ($contador>=7) {
echo "</tr><tr>";

}
?>
  <td><a href="filtro.php?prod=<?php echo $row['id_producto']; ?>"><img src="<?php echo $row['imagen'];?>" width="185" height="250" /></a></br><?php echo $row['precio']; ?></br><?php echo $row['descripcion']; ?></td>
  <?php
$contador++;
}
}
?>
  </tr></table>
</div>
<p>&nbsp;</p>
