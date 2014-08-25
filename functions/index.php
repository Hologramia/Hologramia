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
	$action_dict = Helper::getSessionValue("actions",array());
	
	$action_functions = array(
		"add-product-to-cart" => (function($product_id){
			//echo "f1";
			//exit;
			$cart_products = Helper::getSessionValue("cart",array());
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
			unset($_SESSION["shipping-price"]);
		}),
		"remove-product-from-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getSessionValue("cart",array());
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
			Holo::unsetShippingPrice();
		}),
		"decrement-product-in-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getSessionValue("cart",array());
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
			Holo::unsetShippingPrice();
		}),
		"increment-product-in-cart" => (function($product_id){
			//echo "f2";
			//exit;
			$cart_products = Helper::getSessionValue("cart",array());
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
			Holo::unsetShippingPrice();
		}),
		"calculate-shipping" => (function(){
			global $action_dict, $local_data;
			if (($user = Holo::currentUser()) !== FALSE){
				if (($shippingArea = Holo::currentShippingArea()) !== FALSE){
					Holo::computeShippingPrice();
				}else{
					$uniqueShippingId = Holo::updateActionDictionary($action_dict,array("key"=>"calculate-shipping","param"=>"1"));
					$url = "../?".Helper::urlData(Helper::updatedArray(Helper::arrayMinusKeys($local_data,array("action")),array("action"=>$uniqueShippingId)));
					$uniqueURLId = Holo::saveURL($url);
					$_SESSION["actions"] = $action_dict;
					header("Location: destino/?".Helper::urlData(array("url"=>$uniqueURLId)));
					exit;
				}	
			}else{
				$uniqueShippingId = Holo::updateActionDictionary($action_dict,array("key"=>"calculate-shipping","param"=>"1"));
				$url = "../?".Helper::urlData(Helper::updatedArray(Helper::arrayMinusKeys($local_data,array("action")),array("action"=>$uniqueShippingId)));
				$uniqueURLId = Holo::saveURL($url);
				$_SESSION["actions"] = $action_dict;
				header("Location: entrar/?".Helper::urlData(array("url"=>$uniqueURLId)));
				exit;
			}
		}),
		"check-out" => (function(){
			global $action_dict, $local_data;
			if (($user = Holo::currentUser()) !== FALSE){
				if (($shippingArea = Holo::currentShippingArea()) !== FALSE){
					Holo::computeShippingPrice();
					$_SESSION["actions"] = $action_dict;
					header("Location: comprar/");
					exit;
				}else{
					$uniqueCheckOutId = Holo::updateActionDictionary($action_dict,array("key"=>"check-out","param"=>"1"));
					$url = "../?".Helper::urlData(Helper::updatedArray(Helper::arrayMinusKeys($local_data,array("action")),array("action"=>$uniqueCheckOutId)));
					$uniqueURLId = Holo::saveURL($url);
					$_SESSION["actions"] = $action_dict;
					header("Location: destino/?".Helper::urlData(array("url"=>$uniqueURLId)));
					exit;
				}	
			}else{
				$uniqueCheckOutId = Holo::updateActionDictionary($action_dict,array("key"=>"check-our","param"=>"1"));
				$url = "../?".Helper::urlData(Helper::updatedArray(Helper::arrayMinusKeys($local_data,array("action")),array("action"=>$uniqueCheckOutId)));
				$uniqueURLId = Holo::saveURL($url);
				$_SESSION["actions"] = $action_dict;
				header("Location: entrar/?".Helper::urlData(array("url"=>$uniqueURLId)));
				exit;
			}
		}),
		"log-out" => (function(){
			Holo::logOut();
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
	
	$allCats = DB::getAllCategories();
	$allCatypes = $allCats["catypes"];
	$allCategories = $allCats["categories"];
	$allCatStructure = $allCats["structure"];
	
	$categoryString = Helper::getArrayValue($local_data,"c","");

	$categoryIdArray = DB::catypeAndCategoryIdsFromString($categoryString,$allCats);
	$categoryIdYesArray = $categoryIdArray["yes"];
	$categoryIdNoArray = $categoryIdArray["no"];
	
	//var_dump($categoryIdArray);
	
	//exit;
	
	
	//$categoryIdArray = array_key_exists("cats",$local_data)?DB::categoryIdsFromString($local_data["cats"]):array();
	//$categoryIdBooleanArray = Helper::getBooleanArray($categoryIdArray);
	$categoryIdBooleanArray = array();
	
	
	foreach ($allCatStructure as $catype_id=>$category_ids){
		if (array_key_exists($catype_id,$categoryIdYesArray)){
			$filtered_category_ids = $categoryIdYesArray[$catype_id];
			$categoryIdBooleanArray[-$catype_id] = FALSE;
			foreach($category_ids as $category_id){
				$categoryIdBooleanArray[$category_id] = in_array($category_id,$filtered_category_ids);
			}
		}elseif (array_key_exists($catype_id,$categoryIdNoArray)){
			//echo("<br><br>catype $catype_id in NoArray<br><br>");
			$filtered_category_ids = $categoryIdNoArray[$catype_id];
			//var_dump($filtered_category_ids);
			$categoryIdBooleanArray[-$catype_id] = TRUE;
			foreach($category_ids as $category_id){
				$value = !in_array($category_id,$filtered_category_ids);
				//echo("<br><br>catype $category_id SET TO $value<br><br>");
				$categoryIdBooleanArray[$category_id] = $value;
			}
		}
		//echo("($catype_id)($filtered_category_ids)");
	}
	
	//var_dump($categoryIdBooleanArray);
	//exit;
	
	$q = array_key_exists("q",$local_data)?$local_data["q"]:"";
	$resultLimit = 1000;
	$resultOffset = 0;
	
	$products = DB::getProducts($q,$categoryIdArray,$resultLimit,$resultOffset);
	
	//var_dump($products);
	//exit;
	
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

$search_bar_height = 35;
$search_bar_width = 300;
$search_button_width = 60;
$search_bar_button_padding = 0;

$search_corner_radius = 4;

$almost_white_color = "#fdfdfd";
$very_light_gray_color = "rgb(243,243,243)";
$very_light_gray_trans_color = "rgba(243,243,243,0.7)";

$light_gray_color = "#cccccc";

$medium_gray_color = "#aaaaaa";

$selected_checkbox_color = "#44cc66";

$product_padding = 30;
$top_product_padding = 30;

$top_bar_height = 80;
$top_bar_bottom_margin = 10;
$filters_width = 170;
$cart_width = 250;

$logo_width = 200;



?>
<style type="text/css">

			body{
				font-family:'HelveticaNeue-Light';
				margin:0px;
				padding:0px;
				border:0px;
				background-color:<?php print($very_light_gray_color); ?>;
			}
			
			#content-body{
			}

			h1{
				display:inline-block;
				border:0px;
				padding:0px;
				margin:0px;
				margin-right:10px;
				font-weight:normal;
				color:rgba(0,0,0,0);
				background-image:url(logo.png);
				background-position:center;
				background-size:contain;
				background-repeat:no-repeat;
				height:<?php print($top_bar_height); ?>px;
				width:<?php print($logo_width); ?>px;
				vertical-align:middle;
			}
			
			h2{
				padding:0px;
				margin:0px;
				font-weight:normal;
				text-align:center;
			}
			
			h3{
				font-weight:normal;
				text-align:center;
				margin:0px;
				padding:0px;
			}
			
			h4{
				font-weight:normal;
				text-align:center;
				margin-top:5px;
				margin-bottom:5px;
			}
			
			#top-banner{
				position:fixed;
				padding:0px;
				top:0px;
				left:0px;
				right:0px;
				height:<?php print($top_bar_height); ?>px;
				background-color:<?php print($very_light_gray_color); ?>;
				z-index:50;
				box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
				-webkit-box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
				-moz-box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
			}
			
			#top-right-bar{
				position:fixed;
				top:10px;
				right:10px;
				padding:10px;
				z-index:1000;
				font-family:"HelveticaNeue-Medium"
			}
			
			a{
				text-decoration:none;
			}
			
			a:link{color:rgba(50,70,200,1)}
			a:active{color:rgba(50,70,200,1)}
			a:visited{color:rgba(50,70,200,1)}
			a:hover{color:rgba(50,70,200,1)}
			
			form{display:inline;margin:0px;padding:0px;border:0px;}
			
			#search-box-container
			{
				/*background-color:blue;*/
				position:relative;
				display:inline-block;
				vertical-align:middle;
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
				border-top-left-radius:<?php print($search_corner_radius); ?>px;
				border-bottom-left-radius:<?php print($search_corner_radius); ?>px;
			}
			#search-box-container>input[type=submit]{
				position:absolute;
				top:0px;
				right:0px;
				height:<?php print($search_bar_height+2); ?>px;
				padding:0px;
				width:<?php print($search_button_width); ?>px;
				margin:0px;
				border-style:solid;border-color:<?php print($light_gray_color); ?>;border-width:1px;
				background-color:<?php print($light_gray_color); ?>;
				background-image:url(magnifying.png);
				background-position:center;
				background-repeat:no-repeat;
				background-size: auto 70%;
				display:inline-block;
				cursor:pointer;
				border-top-right-radius:<?php print($search_corner_radius); ?>px;
				border-bottom-right-radius:<?php print($search_corner_radius); ?>px;
			}
			
			#filters, #right-column
			{
				border-style:solid;
				border-width:0px;
				border-radius:0px;
			}
			
			#filters h3, #right-column h3{
				color:#555;
				padding:3px;
			}
			
			#filters{
				position:absolute;
				left:0px;
				top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
				width:<?php print($filters_width); ?>px;
				margin-right:<?php print($product_padding/2); ?>px;
			}
			
			
			#right-column{
				position:absolute;
				right:0px;
				top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
				width:<?php print($cart_width); ?>px;
				margin-left:<?php print($product_padding/2); ?>px;
			}
			
			#right-column>div{
				border-style:solid;
				border-width:0px;
				border-color:rgb(230,230,230);
				border-radius:6px;
				position:relative;
				margin-top:10px;
				background-color:<?php print($medium_gray_color); ?>;
				background-color:white;
				padding:10px;
			}
			
			#filters h2{display:none;}
			
			.category-set{
				padding-top:7px;
				padding-bottom:7px;
				background-color:<?php print($almost_white_color); ?>;
				/*box-shadow: inset 0px 2px 2px #eaeaea;
				-webkit-box-shadow: inset 0px 2px 2px #eaeaea;
				-moz-box-shadow: inset 0px 2px 2px #eaeaea;*/
				border-radius:5px;
			}
			
			#product-list{
				position:absolute;
				left:<?php print($filters_width); ?>px;
				right:<?php print($cart_width); ?>px;
				top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
				min-height:500px;
				text-align:center;
				padding-bottom:20px;
			}
			
			#product-list>div{
				position:relative;
				display:inline-block;
				border-style:solid;
				border-width:0px;
				border-color:light-gray;
				background-color:white;
				padding:5px;
				border-radius:5px;
				width:165px;
				height:270px;
				margin:<?php print($product_padding/2); ?>px;
				margin-top:<?php print($top_product_padding); ?>px;
				margin-bottom:<?php print($top_product_padding-$product_padding); ?>px;
				vertical-align:middle;
				overflow:hidden;
				box-shadow: 0px 2px 2px #ddd;
				-webkit-box-shadow: 0px 2px 2px #ddd;
				-moz-box-shadow: 0px 2px 2px #ddd;
			}
			
			.product-name{
				text-align:center;
				padding-top:20px;
				padding-bottom:20px;
				overflow:hidden;
			}
			
			.product-price{
				text-align:left;
				position:absolute;
				left:0px;
				bottom:0px;
				background:white;
				width:100%;
				box-shadow: 0px -10px 10px #fff;
				-webkitbox-shadow: 0px -10px 10px #fff;
				-moz-box-shadow: 0px -10px 10px #fff;
			}
			
			.cart-link,.cart-link-no{
				z-index:40;
				position:absolute;
				right:5px;
				bottom:0px;
				height:30px;
				width:30px;
				background-image:url(cart.png);
				background-position:center;
				background-repeat:no-repeat;
				background-size:auto 100%;
			}
			
			.cart-link-no{
				pointer-events:none;
				opacity:0.3;
			}
			
			.product-thumb{
				display:block;
				height:130px;
				background-size: auto 100%;
				background-repeat: no-repeat;
				background-position:center;
				margin-top:10px;
			}
			
			.category-box {
				padding:3px;
				padding-left:10px;
				border-style:solid;
				border-width:0px;
				border-color:rgb(230,230,230);
				position:relative;
			}
			
			.category-title {
				color:#999999;
			}
			
			.category-title + a, .category-title + a + a {
				border-style:solid;
				border-width:1px;
				border-color:#dddddd;
				background-color:#dddddd;
				position:absolute;
				top:2px;
				right:10px;
				bottom:2px;
				width:18px;
				border-radius:5px;
			}
			
			/*a:empty, click to select a+a:selected, click to unselect*/
			.category-title + a{
				display:block;
				text-decoration:none;
				color:white;
				font-weight:bold;
			}
		
			.category-title + a + a {
				display:none;
				text-decoration:none;
				color:white;
				font-weight:bold;
			}
			
			.category-title[data-selected]{
				color:#000000;
			}
			
			.category-title[data-selected] + a{
				display:none;
			}
			
			.category-title[data-selected] + a + a{
				display:block;
				background-color:<?php print($selected_checkbox_color) ?>;
				border-color:<?php print($selected_checkbox_color) ?>;
				background-image:url(checkmark.png);
				background-size:auto 90%;
				background-repeat:no-repeat;
				background-position:center;
			}
			
			.cart-product-name{
				width:70%;
				min-height:50px;
				margin-bottom:5px;
			}
			
			.remove-cart-link{
				position:absolute;
				top:5px;
				right:5px;
				width:20px;
				height:20px;
				border-style:solid;
				border-width:0px;
				border-color:<?php print($medium_gray_color); ?>;
				border-radius:11px;
				background-image:url(redx.png);
				background-position:center;
				background-size:100% auto;
				background-repeat:no-repeat;
				background-color:white;
				
			}
			
			.cart-price{
				text-align:right;
				position:absolute;
				bottom:10px;
				right:10px;
			}
			
			.cart-calculation{
				color:<?php print($light_gray_color); ?>;
			}
			
			.increment-link, .decrement-link {
				display:inline-block;
				width:16px;
				height:16px;
				background-position:center;
				background-repeat:no-repeat;
				background-size:90% auto;
				vertical-align:middle;
				border-radius:5px;
				background-color:black;
			}
			
			.increment-link{
				margin-left:5px;
				background-image:url(plus.png)
			}
			
			.decrement-link {
				margin-left:5px;
				background-image:url(minus.png)
			}
			
			.carrito{
				display:inline-block;
				width:35px;
				height:35px;
				background-image:url(cart.png);
				background-position:center;
				background-size:100% auto;
				background-repeat:no-repeat;
				vertical-align:middle;
				margin-top:-7px;
				margin-right:5px;
			}
			
			.cart-total-container{
				overflow:auto;
			}
			
			.cart-subtotal-text{
				clear:both;
				float:left;
			}
			
			.cart-subtotal-value{
				clear:right;
				float:right;
			}
			
			.cart-buttons-wrapper{
				clear:both;
				position:relative;
				padding-top:10px;
			}
			
			.shipping-link{
				display:block;
				clear:both;
				text-decoration:none;
				color:white;
				background-color:<?php print($medium_gray_color) ?>;
				padding-top:10px;
				padding-bottom:10px;
				border-radius:6px;
				text-align:center;
			}
			
			.checkout-link{
				background-color:rgb(50,200,50);
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
	
// 	$url = $_SERVER['REQUEST_URI']; //returns the current URL
// 	$parts = explode('/',$url);
// 	$dir = $_SERVER['SERVER_NAME'];
// 	for ($i = 0; $i < count($parts) - 1; $i++) {
//  		$dir .= $parts[$i] . "/";
// 	}
// 	
// 	$head->addChildElement(new HTMLElement(array(
// 		"tag"=>"base",
// 		"ends"=>FALSE,
// 		"params"=>array("href"=>$dir)
// 	)));
	
	
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
	$searchForm->addChildElement(array($searchTextBoxContainer));
	//$hiddenFieldArray = HTMLElement::hiddenInputs(Helper::arrayMinusKeys($local_data,array("q")));
	//$searchForm->addChildElement($hiddenFieldArray);
	
	
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
	
	//THE CATEGORIES
	
	$catypes = DB::getAllCatypes();
	$num_catypes = count($catypes);
	for ($i=0;$i<$num_catypes;$i+=1){
		$catype = $catypes[$i];
		
		$catypeBox = new HTMLElement(array(
			"tag"=>"div",
		));
		$catypeTitle = new HTMLElement(array(
			"tag"=>"h3",
			"inside"=>$catype["name"]
		));
		$catypeBox->addChildElement($catypeTitle);
		
		$categories = DB::getAllCategoriesWithCatypeId($catype["id"]);
		$num_categories = count($categories);
		$category_ids = array();
		foreach ($categories as $value){
			$category_ids[] = $value["id"];
		}
		//$other_category_ids = array_diff($categoryIdArray,$category_ids);
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
		
		$categorySet = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"category-set")
		));
		
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
			$categorySet->addChildElement($categoryBox);
			
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
				"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("c"=>Holo::categoryStringByAdding($category["id"],$categoryIdArray,$allCats,$categoryString))))),
				//"inside" => "+"
			));
			
			$categoryRemoveLink = new HTMLElement(array(
				"tag" => "a",
				"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("c"=>Holo::categoryStringByRemoving($category["id"],$categoryIdArray,$allCats,$categoryString))))),
				//"inside" => "+"
			));
			$categoryBox->addChildElement(array($categoryTitle,$categoryAddLink,$categoryRemoveLink));
		}
		$num_good_categories += 1;
		$otherCategoryBox = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"category-box")
		));
		$categorySet->addChildElement($otherCategoryBox);
			
		$otherTitleParams = array();
			
		if (Helper::arrayKeyIsTRUE(-$catype["id"],$categoryIdBooleanArray)){
			$otherTitleParams["data-selected"] = "TRUE";
		}/*else{
			echo("<br><br><br><br><br>");
			echo("[".$catype["id"]."][");
			var_dump($categoryIdBooleanArray);
			echo("]<br><br><br><br><br>");
		}*/
		
					
		$otherTitleParams["class"] = "category-title";
			
		$otherCategoryTitle = new HTMLElement(array(
			"tag" => "span",
			"params" => $otherTitleParams,
			"inside" => "Otros"
		));
			
		$otherCategoryAddLink = new HTMLElement(array(
			"tag" => "a",
			"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("c"=>Holo::categoryStringByAdding(-$catype["id"],$categoryIdArray,$allCats,$categoryString))))),
				//"inside" => "+"
		));
		
		$otherCategoryRemoveLink = new HTMLElement(array(
			"tag" => "a",
			"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("c"=>Holo::categoryStringByRemoving(-$catype["id"],$categoryIdArray,$allCats,$categoryString))))),
				//"inside" => "+"
		));
		
		$otherCategoryBox->addChildElement(array($otherCategoryTitle,$otherCategoryAddLink,$otherCategoryRemoveLink));
		
		$catypeBox->addChildElement($categorySet);
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
			"params"=>array("class"=>"product-name"),
			"inside"=>htmlentities($product["name"])
		));
		$productPrice = new HTMLElement(array(
			"tag"=>"h3",
			"params"=>array("class"=>"product-price"),
			"inside"=>"&nbsp;Bs. ".$product["price"]
		));
// 		$productDescription = new HTMLElement(array(
// 			"tag"=>"div",
// 			"inside"=>$product["description"],
// 			"param"=>array("class"=>"product-description")
// 		));
		$productElement->addChildElement(array($productThumbnail,$productName,$productPrice));
		if (Helper::array_path_exists(array("cart",NULL,"product_id",$product["id"]),$_SESSION)){
			$urlString = "";
			$addClass = "cart-link-no";
		}else{
			$uniqueAddId = Holo::updateActionDictionary($action_dict,array("key"=>"add-product-to-cart","param"=>$product["id"]));
			$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueAddId)));
			$addClass = "cart-link";
		}
		$productAddLink = new HTMLElement(array(
			"tag" => "a",
			"params"=>array("class"=>$addClass,"href"=>"?".$urlString),
			"inside"=>""
		));
		$productElement->addChildElement($productAddLink);
		$productList->addChildElement($productElement);
	}
	
	
	//Cart
	$rightColumn = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"right-column"),
	));
	
	$cartTitle = new HTMLElement(array(
		"tag"=>"h2",
		"inside"=>"<div class='carrito'></div>Carrito"
	));
	
	$rightColumn->addChildElement($cartTitle);
	
	if ($cartItems = Helper::getSessionValue("cart",FALSE)){
		$totalPrice = 0;
		foreach($cartItems as $item){
			if ($product = DB::getProductById($item["product_id"])){
				$productElement = new HTMLElement(array(
					"tag"=>"div"
				));
				//NAME
				$productName = new HTMLElement(array(
					"tag"=>"div",
					"inside"=>htmlentities($product["name"]),
					"params"=>array("class"=>"cart-product-name")
				));
				$productElement->addChildElement($productName);
				//COUNT
				$countElement = new HTMLElement(array(
					"tag"=>"span",
					"inside"=>"(".$item["count"]." art&iacute;culo".(($item["count"]==1)?"":"s").")"
				));
				$productElement->addChildElement($countElement);
				//DECREMENT
				if ($item["count"]>1){
					$uniqueDecrementId = Holo::updateActionDictionary($action_dict,array("key"=>"decrement-product-in-cart","param"=>$product["id"]));
					$productDecrementLink = new HTMLElement(array(
						"tag" => "a",
						"params"=>array("class"=>"decrement-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueDecrementId)))),
						"inside"=>""
					));
					$productElement->addChildElement($productDecrementLink);
				}
				//INCREMENT
				$uniqueIncrementId = Holo::updateActionDictionary($action_dict,array("key"=>"increment-product-in-cart","param"=>$product["id"]));
				$productIncrementLink = new HTMLElement(array(
					"tag" => "a",
					"params"=>array("class"=>"increment-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueIncrementId)))),
					"inside"=>""
				));
				$productElement->addChildElement($productIncrementLink);
				//REMOVE
				$uniqueRemoveId = Holo::updateActionDictionary($action_dict,array("key"=>"remove-product-from-cart","param"=>$product["id"]));
				$productRemoveLink = new HTMLElement(array(
					"tag" => "a",
					"params"=>array("class"=>"remove-cart-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueRemoveId)))),
					"inside"=>""
				));
				$productElement->addChildElement($productRemoveLink);
				
				//PRICE
				$currentPrice = $product["price"]*$item["count"];
				$totalPrice += $currentPrice;
				$productPriceElement = new HTMLElement(array(
					"tag"=>"div",
					"params"=>array("class"=>"cart-price"),
					"inside"=>(($item["count"]==1)?"":"<span class='cart-calculation'>".$item["count"]." x ".$product["price"]." =</span><br/>")."Bs. ".$currentPrice
				));
				$productElement->addChildElement($productPriceElement);
				
				//Add product
				$rightColumn->addChildElement($productElement);
			}
		}
		
		$totalElement = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"cart-total-container")
		));
		
		$subtotalText = new HTMLElement(array(
			"tag"=>"div",
			"inside"=>"Subtotal:",
			"params"=>array("class"=>"cart-subtotal-text")
		));
		
		$subtotalValue = new HTMLElement(array(
			"tag"=>"div",
			"inside"=>"Bs. ".$totalPrice,
			"params"=>array("class"=>"cart-subtotal-value")
		));
		
		$totalElement->addChildElement(array($subtotalText,$subtotalValue));
		
		
		
		if (($shipping = Holo::currentShippingPrice()) !== FALSE){
			
			$shippingText = new HTMLElement(array(
				"tag"=>"div",
				"inside"=>"Env&iacute;o:",
				"params"=>array("class"=>"cart-subtotal-text")
			));
		
			$shippingValue = new HTMLElement(array(
				"tag"=>"div",
				"inside"=>"Bs. ".$shipping,
				"params"=>array("class"=>"cart-subtotal-value")
			));
			
			$totalText = new HTMLElement(array(
				"tag"=>"div",
				"inside"=>"Total:",
				"params"=>array("class"=>"cart-subtotal-text")
			));
		
			$totalValue = new HTMLElement(array(
				"tag"=>"div",
				"inside"=>"Bs. ".($totalPrice+$shipping),
				"params"=>array("class"=>"cart-subtotal-value")
			));
			
			$totalElement->addChildElement(array($shippingText,$shippingValue,$totalText,$totalValue));
			
		}else{
			
			$linkWrapper = new HTMLElement(array(
				"tag"=>"div",
				"params"=>array("class"=>"cart-buttons-wrapper")
			));
			$uniqueShippingId = Holo::updateActionDictionary($action_dict,array("key"=>"calculate-shipping","param"=>""));
			$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueShippingId)));
			
			$shippingLink = new HTMLElement(array(
				"tag"=>"a",
				"inside"=>"Calcular precio del env&iacute;o",
				"params"=>array("class"=>"shipping-link","href"=>"?".$urlString)
			));
			
			
			
			$linkWrapper->addChildElement($shippingLink);
			$totalElement->addChildElement($linkWrapper);
			
		}
		
		$linkWrapper = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"cart-buttons-wrapper")
		));
		
		$uniqueCheckOutId = Holo::updateActionDictionary($action_dict,array("key"=>"check-out","param"=>""));
		$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueCheckOutId)));
		$checkoutLink = new HTMLElement(array(
			"tag"=>"a",
			"inside"=>"Finalizar compra :)",
			"params"=>array("class"=>"shipping-link checkout-link","href"=>"?".$urlString)
		));
		
		$linkWrapper->addChildElement($checkoutLink);
		$totalElement->addChildElement($linkWrapper);
		
		
		
		
		
		
		
		
		$rightColumn->addChildElement($totalElement);
		
	}
	
	$contentBody = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"content-body")
	));
	
	$contentBody->addChildElement(array($searchFilters,$rightColumn,$productList));
	
	$body->addChildElement(array($topBanner,$contentBody));
	
// 	$body->addChildElement(new HTMLElement(array(
// 		"inside"=>"<div style='position:fixed;z-index:1000;top:5px;right:5px;'>Contacto:<br/>02123393608<br/>justo@hologramia.com</div>"
// 	)));

	if (($currentUser=Holo::currentUser()) !== FALSE){
		$logout = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("id"=>"top-right-bar"),
			"inside"=>"Conectado como ".htmlentities($currentUser["name"])."&nbsp;-&nbsp"
		));
		
		$uniqueLogoutId = Holo::updateActionDictionary($action_dict,array("key"=>"log-out","param"=>""));
		$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueLogoutId)));
		
		$logoutLink = new HTMLElement(array(
			"tag"=>"a",
			"params"=>array("href"=>"?".$urlString),
			"inside"=>"desconectarse"
		));
		$logout->addChildElement($logoutLink);
		$body->addChildElement($logout);
	}else{
		$login = new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("id"=>"top-right-bar")
		));
		
		$loginLink = new HTMLElement(array(
			"tag"=>"a",
			"params"=>array("href"=>"entrar/"),
			"inside"=>"entrar con tu cuenta"
		));
		
		$separator = new HTMLElement(array(
			"inside"=>"&nbsp;-&nbsp;"
		));
		
		$registerLink = new HTMLElement(array(
			"tag"=>"a",
			"params"=>array("href"=>"unirse/"),
			"inside"=>"unirse"
		));
		$login->addChildElement(array($loginLink,$separator,$registerLink));
		$body->addChildElement($login);
		
	}
	
	$_SESSION["actions"] = $action_dict;
	
	
	$document->display();

?>