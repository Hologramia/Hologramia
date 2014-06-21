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

echo DB::removeProductWithId("30");



?>