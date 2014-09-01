<?php
	ob_start();
	
	
	session_start();
	
	
	
	/*session_unset();

	exit;*/

	require_once('../functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	$name_message = FALSE;
	$email_message = FALSE;
	$pass_message = FALSE;
	$pass2_message = FALSE;
	
	$cuenta_creada = FALSE;
	
	if (($url = Helper::getArrayValue($local_data,"url",FALSE)) === FALSE){
		$url = Helper::getArrayValue($_POST,"url",FALSE);
	}
	
	$name = FALSE;
	
	if (($email = Helper::getArrayValue($_POST,"email",FALSE)) !== FALSE){
		$name = Helper::getArrayValue($_POST,"name",FALSE);
		$pass = Helper::getArrayValue($_POST,"pass",FALSE);
		$pass2 = Helper::getArrayValue($_POST,"pass2",FALSE);
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			if (Holo::userEmailExists($email)){
				$email_message = "Este correo electr&oacute;nico ya existe en nuestra base de datos. <a href='../entrar/".(($url===FALSE)?"":"url=$url")."'>Haz click aqu&iacute; para entrar con esta cuenta.</a>";
			}elseif (strlen($pass)<6){
				$pass_message = "Tu contrase&ntilde;a debe tener al menos 6 caracteres.";
			}elseif ($pass !== $pass2){
				$pass2_message = "Debes escribir tu contrase&ntilde;a dos veces.";
			}elseif ($name===FALSE || strlen($name)<1){
				$name_message = "Debes escribir tu nombre.";
			}else{
				if (Holo::createAccount($name,$email,$pass)){
					$cuenta_creada = TRUE;
				}else{
					$name_message = "Ocurri&oacute; un error creando tu cuenta. Por favor intenta de nuevo";
				}
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
		"params"=>array("class"=>"main join-main"),
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
	
	
	
	if ($cuenta_creada){
		$urlArray = Helper::getSessionValue("urls",array());
		$actualURL = "../";
		if ($url !== FALSE && ($newActualURL = Helper::getArrayValue($urlArray,$url,FALSE)) !== FALSE){
			$actualURL = $newActualURL;
		}
	
		$explanationDiv = new HTMLElement(array(
			"tag"=>"div",
			"inside"=>"Tu cuenta ha sido creada. <a href=\"".$actualURL."\">Haz click aqu&iacute; para continuar</a>"
		));
	
		$mainDiv->addChildElement(array($titleLink,$explanationDiv));
		
	}else{
	
	$explanationDiv = new HTMLElement(array(
		"tag"=>"div",
		"inside"=>"Escribe tus datos abajo para crear una nueva cuenta en Hologramia."
	));
	
	$form = new HTMLElement(array(
		"tag"=>"form",
		"params"=>array("action"=>"","method"=>"post","class"=>"log-in-form")
	));
	
	$nameInput = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text","name"=>"name","placeholder"=>"nombre","value"=>(($name===FALSE)?"":$name))
	));
	
	$emailInput = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text","name"=>"email","placeholder"=>"e-mail","value"=>(($email===FALSE)?"":$email))
	));
	
	$passInput = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"password","name"=>"pass","placeholder"=>"contrase&ntilde;a")
	));
	
	$pass2Input = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"password","name"=>"pass2","placeholder"=>"contrase&ntilde;a de nuevo")
	));
	
	$submitButton = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"submit","value"=>"Crear Cuenta")
	));
	
	if ($name_message !== FALSE){
		$form->addChildElement(new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"error-message"),
			"inside"=>$name_message
		)));
	}
	
	$form->addChildElement($nameInput);
	
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
	
	if ($pass2_message !== FALSE){
		$form->addChildElement(new HTMLElement(array(
			"tag"=>"div",
			"params"=>array("class"=>"error-message"),
			"inside"=>$pass2_message
		)));
	}
	
	$form->addChildElement($pass2Input);
	
	$form->addChildElement($submitButton);
	
	$form->addChildElement(new HTMLElement(array(
		"tag"=>"div",
		"params"=>array("class"=>"normal-text"),
		"inside"=>"&iquest;Ya eres miembro de Hologramia? <a href=\"../entrar/?url=".(($url===FALSE)?"":$url)."\">Entra haciendo click aqu&iacute;.</a>"
	)));
	
	if ($url !== FALSE){
		$form->addChildElement(HTMLElement::hiddenInputs(array("url"=>$url)));
	}
	
	$mainDiv->addChildElement(array($titleLink,$explanationDiv,$form));
	
	}
	
	$body->addChildElement($mainDiv);
	
	$document->display();
	
	
?>