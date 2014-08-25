<?php
	ob_start();
	
	
	session_start();
	
	
	
	/*session_unset();

	exit;*/

	require_once('../functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	$email_message = FALSE;
	$pass_message = FALSE;
	
	if (($url = Helper::getArrayValue($local_data,"url",FALSE)) === FALSE){
		$url = Helper::getArrayValue($_POST,"url",FALSE);
	}
	
	if (($email = Helper::getArrayValue($_POST,"email",FALSE)) !== FALSE){
		$pass = Helper::getArrayValue($_POST,"pass",FALSE);
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			if (Holo::userEmailExists($email)){
				if (Holo::login($email,$pass)){
					$actualURL = "../";
					if ($url !== FALSE && ($newActualURL = Helper::getArrayValue(Helper::getSessionValue("urls",array()),$url,FALSE)) !== FALSE){
						$actualURL = $newActualURL;
					}
					header("Location: ".$actualURL);
					exit;
				}else{
					$pass_message = "Contrase&ntilde;a incorrecta.";
				}
			}else{
				$email_message = "Este correo electr&oacute;nico no existe en nuestra base de datos. <a href='../unirse/?".(($url===FALSE)?"":"url=$url")."'>Haz click aqu&iacute; para crear una cuenta.</a>";
			}
		}else{
			$email_message = "Correo electr&oacute;nico incorrecto.";
		}
		
	}
	
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
			
			.main{
				position:absolute;
				left:50%;
				top:50%;
   			 	-ms-transform: translate(-50%,-50%); /* IE 9 */
   	 			-webkit-transform: translate(-50%,-50%); /* Chrome, Safari, Opera */
    			transform: translate(-50%,-50%);
    			width:300px;
			}
			
			.main > div
			{
				text-align:center;
				font-weight:bold;
			}
			
			form
			{
				display:block;
				margin:0px;
				padding:0px;
				text-align:center;
			}
			
			form input[type='text'], form input[type='password']
			{
				display:block;
				margin:0px;
				padding:0px;
				width:100%;
				margin-top:10px;
				height:35px;
				font-size:14pt;
				border-style:solid;
				border-width:1px;
				border-color:<?php print($light_gray_color); ?>;
				border-radius:6px;
			}
			
			form input[type='submit']
			{
				display:block;
				margin:0px;
				padding:0px;
				width:100%;
				margin-top:10px;
				height:35px;
				border-style:solid;
				border-width:0px;
				border-radius:6px;
				font-size:18px;
				color:white;
				background-color:rgb(100,190,100);
				cursor:pointer;
			}
			
			.error-message
			{
				color:red;
				margin-top:10px;
				text-align:center;
			}
			
			.normal-text
			{
				margin-top:10px;
				text-align:center;
			}
			
			h1{
				color:rgba(0,0,0,0);
				background-image:url(../logo.png);
				background-position:center;
				background-size:contain;
				background-repeat:no-repeat;
				height:80px;
				margin:0px;
				margin-bottom:10px;
			}
			
			a {text-decoration:none}

			
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
		"params"=>array("class"=>"main"),
	));
	
	$titleLink = new HTMLElement(array(
		"tag"=>"a",
		"params"=>array("href"=>"../")
	));
	
	$titleDiv = new HTMLElement(array(
		"tag"=>"h1",
		"inside"=>"Hologramia"
	));
	
	$titleLink->addChildElement($titleDiv);
	
	$explanationDiv = new HTMLElement(array(
		"tag"=>"div",
		"inside"=>"Por favor escribe tu correo electr&oacute;nico y contrase&ntilde;a para continuar."
	));
	
	$form = new HTMLElement(array(
		"tag"=>"form",
		"params"=>array("action"=>"","method"=>"post")
	));
	
	$emailInput = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text","name"=>"email","placeholder"=>"e-mail","value"=>(($email===FALSE)?"":$email))
	));
	
	$passInput = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"password","name"=>"pass","placeholder"=>"contrase&ntilde;a")
	));
	
	$submitButton = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"submit","value"=>"Entrar")
	));
	
	if ($email_message !== FALSE){
		$form->addChildElement(new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"error-message"),
			"inside"=>$email_message
		)));
	}
	
	$form->addChildElement($emailInput);
	
	if ($pass_message !== FALSE){
		$form->addChildElement(new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"error-message"),
			"inside"=>$pass_message
		)));
	}
	
	$form->addChildElement($passInput);
	
	$form->addChildElement($submitButton);
	
	$form->addChildElement(new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("class"=>"normal-text"),
		"inside"=>"&iquest;Todav&iacute;a no eres miembro de Hologramia? <a href=\"../unirse/?url=".(($url===FALSE)?"":$url)."\">&Uacute;nete haciendo click aqu&iacute;.</a>"
	)));
	
	if ($url !== FALSE){
		$form->addChildElement(HTMLElement::hiddenInputs(array("url"=>$url)));
	}
	
	$mainDiv->addChildElement(array($titleLink,$explanationDiv,$form));
	
	$body->addChildElement($mainDiv);
	
	$document->display();
	
	
?>