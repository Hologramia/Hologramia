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
		"params"=>array("class"=>"main log-in-main"),
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
	
	$explanationDiv = new HTMLElement(array(
		"tag"=>"div",
		"inside"=>"Por favor escribe tu correo electr&oacute;nico y contrase&ntilde;a para continuar."
	));
	
	$form = new HTMLElement(array(
		"tag"=>"form",
		"params"=>array("action"=>"","method"=>"post","class"=>"log-in-form")
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