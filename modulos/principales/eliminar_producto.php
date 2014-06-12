<? 
include("carrito.php"); 
$_SESSION["ocarrito"]->elimina_producto($_GET["linea"]); 
?> 

<html> 
<head> 
   	<title>Introduce Producto</title> 
</head> 

<body> 

Producto eliminado. 
<br> 
<br> 
<br> 
<a href="catakogo2.php">- Volver</a> 
<br> 
<br> 
<a href="ver_carrito.php">- Ver carrito</a> 

</body> 
</html>