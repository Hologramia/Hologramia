<?php include ("../controladores/conexion.php");?>
<div class="mod">
	<div class="titulo">
    	<div class="titular fill5"><h2>Productos</h2></div>
        <div class="down-right-shadow"></div>
        <div class="down-left-shadow"></div>
    </div>

<div class="mod_content">
    <?php $registro = mysql_query("SELECT * FROM productos",Conectar::conexion()) or die ("Error en el select"); ?>

    	
	<?php
		$fila = 0;
	 	while ($reg = mysql_fetch_array($registro))
		{ 	if ($fila <= 0)
			{
				?>
			<div class="row mi_prod">
				<?php
            }
		?>      
			<div class="col-md-4">
        		<div class="producto sombra fill1">
        			<div class="central">
  						<div class="prod_miniatura">
      	<a href="producto.php?prod=<?php echo $reg['id_producto']; ?>"><?php echo "<img src='$reg[foto]' width='100%' height='100%'/>";?></a>
						</div>
                	</div>
                    <div class="interactivo">
                    	<div class="social fill5 banda"></div>
                        <div class="rating fill5 banda"></div><img src=""/>
                    </div>
                    <div class="right-block">
                    	<div class="ventana" style="width:100%; height:100%;">
                            <span class="nombreprecio pull-left">
                            	<h4 class="color6"><?php echo $reg['nombre']; ?></h4>
                                <h5 class="color6"><?php echo "Bs ".$reg['precio']; ?></h5>
                            </span>
                           	<div class="pull-right botones">
                                <div class="probar"></div>
                                <span class="pull-right carrito"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?php
			$fila++;
			if ($fila >= 3)
				{
   		 			echo '</div>';
		 			$fila = 0;
				}
		}
		?>
</div>