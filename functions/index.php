<?php
	ob_start();
	
	
	session_start();
	
	
	
	/*session_unset();

	exit;*/

	require_once('functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	if (Helper::getArrayValue($local_data,"reset",FALSE)){
		session_unset();
		header("Location: ?");
		exit;
	}
	
	//var_dump($_SESSION);
	
	//Dictionary of hash=>array("key"=>key,"param"=>string_param)
	$action_dict = Helper::getArrayValue($_SESSION,"actions",array());
	
	$action_functions = array(
		"add-product-to-cart" => (function($product_id){
			//echo "f1";
			//exit;
			$cart_products = Helper::getArrayValue($_SESSION,"cart",array());
			$index = -1;
			foreach($cart_products as $i=>$value){
				if ($value["product_id"]===$product_id){
					$index = $i;
				}
			}
			if ($index==-1){
				$cart_products[] = array("product_id"=>$product_id,"count"=>1);
			}
			$_SESSION["cart"] = $cart_products;
		}),
		"remove-product-from-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getArrayValue($_SESSION,"cart",array());
			$index = -1;
			foreach($cart_products as $i=>$value){
				if ($value["product_id"]===$product_id){
					$index = $i;
				}
			}
			if ($index!=-1){
				array_splice($cart_products, $index, 1);
			}
			$_SESSION["cart"] = $cart_products;
		}),
		"decrement-product-in-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getArrayValue($_SESSION,"cart",array());
			$product = FALSE;
			$index = -1;
			foreach($cart_products as $i=>$value){
				if ($value["product_id"]===$product_id){
					$product = $value;
					$index = $i;
				}
			}
			if ($index != -1 && $product){
				if ($product["count"]<=1){
					array_splice($cart_products, $index, 1);
				}else{
					$product["count"] -= 1;
					$cart_products[$index] = $product;
				}
			}
			$_SESSION["cart"] = $cart_products;
		}),
		"increment-product-in-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getArrayValue($_SESSION,"cart",array());
			$product = FALSE;
			$index = -1;
			foreach($cart_products as $i=>$value){
				if ($value["product_id"]===$product_id){
					$product = $value;
					$index = $i;
				}
			}
			if ($index != -1 && $product){
				$product["count"] += 1;
				$cart_products[$index] = $product;
			}
			$_SESSION["cart"] = $cart_products;
		})
		
	);
	
	if ($currentActionKeyArray = Helper::getArrayValue($local_data,"action",FALSE)){
		$currentActionKeyArray = explode(",",$currentActionKeyArray);
		foreach ($currentActionKeyArray as $currentActionKey){
			if ($actionArray = Helper::getArrayValue($action_dict,$currentActionKey,FALSE)){
				if ($function = Helper::getArrayValue($action_functions,$actionArray["key"],FALSE)){
					//echo "running:".$actionArray["key"];
					$function($actionArray["param"]);
				}
				unset($action_dict[$currentActionKey]);
			}
		}
		$_SESSION["actions"] = $action_dict;
		header("Location: ?".Helper::urlData(Helper::arrayMinusKeys($local_data,array("action"))));
		exit;
	}
	
	//Helper::updateActionDictionary($action_dict,);
	
	
	$categoryIdArray = array_key_exists("cats",$local_data)?DB::categoryIdsFromString($local_data["cats"]):array();
	$categoryIdBooleanArray = Helper::getBooleanArray($categoryIdArray);
	$q = array_key_exists("q",$local_data)?$local_data["q"]:"";
	$resultLimit = 1000;
	$resultOffset = 0;
	
	$products = DB::getProducts($q,$categoryIdArray,$resultLimit,$resultOffset);
	
	$existingCategoriesBooleanArray = array();
	
	foreach($products as $product){
		$productCategories = DB::getCategoryIdsForProduct($product["id"]);
		foreach($productCategories as $prodcat){
			$existingCategoriesBooleanArray[$prodcat["category_id"]] = TRUE;
		}
	}
	
	foreach($categoryIdBooleanArray as $key=>$value){
		$existingCategoriesBooleanArray[$key] = TRUE;
	}
	/*
	$a = array("a"=>"b","c"=>"d");
	$b = array("a"=>"b","c"=>"d");
	
	if ($a===$b){
		echo ("(a=b)");
	}
	*/
	//Stylesheet
	$styleTag = new HTMLElement(array(
		"insideFunction" => (function(){

$search_bar_height = 30;
$search_bar_width = 200;
$search_button_width = 60;
$search_bar_button_padding = 0;

$light_gray_color = "#cccccc";

$medium_gray_color = "#aaaaaa";

?>
<style type="text/css">

			body{
				font-family:'Trebuchet MS';
			}

			h1{
				display:inline;
				border:0px;
				padding:0px;
				margin:0px;
				margin-right:10px;
			}
			
			#top-banner{
				/*background-color:red;*/
				margin-bottom:10px;
			}
			
			form{display:inline;margin:0px;padding:0px;border:0px;}
			
			#search-box-container
			{
				/*background-color:blue;*/
				position:relative;
				display:inline-block;
				height:<?php print($search_bar_height); ?>px;
				width:<?php print($search_bar_width+$search_button_width+$search_bar_button_padding); ?>px;
			}
			
			#search-box-container>input[type=text]{
				position:absolute;
				top:0px;
				left:0px;
				height:<?php print($search_bar_height); ?>px;
				padding:0px; margin:0px;
				border-style:solid;border-color:<?php print($light_gray_color); ?>;border-width:1px;
				width:<?php print($search_bar_width); ?>px;
				font-size:15px;
				display:inline-block;
				z-index:100;
			}
			#search-box-container>input[type=submit]{
				position:absolute;
				top:0px;
				right:0px;
				height:<?php print($search_bar_height+2); ?>px;
				padding:0px;
				width:<?php print($search_button_width); ?>px;
				margin:0px;
				border-style:solid;border-color:<?php print($medium_gray_color); ?>;border-width:1px;
				background-color:<?php print($medium_gray_color); ?>;
				background-image:url(search-white.png);
				background-position:center;
				background-repeat:no-repeat;
				display:inline-block;
				cursor:pointer;
			}
			
			#filters, #right-column
			{
				border-style:solid;
				border-width:2px;
				border-color:rgb(230,230,230);
				padding:10px;
				background-color:white;
				border-radius:6px;
			}
			
			#filters{
				float:left;
				width:150px;
				margin-right:5px;
			}
			
			#right-column{
				width:150px;
				float:right;
				margin-left:5px;
			}
			
			#right-column>div{
				border-style:solid;
				border-width:3px;
				border-color:rgb(230,230,230);
				padding:3px;
				margin-top:3px;
				border-radius:6px;
			}
			
			h2{
				padding:0px;
				margin:0px;
			}
			
			#product-list{
				height:500px;
			}
			
			#product-list>div{
				display:inline-block;
				border-style:solid;
				border-width:0px;
				border-color:light-gray;
				background-color:rgb(240,240,240);
				padding:5px;
				border-radius:6px;
				width:150px;
				height:220px;
				margin:5px;
				margin-top:0px;
				margin-bottom:10px;
				vertical-align:middle;
				overflow:hidden;
			}
			
			#product-list>div>h3{
				text-align:center;
			}
			
			.product-thumb{
				display:block;
				height:50px;
				background-size: auto 100%;
				background-repeat: no-repeat;
				background-position:center;
			}

			.catype-title{
				font-weight:bold;
				margin-top:10px;
			}
			
			.category-box {
				margin-top:3px;
				padding:3px;
				border-style:solid;
				border-width:3px;
				border-color:rgb(230,230,230);
				overflow:hidden;
				position:relative;
				border-radius:6px;
			}
			
			.category-title {
				color:rgb(230,230,230);
			}
			
			.category-title + a, .category-title + a + a {
				position:absolute;
				top:3px;
				bottom:3px;
				right:3px;
			}
			
			.category-title + a{
				display:block;
				background-color:rgb(100,240,100);
				text-decoration:none;
				color:white;
				padding-right:4px;
				padding-left:4px;
				border-radius:10px;
				font-weight:bold;
			}
		
			.category-title + a + a {
				display:none;
				background-color:rgb(240,100,100);
				text-decoration:none;
				color:white;
				padding-right:4px;
				padding-left:4px;
				border-radius:10px;
				font-weight:bold;
				transform:rotate(45deg);
				-ms-transform:rotate(45deg); /* IE 9 */
				-webkit-transform:rotate(45deg); /* Opera, Chrome, and Safari */
			}
			
			.category-title[data-selected]{
				color:rgb(100,120,100);
			}
			
			.category-title[data-selected] + a{
				display:none;
			}
			
			.category-title[data-selected] + a + a{
				display:block;
			}
</style>
<?php

	
		})
	));
	
	//Classic elements
	$document = new HTMLElement();
	$doctype = new HTMLElement("!DOCTYPE html");
	$html = new HTMLElement("html");
	$body = new HTMLElement("body");
	$head = new HTMLElement("head");
	$head->addChildElement(array(new HTMLElement(array("tag"=>"title","inside"=>"Hologramia")),$styleTag));
	$document->addChildElement(array($doctype,$head,$body));
	$head->addChildElement(new HTMLElement(array("tag"=>"title","inside"=>"Hologramia")));
	
	
	//Search bar
	$searchBar = new HTMLElement();
	$searchForm = new HTMLElement(array(
		"tag"=>"form",
		"params"=>array("action"=>"","method"=>"get","id"=>"search-form")
	));
	$searchBar->addChildElement($searchForm);
	$searchTextBoxContainer = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"search-box-container")
	));
	$searchTextBox = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text","name"=>"q","value"=>htmlentities($q))
	));
	$searchButton = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"submit","value"=>"")
	));
	$searchTextBoxContainer->addChildElement(array($searchTextBox,$searchButton));
	$hiddenFieldArray = HTMLElement::hiddenInputs(Helper::arrayMinusKeys($local_data,array("q")));
	/*$searchHiddenField = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"hidden","name"=>"cats","value"=>implode(",",$categoryIdArray))
	));*/
	$searchForm->addChildElement(array($searchTextBoxContainer));
	$searchForm->addChildElement($hiddenFieldArray);
	
	
	//Top banner
	$topBanner = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"top-banner"),
		"childElements"=>array(new HTMLElement(array(
			"tag"=>"h1",
			"inside"=>"Hologramia"
		)),$searchBar)
	));
	
	
	
	//Search filters
	$searchFilters = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"filters")
	));
	
	$filterTitle = new HTMLElement(array(
		"tag"=>"h2",
		"inside"=>"Filtros"
	));
	
	$searchFilters->addChildElement($filterTitle);
	
	$catypes = DB::getAllCatypes();
	$num_catypes = count($catypes);
	for ($i=0;$i<$num_catypes;$i+=1){
		$catype = $catypes[$i];
		
		$catypeBox = new HTMLElement(array(
			"tag"=>"div",
		));
		$catypeTitle = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"catype-title"),
			"inside"=>$catype["name"]
		));
		$catypeBox->addChildElement($catypeTitle);
		
		$categories = DB::getAllCategoriesWithCatypeId($catype["id"]);
		$num_categories = count($categories);
		$category_ids = array();
		foreach ($categories as $value){
			$category_ids[] = $value["id"];
		}
		$other_category_ids = array_diff($categoryIdArray,$category_ids);
		$multiple = $catype["allows_multiple"];
		
		/*if (!$multiple){
			foreach ($categories as $category0){
				if (Helper::arrayKeyIsTRUE($category0["id"],$categoryIdBooleanArray)){
					for ($j=0;$j<$num_categories;$j+=1){
						$category1_id = $categories[$j]["id"];
						$existingCategoriesBooleanArray[$category1_id] = TRUE;
					}
					break;
				}
			}
		}*/
		
		$num_good_categories = 0;
		for ($j=0;$j<$num_categories;$j+=1){
			$category = $categories[$j];
			
			/*if (!Helper::arrayKeyIsTRUE($category["id"],$existingCategoriesBooleanArray)){
				continue;
			}*/
			
			$num_good_categories += 1;
			$categoryBox = new HTMLElement(array(
				"tag"=>"div",
				"params"=>array("class"=>"category-box")
			));
			$catypeBox->addChildElement($categoryBox);
			
			$titleParams = array();
			
			if (Helper::arrayKeyIsTRUE($category["id"],$categoryIdBooleanArray)){
				$titleParams["data-selected"] = "TRUE";
			}
			
			$titleParams["class"] = "category-title";
			
			$categoryTitle = new HTMLElement(array(
				"tag" => "span",
				"params" => $titleParams,
				"inside" => $category["name"]
			));
			
			$categoryAddLink = new HTMLElement(array(
				"tag" => "a",
				"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("cats"=>implode(",",Helper::arrayUnion($multiple?$categoryIdArray:$other_category_ids,array($category["id"]))))))),
				"inside" => "+"
			));
			
			$categoryRemoveLink = new HTMLElement(array(
				"tag" => "a",
				"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("cats"=>implode(",",array_diff($categoryIdArray,array($category["id"]))))))),
				"inside" => "+"
			));
			$categoryBox->addChildElement(array($categoryTitle,$categoryAddLink,$categoryRemoveLink));
		}
		if ($num_good_categories>0){
			$searchFilters->addChildElement($catypeBox);
		}
	}
	
	
	
	//Product list
	$productList = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"product-list")
	));
	
	foreach ($products as $product){
		$productElement = new HTMLElement(array(
			"tag" => "div"
		));
		$productThumbnail = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"product-thumb","style"=>"background-image:url(".$product["thumb"].")")
		));
		$productName = new HTMLElement(array(
			"tag"=>"h3",
			"inside"=>$product["name"]
		));
		$productDescription = new HTMLElement(array(
			"tag"=>"div",
			"inside"=>$product["description"],
			"param"=>array("class"=>"product-description")
		));
		$productElement->addChildElement(array($productThumbnail,$productName,$productDescription));
		if (!Helper::array_path_exists(array("cart",NULL,"product_id",$product["id"]),$_SESSION)){
			$uniqueAddId = Holo::updateActionDictionary($action_dict,array("key"=>"add-product-to-cart","param"=>$product["id"]));
			$productAddLink = new HTMLElement(array(
				"tag" => "a",
				"params"=>array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueAddId)))),
				"inside"=>"[agregar]"
			));
			$productElement->addChildElement($productAddLink);
		}
		$productList->addChildElement($productElement);
	}
	
	
	//Cart
	$rightColumn = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"right-column"),
	));
	
	$cartTitle = new HTMLElement(array(
		"tag"=>"h2",
		"inside"=>"Carrito"
	));
	
	$rightColumn->addChildElement($cartTitle);
	
	if ($cartItems = Helper::getArrayValue($_SESSION,"cart",FALSE)){
		foreach($cartItems as $item){
			if ($product = DB::getProductById($item["product_id"])){
				$productElement = new HTMLElement(array(
					"tag"=>"div",
					"inside"=>$product["name"]
				));
				//DECREMENT
				if ($item["count"]>1){
					$uniqueDecrementId = Holo::updateActionDictionary($action_dict,array("key"=>"decrement-product-in-cart","param"=>$product["id"]));
					$productDecrementLink = new HTMLElement(array(
						"tag" => "a",
						"params"=>array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueDecrementId)))),
						"inside"=>"[-]"
					));
					$productElement->addChildElement($productDecrementLink);
				}
				//COUNT
				$countElement = new HTMLElement(array(
					"tag"=>"span",
					"inside"=>$item["count"]
				));
				$productElement->addChildElement($countElement);
				//INCREMENT
				$uniqueIncrementId = Holo::updateActionDictionary($action_dict,array("key"=>"increment-product-in-cart","param"=>$product["id"]));
				$productIncrementLink = new HTMLElement(array(
					"tag" => "a",
					"params"=>array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueIncrementId)))),
					"inside"=>"[+]"
				));
				$productElement->addChildElement($productIncrementLink);
				//REMOVE
				$uniqueRemoveId = Holo::updateActionDictionary($action_dict,array("key"=>"remove-product-from-cart","param"=>$product["id"]));
				$productRemoveLink = new HTMLElement(array(
					"tag" => "a",
					"params"=>array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueRemoveId)))),
					"inside"=>"[remover]"
				));
				$productElement->addChildElement($productRemoveLink);
				
				//Add product
				$rightColumn->addChildElement($productElement);
			}
		}
	}
	
	$body->addChildElement(array($topBanner,$searchFilters,$rightColumn,$productList));
	
	
	$_SESSION["actions"] = $action_dict;
	
	
	$document->display();

?>