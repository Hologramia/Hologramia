<?php 
$key_words    = 'verde pantalon rojo corbata azul corbata verde chaqueta morado pantalon';
 
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

<div align="center" id="filtros">
  <table width="206" border="1">
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
      <td width="100"><div align="center">
        <p align="center"><a href="index.php?prod=<?php echo $row['id_producto']; ?>"><img src="<?php echo $row['imagen'];?>" width="160" height="200" /></a></p>
        <div align="center">
          <table width="100" border="0">
            <tr>
              <td width="61"><strong>Art&iacute;culo:</strong></td>
              <td width="36"><?php echo $row['descripcion']; ?></td>
              </tr>
            <tr>
              <td><strong>Precio:</strong></td>
              <td><?php echo $row['precio']; ?></td>
              </tr>
          </table>
          
          <table width="100" border="0">
            <tr>
              <td><img src="Imagens/H.png" width="93" height="68" /></td>
              </tr>
          </table>
        </div>
      </div></td>
      <?php
$contador++;
}
}
?>
  </tr></table>
</div>
