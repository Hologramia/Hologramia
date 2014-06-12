<? 
include("carrito.php"); 
?> 

<html> 
<head> 
   	<title>Introduce Producto</title> 
</head> 

<body> 

<? 
$_SESSION["ocarrito"]->imprime_carrito(); 
?> 
<br> 
<br> 
<a href="catalogo2.php">Volver</a> 

</body> 
</html> 