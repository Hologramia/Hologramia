<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Filtros</title>
<style type="text/css">
.Estilo2 {font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
</style>
</head>

<body>
  <div class="genero">
    <p><strong>GENERO</strong><span class="Estilo2">
      <select name="sexo" class="required" id="sexo">
        <option></option>
        <?php $cadbusca = "SELECT * FROM genero";
				
				$resultado = mysql_query($cadbusca,Conectar:: conexion());
				
				while ($row = mysql_fetch_array($resultado)){
					
			?>
        <option value="<?php echo $row["id_genero"];?>"><?php echo $row["genero"];?></option>
        <?php }?>
      </select>
    </span></p>
    <p>&nbsp;</p>
    <p><strong>TALLA</strong><span class="Estilo2">
      <select name="sexo2" class="required" id="sexo2">
        <option></option>
        <?php $cadbusca = "SELECT * FROM talla";
				
				$resultado = mysql_query($cadbusca,Conectar:: conexion());
				
				while ($row = mysql_fetch_array($resultado)){
					
			?>
        <option value="<?php echo $row["id_talla"];?>"><?php echo $row["talla"];?></option>
        <?php }?>
      </select>
    </span></p>
    <p>&nbsp;</p>
    <p><strong>PRECIO</strong></p>
    <?php include ("slider.php");?>
</div>
</body>
</html>
