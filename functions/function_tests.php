<?php

require_once('functions.php');

//JUSTO: Here you write your HTML/PHP testing code.

//$result=mysql_query( "INSERT INTO product (name, description, price) VALUES ("."'"."franela"."'"." ,"."'"."marca buena"."'"." ,500)" , DB::connection());

//$id=mysql_insert_id();


//echo $id;

//if (!$result) {
  //  die('Invalid query: ' . mysql_error());
//}


//echo DB::insertProduct("franelai","marca buenai","700");
//print_r(DB::getProductById("30"));

//echo DB::insertUser("Juan Pepei","justojavier@gmail.co2i","123804702i")

//print_r(DB::getUserById("36"));


//echo DB::insertCategoryType("Tallaii","0");

//print_r(DB::getCategoryTypeById("57"));


//echo DB::insertCategory("rojo","1");


//print_r(DB::getCategoryById("2"));

//echo DB::addCategoryToProduct("10","10");

//echo DB::removeProductWithId("5");

//echo DB::removeUserWithId("6");

//echo DB::removeCategoryWithId("5");

//echo DB::removeCatypeWithId("1");

//DB::removeCategoryWithId("6");

//print_r(DB::getCategoryIdsForProduct("1"));

//DB::getProducts(array("1", "2", "4"),"1","1");


//print_r(mysqli_fetch_array($result));

<<<<<<< HEAD
//DB::insertCategories("color","azul","1");	
=======
//print_r(DB::getAllCatypes());

//print_r(DB::getAllCategoriesWithCatypeId("1"));
	
//var_dump(DB::connection());
>>>>>>> origin/master
	
//echo "Hello world";

print_r(DB::getAllProducts());


?>