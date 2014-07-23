<?php

	require_once('functions.php');
	
	Helper::loadLocalData($local_data,$_GET);
	
	$categoryIdArray = array_key_exists("cats",$local_data)?DB::categoryIdsFromString($local_data["cats"]):array();
	$categoryIdBooleanArray = Helper::getBooleanArray($categoryIdArray);
	$q = array_key_exists("q",$local_data)?$local_data["q"]:"";
	$resultLimit = 1000;
	$resultOffset = 0;
	
	$products = DB::getProducts($q,$categoryIdArray,$resultLimit,$resultOffset);
	
	
	
	//Stylesheet
	$styleTag = new HTMLElement(array(
		"insideFunction" => (function(){

?>
<style type="text/css">
			.catype-title{
				font-weight:bold;
			}
			
			.category-title {
				color:gray;
			}
			
			.category-title + a{
				display:inline;
			}
		
			.category-title + a + a {
				display:none;
			}
			
			.category-title[data-selected]{
				color:black;
			}
			
			.category-title[data-selected] + a{
				display:none;
			}
			
			.category-title[data-selected] + a + a{
				display:inline;
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
	
	//Top banner
	$topBanner = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"top-banner"),
		"childElements"=>array(new HTMLElement(array(
			"tag"=>"h1",
			"inside"=>"Hologramia"
		)))
	));
	
	//Search bar
	$searchBar = new HTMLElement();
	$searchForm = new HTMLElement(array(
		"tag"=>"form",
		"params"=>array("action"=>"","method"=>"get")
	));
	$searchBar->addChildElement($searchForm);
	$searchTextBox = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text","name"=>"q","value"=>htmlentities($q))
	));
	$searchButton = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"submit","value"=>"Buscar")
	));
	$searchHiddenField = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"hidden","name"=>"cats","value"=>implode(",",$categoryIdArray))
	));
	$searchForm->addChildElement(array($searchTextBox,$searchButton,$searchHiddenField));
	
	//Search filters
	$searchFilters = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"filters")
	));
	
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
		$searchFilters->addChildElement($catypeBox);
		
		$categories = DB::getAllCategoriesWithCatypeId($catype["id"]);
		$num_categories = count($categories);
		$category_ids = array();
		foreach ($categories as $value){
			$category_ids[] = $value["id"];
		}
		$other_category_ids = array_diff($categoryIdArray,$category_ids);
		$multiple = $catype["allows_multiple"];
		for ($j=0;$j<$num_categories;$j+=1){
			$category = $categories[$j];
			
			$categoryBox = new HTMLElement(array(
				"tag"=>"div"
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
				"inside" => "filter"
			));
			
			$categoryRemoveLink = new HTMLElement(array(
				"tag" => "a",
				"params" => array("href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("cats"=>implode(",",array_diff($categoryIdArray,array($category["id"]))))))),
				"inside" => "remove"
			));
			$categoryBox->addChildElement(array($categoryTitle,$categoryAddLink,$categoryRemoveLink));
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
		$productList->addChildElement($productElement);
	}
	
	
	$body->addChildElement(array($topBanner,$searchBar,$searchFilters,$productList));
	
	$document->display();

?>