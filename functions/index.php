<?php

	
	session_start();
	
	/*session_unset();

	exit;*/

	require_once('functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	var_dump($_SESSION);
	
	//Dictionary of hash=>array("key"=>key,"param"=>string_param)
	$action_dict = Helper::getArrayValue($_SESSION,"actions",array());
	
	$action_functions = array(
		"add-product-to-cart" => (function($product_id){
			echo "ADDING PRODUCT TO CART ...";
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
		})
	);
	
	if ($currentActionKey = Helper::getArrayValue($local_data,"action",FALSE)){
		$actionArray = $action_dict[$currentActionKey];
		if ($function = Helper::getArrayValue($action_functions,$actionArray["key"],FALSE)){
			$function($actionArray["param"]);
		}
		unset($action_dict[$currentActionKey]);
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
				vertical-align:top;
				padding-top:5px;
			}
			
			#search-box-container>input[type=text]{
				height:25px;
				padding:0px; margin:0px;
				border-style:solid;border-color:black;border-width:1px;
				margin-right:10px;
				width:200px;
				font-size:15px;
			}
			#search-box-container>input[type=submit]{
				height:25px;
				padding:0px;
				width:50px;
				margin:0px;
				border-style:solid;border-color:black;border-width:1px;
				background-color:rgb(230,230,230);
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
			
			#filters > h2{
				padding:0px;
				margin:0px;
			}
			
			#product-list{
				height:500px;
				padding-top:5px;
			}
			
			#product-list>div{
				display:inline-block;
				border-style:solid;
				border-width:0px;
				border-color:light-gray;
				background-color:rgb(240,240,240);
				padding:5px;
				border-radius:6px;
				width:100px;
				height:140px;
				margin:5px;
				vertical-align:middle;
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
				color:rgb(100,120,100);;
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
		"params"=>array("type"=>"submit","value"=>"Buscar")
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
			"tag" => "div",
			"inside" => $product["name"]
		));
		$uniqueAddId = Holo::updateActionDictionary($action_dict,array("key"=>"add-product-to-cart","param"=>$product["id"]));
		$productAddLink = new HTMLElement(array(
			"tag" => "a",
			"params"=>array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueAddId)))),
			"inside"=>"[agregar]"
		));
		$productElement->addChildElement($productAddLink);
		$productList->addChildElement($productElement);
	}
	
	
	//Right column
	$rightColumn = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"right-column"),
		"inside"=>"Carrito"
	));
	
	$body->addChildElement(array($topBanner,$searchFilters,$rightColumn,$productList));
	
	
	$_SESSION["actions"] = $action_dict;
	
	
	$document->display();

?>