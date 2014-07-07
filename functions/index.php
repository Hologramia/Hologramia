<?php

	//error_reporting(E_ALL);

	require_once('functions.php');
	
	//phpinfo();
?>

<!DOCTYPE html>
<html>
	<body>
	
	<h1>Prueba de productos filtrados por categor&iacute;a</h1>
	
	<h2>Par&aacute;metros:</h2>
	<form>
	
	<input type="text" name="q" value="<?php
		if (array_key_exists("q",$_GET)){
			print(htmlentities($_GET["q"],ENT_COMPAT | ENT_HTML401,"UTF-8"));
		}
	?>">

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

		<input type="checkbox" name="c<?php print($categories[$j]["id"]); ?>" value="1"<?php
		
			if (array_key_exists("c".$categories[$j]["id"],$_GET)){
				print(" checked");
			}
			
		?>/><?php print($categories[$j]["name"]); ?><br/>

<?php
			}

		}else{
			
?>
			
		<input type="radio" name="ct<?php print($catypes[$i]["id"]); ?>" value=""<?php
		
			if (array_key_exists("ct".$catypes[$i]["id"],$_GET)){
				print(($_GET["ct".$catypes[$i]["id"]].""=="")?" checked":"");
			}else{
				print(" checked");
			}
		
		?>/>(cualquier)<br/>
	
<?php

			$categories = DB::getAllCategoriesWithCatypeId($catypes[$i]["id"]);
			
			$num_categories = count($categories);
			
			for ($j=0;$j<$num_categories;$j+=1){
?>

		<input type="radio" name="ct<?php print($catypes[$i]["id"]); ?>" value="<?php print($categories[$j]["id"]); ?>"<?php
		
			if (array_key_exists("ct".$catypes[$i]["id"],$_GET)){
				print(($_GET["ct".$catypes[$i]["id"]].""==$categories[$j]["id"]."")?" checked":"");
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
	
	$categoryIdArray = array();
	
	foreach ($_GET as $key => $value) {
		if ($key){
			if (substr($key,0,2) === "ct") {
     			$id = substr($key,2)+0;
     			$catype = DB::getCategoryTypeById($id);
     			//print("found catype: ".$catype["name"]."</br>");
     			if ($catype){
     				$category = DB::getCategoryById($value+0);
     				//print("found category($value): ".$category["name"]."</br>");
     				if ($category){
     					if ($category["catype_id"]==$catype["id"]){
     						array_push($categoryIdArray,$category["id"]);
     						//print("<br/>added:$id.<br/>");
     					}else{
     						print("<br/>bad category match.<br/>");
     					}
     				}
     			}
			}elseif (substr($key,0,1) === "c"){
				$id = substr($key,1)+0;
     			$category = DB::getCategoryById($id+0);
     			//print("found lonely category($id): ".$category["name"]."</br>");
     			if ($category){
     				array_push($categoryIdArray,$id);
     				//print("<br/>added:$id.<br/>");
     			}
			}
		}
	}
	
	//var_dump($categoryIdArray);
	$q = "";
	if (array_key_exists("q",$_GET)){
		$q = $_GET["q"];
	}
	$products = DB::getProducts($q,$categoryIdArray,100,0);
	
	//var_dump($products);
	
	$num_products = count($products);
	
	for ($i=0;$i<$num_products;$i+=1){
		
?>

	<p><b>[<?php print($products[$i]["id"]); ?>]</b> <?php print($products[$i]["name"]); ?> (<?php print($products[$i]["description"]); ?>)</p>

<?php
		
	}
	
?>
	<br/>
	--
	
	<?php
	
		//DB::insertProduct("producto primero","un producto muy grande",20.45);
		//DB::insertProduct("producto SEGUNDO","un producto muy pendejo",200.45);
		//DB::insertProduct("producto tercero","un producto muy chiquito",201.45);
		//DB::insertProduct("producto cuarto","un producto muy muy",202.45);
	
		if (array_key_exists("do",$_GET) && $_GET["do"]=="yes"){
		
			//DB::insertProduct("un producto bueno","este producto es muy muy bueno",20.23); //8
			//DB::insertCategories("Color",array("Amarillo","Azul","Morado","Rojo","Verde"),TRUE); //2
			//$insert_ids = DB::insertCategories("Talla",array("XS","S","M","L","XL","XXL","U"),FALSE);
			//print("ids:");
			//var_dump($insert_ids);
			
			//DB::insertProduct("otro producto regular","este objeto es mas o menos regular",2000);
			
			//DB::insertProduct("producto cualquiera, quien sabe","no tiene nada especial",1000);
			
			
			//DB::addCategoryToProduct(8,8);
			//DB::addCategoryNameToProduct(8,"amarillo");
			
		
		/*
			print("<br/>inserting...");
			$insert_id = DB::insertProduct("perro","perro bonito",400.0);
			print("<br/>insert id:");
			var_dump($insert_id);
			print("<br/>getting...");
			$object = DB::getProductById($insert_id);
			print("<br/>got:");
			var_dump($object);
			print("<br/>adding category...");
			$catresult = DB::addCategoryToProduct($insert_id,1);
			print("<br/>got:");
			var_dump($catresult);
			print("<br/>removing...");
			$result = DB::removeProductWithId($insert_id);
			print("<br/>got:");
			var_dump($result);
			*/
			
			/*
			var_dump(DB::insertCategories("Color",array("Morado","Naranja"),TRUE));
			
			print("inserted!!!");*/
		}
	
	?>
	
	</body>

</html>