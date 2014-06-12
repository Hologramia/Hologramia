
<?php class carrito { 
   	//atributos de la clase 
    var $num_productos; 
    var $array_id_producto; 
    var $array_descripcion; 
    var $array_precio; 

   	//constructor. Realiza las tareas de inicializar los objetos cuando se instancian 
   	//inicializa el numero de productos a 0 
   	function carrito () { 
       $this->num_productos=0; 
   	} 

   	//Introduce un producto en el carrito. Recibe los datos del producto 
   	//Se encarga de introducir los datos en los arrays del objeto carrito 
   	//luego aumenta en 1 el numero de productos 
   	function introduce_producto($id_producto,$descripcion,$precio){ 
      	 $this->array_id_prod[$this->num_productos]=$id_producto; 
      	 $this->array_nombre[$this->num_productos]=$nombre; 
      	 $this->array_precio[$this->num_productos]=$precio; 
      	 $this->num_productos++; 
   	} 

   	//Muestra el contenido del carrito de la compra 
   	//ademas pone los enlaces para eliminar un producto del carrito 
   	function imprime_carrito(){ 
      	 $suma = 0; 
      	 echo '<table border=1 cellpadding="3"> 
      	 <tr> 
      	 <td><b>Nombre producto</b></td> 
      	 <td><b>Precio</b></td> 
      	 <td> </td> 
      	 </tr>'; 
      	 for ($i=0;$i<$this->num_productos;$i++){ 
         	 if($this->array_id_producto[$i]!=0){ 
            	 echo '<tr>'; 
            	 echo "<td>" . $this->array_nombre[$i] . "</td>"; 
            	 echo "<td>" . $this->array_precio[$i] . "</td>"; 
            	 echo "<td><a href='eliminar_producto.php?linea=$i'>Eliminar producto</td>"; 
            	 echo '</tr>'; 
            	 $suma += $this->array_precio[$i]; 
         	 } 
      	 } 
      	 //muestro el total 
      	 echo "<tr><td><b>TOTAL:</b></td><td> <b>$suma</b></td><td> </td></tr>"; 
      	 //total más IVA 
      	 echo "<tr><td><b>IVA (16%):</b></td><td> <b>" . $suma * 1.16 . "</b></td><td> </td></tr>"; 
      	 echo "</table>"; 
   	} 

   	//elimina un producto del carrito. recibe la linea del carrito que debe eliminar 
   	//no lo elimina realmente, simplemente pone a cero el id, para saber que esta en estado retirado
   	function elimina_producto($linea){ 
      	 $this->array_id_producto[$linea]=0; 
   	} 
}
?>
