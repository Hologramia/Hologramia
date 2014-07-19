<?php

	require_once('functions.php');
	
	//Classic elements
	$document = new HTMLElement();
	$doctype = new HTMLElement("!DOCTYPE html");
	$html = new HTMLElement("html");
	$body = new HTMLElement("body");
	$head = new HTMLElement("head");
	$document->addChildElement(array($doctype,$head,$body));
	$head->addChildElement(new HTMLElement(array("tag"=>"title","inside"=>"Hologramia")));
	
	//Specific elements
	$topBanner = new HTMLElement(array(
		"tag"=>"div",
		"id"=>"top-banner",
		"childElements"=>array(new HTMLElement(array(
			"tag"=>"h1",
			"inside"=>"Hologramia"
		)))
	));
	
	$searchBar = new HTMLElement();
	$searchTextBox = new HTMLElement(array(
		"tag"=>"input",
		"params"=>array("type"=>"text")
	));
	
	$searchBar->addChildElement(array($searchTextBox));
	
	$body->addChildElement(array($topBanner,$searchBar));
	
	$document->display();

?>