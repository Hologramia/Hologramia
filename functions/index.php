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
	
	$action_functions =
    array(
		"add-product-to-cart" => (function($product_id){
			if (($cart_id = Helper::getSessionValue("cart",FALSE)) === FALSE){
				$user = Holo::currentUser();
				if ($user===FALSE){
					$cart_id = DB::insertCart(0);
				}else{
					$cart_id = DB::insertCart($user["id"]);
				}
				if ($cart_id !== FALSE){
					$_SESSION["cart"]=$cart_id;
				}
			}
			//DO NOT JOIN THESE IFS AS IF-ELSE!
			if ($cart_id !== FALSE){
				if(($cart = DB::getCartById($cart_id)) !== FALSE){
					$cart_products = Holo::getCartProducts($cart["content"]);
					
					$index = -1;
					foreach($cart_products as $i=>$value){
						if ($value["product_id"]===$product_id){
							$index = $i;
						}
					}
					if ($index==-1){
						if (Holo::reserveProduct($product_id)){
							$cart_products[] = array("product_id"=>$product_id,"count"=>1);
							Holo::updateCart($cart_id,$cart_products);
						}
						
					}
					
				}
			}
		}),
		"remove-product-from-cart" => (function($product_id){
			if(($cart = Holo::getCurrentCart()) !== FALSE){
				$cart_products = Holo::getCartProducts($cart["content"]);
				$index = -1;
				foreach($cart_products as $i=>$value){
					if ($value["product_id"]===$product_id){
						$index = $i;
					}
				}
				if ($index!=-1){
					Holo::releaseProduct($product_id,$cart_products[$index]["count"]);
					array_splice($cart_products, $index, 1);
					if ($cart_products){
						
					}
					Holo::updateCart($cart["id"],$cart_products);
				}
					
			}
		}),
		"decrement-product-in-cart" => (function($product_id){
			if(($cart = Holo::getCurrentCart()) !== FALSE){
				$cart_products = Holo::getCartProducts($cart["content"]);
				$index = -1;
				$product = FALSE;
				foreach($cart_products as $i=>$value){
					if ($value["product_id"]===$product_id){
						$product = $value;
						$index = $i;
					}
				}
				if ($index!=-1 && $product !== FALSE){
					if ($product["count"]<=1){
						Holo::releaseProduct($product_id,$cart_products[$index]["count"]);
						array_splice($cart_products, $index, 1);
						Holo::updateCart($cart_id,$cart_products);
					}else{
						Holo::releaseProduct($product_id,1);
						$product["count"] -= 1;
						$cart_products[$index] = $product;
						Holo::updateCart($cart["id"],$cart_products);
					}
				}
					
			}
		}),
		"increment-product-in-cart" => (function($product_id){
			if(($cart = Holo::getCurrentCart()) !== FALSE){
				$cart_products = Holo::getCartProducts($cart["content"]);
				$index = -1;
				$product = FALSE;
				foreach($cart_products as $i=>$value){
					if ($value["product_id"]===$product_id){
						$product = $value;
						$index = $i;
					}
				}
				if ($index!=-1 && $product !== FALSE){
					if (Holo::reserveProduct($product_id)){
						$product["count"] += 1;
						$cart_products[$index] = $product;
						Holo::updateCart($cart["id"],$cart_products);
					}
				}
				
			}
		}),
		
		"calculate-shipping" => (function(){
			global $action_dict, $local_data;
			if (($cart=Holo::getCurrentCart())===FALSE){
				return;
			}
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
			if (($cart=Holo::getCurrentCart())===FALSE){
				return;
			}
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
				unset($action_dict[$currentActionKey]);
				if (($function = Helper::getArrayValue($action_functions,$actionArray["key"],FALSE)) !== FALSE){
					//echo "running:".$actionArray["key"];
					$function($actionArray["param"]);
				}
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
    $styleTag = new HTMLElement(
                                array(
                                      "tag"=>"link",
                                      "params"=>array(
                                                      "rel"=>"stylesheet",
                                                      "type"=>"text/css",
                                                      "href"=>"estilo/style.php"
                                                      )
                                      )
                                );
	
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
		"params"=>array("action"=>"","method"=>"get","class"=>"search-form")
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
			"inside"=>"Hologramia", "params"=>array("class"=>"main-header")
		)),$searchBar)
	));
	
	
	
	//Search filters
	$searchFilters = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("id"=>"filters")
	));
	
	$filterTitle = new HTMLElement(array(
		"tag"=>"h2",
		"inside"=>"Filtros", "params"=>array("class"=>"sides-header")
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
		
		
		if (Holo::isProductInCart($product["id"])){
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
		"tag"=>"h2", "params"=>array("class"=>"sides-header"),
		"inside"=>"<div class='carrito'></div>Carrito"
	));
	
	$rightColumn->addChildElement($cartTitle);
	
	if (($cartItems = Holo::getCurrentCartProducts()) !== FALSE && count($cartItems)>0){
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