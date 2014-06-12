<? 
include("Clases/carrito.php"); 
$_SESSION["ocarrito"]->introduce_producto($_GET["id_producto"], $_GET["nombre"], $_GET["precio"]); 
?> 
<html> 
<head> 
   	<title>Introduce Producto</title> 
</head> 
<body> 

Producto introducido. 
<br> 
<br> 
<a href="catalogo2.php">- Volver</a> 
<br> 
<br> 
<a href="ver_carrito.php">- Ver carrito</a> 

</body> 
</html>