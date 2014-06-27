<?php

	require_once('functions.php');
?>

<!DOCTYPE html>
<html>
	<body>
	
	<h1>Prueba de productos filtrados por categor&iacute;a</h1>
	
	<h2>Par&aacute;metros:</h2>
	<form>

<?php

	$catypes = DB::getAllCatypes();
	
	$num_catypes = count($catypes);
	
	for ($i=0;$i<$num_catypes;$i+=1){
?>

		<h2><?php print($catypes[$i]["name"]); ?></h2>
	
<?php
		if ($catypes[$i]["allows_multiple"]==1){
?>

<?php

			$categories = DB::getAllCategoriesWithCatypeId($catypes[$i]["id"]);
			
			$num_categories = count($categories);
			
			for ($j=0;$j<$num_categories;$j+=1){
?>

		<input type="checkbox" name="cat<?php print($catypes[$i]["id"]); ?>" value="<?php print($categories[$j]["id"]); ?>"<?php
		
			if (array_key_exists("cat".$catypes[$i]["id"],$_GET)){
				print(($_GET["cat".$catypes[$i]["id"]].""==$categories[$j]["id"]."")?" checked":"");
			}
			
		?>/><?php print($categories[$j]["name"]); ?><br/>

<?php
			}

		}else{
			
?>
			
		<input type="radio" name="cat<?php print($catypes[$i]["id"]); ?>" value=""<?php
		
			if (array_key_exists("cat".$catypes[$i]["id"],$_GET)){
				print(($_GET["cat".$catypes[$i]["id"]].""=="")?" checked":"");
			}else{
				print(" checked");
			}
		
		?>/>(cualquier)<br/>
	
<?php

			$categories = DB::getAllCategoriesWithCatypeId($catypes[$i]["id"]);
			
			$num_categories = count($categories);
			
			for ($j=0;$j<$num_categories;$j+=1){
?>

		<input type="radio" name="cat<?php print($catypes[$i]["id"]); ?>" value="<?php print($categories[$j]["id"]); ?>"<?php
		
			if (array_key_exists("cat".$catypes[$i]["id"],$_GET)){
				print(($_GET["cat".$catypes[$i]["id"]].""==$categories[$j]["id"]."")?" checked":"");
			}
		
		?>/><?php print($categories[$j]["name"]); ?><br/>

<?php
			}
			
		}
		
?>

		<br/><br/>

<?php
		
	}

?>

		<input type="submit" value="Aplicar filtro">

	</form>
	
	<h2>Resultados:</h2>
	
<?php
	
	$categoryIdArray = [];
	
	foreach ($_GET as $key => $value) {
		if ($key){
			if (substr($key,0,3) === "cat") {
     			$id = substr($key,3)+0;
     			$catype = DB::getCategoryTypeById($id);
     			if ($catype != NULL){
     				$category = DB::getCategoryById($value);
     				if ($category != NULL){
     					if ($category["catype_id"]==$catype["id"]){
     						array_push($categoryIdArray,$id);
     					}else{
     						print("bad category match.");
     					}
     				}
     			}
			}
		}
	}
	
	$products = DB::getProducts($categoryIdArray,100,0);
	
	
	
	$num_products = count($products);
	
	for ($i=0;$i<$num_products;$i+=1){
		
?>

	<p><b>[<?php print($products[$i]["id"]); ?>]</b> <?php print($products[$i]["name"]); ?></p>

<?php
		
	}
	
?>
	<br/>
	--
	
	<?php
	
		if ($_GET["do"]=="yes"){
			//DB::insertProduct("Zapato Pequeño","Muy chiquito este zapato",22.25);
			//DB::insertProduct("Zapato Grande","Este zapato es gigante",100.1);
			//$id = DB::insertProduct("Pantalon","Blue jean",10000);
			//print("insert id: ".$id);
			//$product = DB::getProductById(3);
			//var_dump($product);
			
			//$catype = DB::insertCategoryType("Talla",FALSE);
			//print("inserted catype: $catype");
			
			//$category = DB::insertCategory("Verde",4);
			//print("|inserted:");
			//var_dump($category);
		}
	
	?>
	
	</body>

</html>