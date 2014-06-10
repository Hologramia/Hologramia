<?php 
include ("conexion.php") ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
.Estilo2 {font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
body,td,th {
	color: #FFF;
}
body {
	background-color: #B0A8BD;
}
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <div align="center">
    <h3><strong>Transferencia Bancaria</strong></h3>
    <table width="65%">
      <tr>
        <td colspan="2"><strong>Nombre:
            <input name="textfield14" type="text" id="textfield14" size="63" />
        </strong></td>
        
      </tr>
      <tr>
        <td colspan="2"><strong>Email:
            <input name="textfield2" type="text" id="textfield2" size="65" />
        </strong></td>
       
      </tr>
      <tr>
        <td colspan="2"><p><strong>Banco: <span class="Estilo2">
          <select name="especialidad2" class="required" id="especialidad2">
            <option></option>
            <?php $buscar = "SELECT * FROM banco";

$resultado = mysql_query($buscar, Conectar::conexion());

while ($row = mysql_fetch_array($resultado)){?>
            <option value="<?php echo $row["id"];?>"><?php echo $row["descripcion"];?></option>
            <?php }?>
          </select>
          </span><span class="Estilo2"></span></strong> <strong>Codigo de Referencia :
            <input name="textfield5" type="text" id="textfield12" size="20" maxlength="20" />
          </strong><strong>Fecha:
          19 Mayo, 2014</strong></p></td>
      </tr>
      <tr>
        <td width="34%"><p><strong>N°de Factura:
          <input name="textfield5" type="text" id="textfield9" size="20" maxlength="11" />
        </strong></p></td>
        <td width="66%"><strong>Monto:
          <input name="textfield5" type="text" id="textfield10" size="20" maxlength="11" />
        </strong></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <p align="left"><strong>Direccion de Facturacion: </strong></p>
          <p align="left"><strong>
            <textarea name="textarea2" id="textarea2" cols="70" rows="5"></textarea>
          </strong></p>
        </div></td>
        
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <p align="left"><strong>Direccion de Envio: </strong></p>
          <p align="left"><strong>
            <textarea name="textarea" id="textarea" cols="70" rows="5"></textarea>
          </strong></p>
        </div></td>
      <tr>
        <td colspan="2"><input type="submit" name="button" id="button" value="Pagar" />
          <input type="reset" name="button3" id="button3" value="Borrar" />
        <input type="submit" name="button2" id="button2" value="Regresar" /></td>
      </table>
  </div>
</form>
</body>
</html>