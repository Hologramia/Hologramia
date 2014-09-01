<?php
	ob_start();
	
	
	session_start();
	
	
	
	/*session_unset();

	exit;*/

	require_once('../functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	if (($cart = Holo::getCurrentCartProducts()) === FALSE || count($cart)<1){
		header("Location: ../");
		exit;
	}elseif (($user = Holo::currentUser()) !== FALSE && ($shippingArea = Holo::currentShippingArea()) !== FALSE){
		Holo::computeShippingPrice();
	}else{
		$uniqueCheckOutId = Holo::updateActionDictionary($action_dict,array("key"=>"check-out","param"=>"2"));
		header("Location: ../?".Helper::urlData(array("action"=>$uniqueCheckOutId)));
		exit;
	}
	
    $styleTag = new HTMLElement(
                                array(
                                      "tag"=>"link",
                                      "params"=>array(
                                                      "rel"=>"stylesheet",
                                                      "type"=>"text/css",
                                                      "href"=>"../estilo/style.php"
                                                      )
                                      )
                                );
	
	//Classic elements
	$document = new HTMLElement();
	$doctype = new HTMLElement("!DOCTYPE html");
	$html = new HTMLElement("html");
	$body = new HTMLElement("body");
	$head = new HTMLElement("head");
	$head->addChildElement(array(new HTMLElement(array("tag"=>"title","inside"=>"Hologramia - Entrar")),$styleTag));
// 	$url = $_SERVER['REQUEST_URI']; //returns the current URL
// 	$parts = explode('/',$url);
// 	$dir = $_SERVER['SERVER_NAME'];
// 	for ($i = 0; $i < count($parts) - 1; $i++) {
//  		$dir .= $parts[$i] . "/";
// 	}
// 	$head->addChildElement(new HTMLElement(array(
// 		"tag"=>"base",
// 		"ends"=>FALSE,
// 		"params"=>array("href"=>$dir."/../");
// 	)));
	$document->addChildElement(array($doctype,$head,$body));
	
	$mainDiv = new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("class"=>"main","id"=>"main-div"),
	));
	
	$titleLink = new HTMLElement(array(
		"tag"=>"a",
		"params"=>array("href"=>"../")
	));
	
	$titleDiv = new HTMLElement(array(
		"tag"=>"h1",
		"inside"=>"Hologramia", "params"=>array("class"=>"centered-header")
	));
	
	$titleLink->addChildElement($titleDiv);
	
	// $explanationDiv = new HTMLElement(array(
// 		"tag"=>"div",
// 		"inside"=>"Selecciona un area de destino para estimar el precio del env&iacute;o. Podr&aacute;s confirmar o cambiar este destino al momento de finalizar tu compra."
// 	));
	
	$mainDiv->addChildElement(array($titleLink));
	
	
	
	$cartTitle = new HTMLElement(array(
		"tag"=>"h2",
		"inside"=>"<div class='carrito'></div>Finalizar Compra"
	));
	
	$mainDiv->addChildElement($cartTitle);
	
	if ($cartItems = Holo::getCurrentCartProducts()){
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
				// //DECREMENT
// 				if ($item["count"]>1){
// 					$uniqueDecrementId = Holo::updateActionDictionary($action_dict,array("key"=>"decrement-product-in-cart","param"=>$product["id"]));
// 					$productDecrementLink = new HTMLElement(array(
// 						"tag" => "a",
// 						"params"=>array("class"=>"decrement-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueDecrementId)))),
// 						"inside"=>""
// 					));
// 					$productElement->addChildElement($productDecrementLink);
// 				}
// 				//INCREMENT
// 				$uniqueIncrementId = Holo::updateActionDictionary($action_dict,array("key"=>"increment-product-in-cart","param"=>$product["id"]));
// 				$productIncrementLink = new HTMLElement(array(
// 					"tag" => "a",
// 					"params"=>array("class"=>"increment-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueIncrementId)))),
// 					"inside"=>""
// 				));
// 				$productElement->addChildElement($productIncrementLink);
// 				//REMOVE
// 				$uniqueRemoveId = Holo::updateActionDictionary($action_dict,array("key"=>"remove-product-from-cart","param"=>$product["id"]));
// 				$productRemoveLink = new HTMLElement(array(
// 					"tag" => "a",
// 					"params"=>array("class"=>"remove-cart-link","href"=>"?".Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueRemoveId)))),
// 					"inside"=>""
// 				));
// 				$productElement->addChildElement($productRemoveLink);
				
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
				$mainDiv->addChildElement($productElement);
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
			//error
			exit;
			
			// $linkWrapper = new HTMLElement(array(
// 				"tag"=>"div",
// 				"params"=>array("class"=>"cart-buttons-wrapper")
// 			));
// 			$uniqueShippingId = Holo::updateActionDictionary($action_dict,array("key"=>"calculate-shipping","param"=>""));
// 			$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueShippingId)));
// 			
// 			$shippingLink = new HTMLElement(array(
// 				"tag"=>"a",
// 				"inside"=>"Calcular precio del env&iacute;o",
// 				"params"=>array("class"=>"shipping-link","href"=>"?".$urlString)
// 			));
// 			
// 			
// 			
// 			$linkWrapper->addChildElement($shippingLink);
// 			$totalElement->addChildElement($linkWrapper);
			
		}
		// 
// 		$linkWrapper = new HTMLElement(array(
// 			"tag"=>"div",
// 			"params"=>array("class"=>"cart-buttons-wrapper")
// 		));
// 		
// 		$uniqueCheckOutId = Holo::updateActionDictionary($action_dict,array("key"=>"check-out","param"=>""));
// 		$urlString = Helper::urlData(Helper::updatedArray($local_data,array("action"=>$uniqueCheckOutId)));
// 		$checkoutLink = new HTMLElement(array(
// 			"tag"=>"a",
// 			"inside"=>"Finalizar compra :)",
// 			"params"=>array("class"=>"shipping-link checkout-link","href"=>"?".$urlString)
// 		));
// 		
// 		$linkWrapper->addChildElement($checkoutLink);
// 		$totalElement->addChildElement($linkWrapper);
		
		
		
		
		
		
		
		
		$mainDiv->addChildElement($totalElement);
		
	}
	
	
	
	$body->addChildElement($mainDiv);
	
	$document->display();
	
	
?>