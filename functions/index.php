<?php
	require_once('functions.php');
?>

<!DOCTYPE html>
<html>
	<body>
	
	<h1>Prueba de productos filtrados por categor&iacute;a</h1>
	
	</h2>Par&aacute;metros:</h2>
	<form>

<?php

	$catypes = DB::getAllCatypes();
	
	$num_catypes = count($catypes);
	for ($i=0;i<$num_catypes;$i+=1){
?>

		<h2><?php print($catypes[$i]["name"]); ?></h2>
	
<?php
		if ($catypes[$i]["allows_multiple"]==1){
			
		}else{
			
		}
	}

?>

	</form>
	
	</h2>Resultados:</h2>
	
	
	
	</body>

</html>