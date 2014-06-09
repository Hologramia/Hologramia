<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Filtros</title>
<style type="text/css">
</style>
</head>

<body>
  <div class="genero" id="selectores">
    <table width="123" border="0">
      <tr>
        <td width="70"><strong>GENERO</strong></td>
        <td width="37"><span class="Estilo2">
          <select name="sexo" class="required" id="genero">
            <option></option>
            <?php $cadbusca = "SELECT * FROM genero";
				
				$resultado = mysql_query($cadbusca,Conectar:: conexion());
				
				while ($row = mysql_fetch_array($resultado)){
					
			?>
            <option value="<?php echo $row["Id_genero"];?>"><?php echo $row["Genero"];?></option>
            <?php }?>
          </select>
        </span></td>
      </tr>
      <tr>
        <td><strong>TALLA</strong></td>
        <td><span class="Estilo2">
          <select name="sexo2" class="required" id="talla">
            <option></option>
            <?php $cadbusca = "SELECT * FROM talla";
				
				$resultado = mysql_query($cadbusca,Conectar:: conexion());
				
				while ($row = mysql_fetch_array($resultado)){
					
			?>
            <option value="<?php echo $row["Id_talla"];?>"><?php echo $row["Talla"];?></option>
            <?php }?>
          </select>
        </span></td>
      </tr>
    </table>
</div>
</body>
</html>
