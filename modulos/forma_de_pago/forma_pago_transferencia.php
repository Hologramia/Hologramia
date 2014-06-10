<?php 
include ("clases/conexion.php");
Include ("base.php")
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <div align="center">
    <p>Transferencia Bancaria</p>
    <table width="50%" border="1">
      <tr>
        <td width="46%">Usuario:
        <input name="textfield14" type="text" id="textfield14" size="63" /></td>
        <td width="6%" rowspan="2">&nbsp;</td>
        <td width="48%">Email:
        <input name="textfield2" type="text" id="textfield2" size="63" /></td>
      </tr>
      <tr>
        <td width="46%">Banco:        
          <select name="select" id="select">
        </select></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><p><strong>Direccion de Facturacion: </strong><strong>
          <textarea name="textarea" id="textarea" cols="70" rows="5"></textarea>
        </strong></p>
        <p></p></td>
        <td>&nbsp;</td>
        <td><p><strong>Direccion de Envio: </strong><strong>
          <textarea name="textarea2" id="textarea2" cols="70" rows="5"></textarea>
        </strong></p>
        <p></p></td>
      </tr>
      <tr>
        <td><p><strong>Codigo de Transferencia Bancaria:</strong></p>
        <p><strong>
          <input name="textfield3" type="text" id="textfield3" size="20" maxlength="11" />
        </strong></p></td>
        <td>&nbsp;</td>
        <td><p><strong>Verificacion del Codigo de Transferencia Bancaria:</strong></p>
        <p><strong>
          <input name="textfield4" type="text" id="textfield4" size="20" maxlength="11" />
        </strong></p></td>
      </tr>
      <tr>
        <td colspan="3"><div align="center">
          <input type="submit" name="button" id="button" value="Pagar" />
          <input type="reset" name="button3" id="button3" value="Borrar" />
  <input type="submit" name="button2" id="button2" value="Regresar" />
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
</form>
</body>
</html>