<?php
	ob_start();
	
	
	session_start();
	
	
	
	/*session_unset();

	exit;*/

	require_once('../functions.php');
	
	Helper::loadArrayIfNULL($local_data,$_GET);
	
	if (($url = Helper::getArrayValue($local_data,"url",FALSE)) === FALSE){
		$url = Helper::getArrayValue($_POST,"url",FALSE);
	}
	
	if (($area = Helper::getArrayValue($_GET,"area",FALSE)) !== FALSE){
		Holo::updateShippingArea($area);
		$actualURL = "../";
		if ($url !== FALSE && ($newActualURL = Helper::getArrayValue(Helper::getSessionValue("urls",array()),$url,FALSE)) !== FALSE){
			$actualURL = $newActualURL;
		}
		header("Location: ".$actualURL);
		exit;
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
				top:50px;
   			 	-ms-transform: translate(-50%,0); /* IE 9 */
   	 			-webkit-transform: translate(-50%,0); /* Chrome, Safari, Opera */
    			transform: translate(-50%,0);
    			width:400px;
    			padding-bottom:50px;
			}
			
			.main > div
			{
				text-align:center;
				margin-top:10px;
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
			
			.explanation
			{
				font-weight:bold;
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
		"params"=>array("class"=>"explanation"),
		"inside"=>"Selecciona un area de destino para estimar el precio del env&iacute;o. Podr&aacute;s confirmar o cambiar este destino al momento de finalizar tu compra."
	));
	
	$mainDiv->addChildElement(array($titleLink,$explanationDiv));
	
	$destinos = array(array("id"=>471,"name"=>'ABEJALES'),
array("id"=>25,"name"=>'ACARIGUA'),
array("id"=>45,"name"=>'ACHAGUAS'),
array("id"=>569,"name"=>'ADICORA'),
array("id"=>46,"name"=>'AEREOPUERTO CARACAS'),
array("id"=>47,"name"=>'AEROPUERTO (35 KM)'),
array("id"=>446,"name"=>'AEROPUERTO LAS FLORES'),
array("id"=>48,"name"=>'AGUA BLANCA'),
array("id"=>503,"name"=>'AGUA VIVA'),
array("id"=>49,"name"=>'AGUAS CALIENTES'),
array("id"=>50,"name"=>'AGUASAY'),
array("id"=>51,"name"=>'ALBARICO'),
array("id"=>53,"name"=>'ALTAGRACIA DE ORITUCO'),
array("id"=>55,"name"=>'AMUAY'),
array("id"=>24,"name"=>'ANACO'),
array("id"=>56,"name"=>'APARTADEROS (ESTADO MERIDA)'),
array("id"=>57,"name"=>'APURITO'),
array("id"=>58,"name"=>'ARAGUA DE BARCELONA'),
array("id"=>59,"name"=>'ARAGUA DE MATURIN'),
array("id"=>60,"name"=>'ARAIRA'),
array("id"=>502,"name"=>'ARAPUEY'),
array("id"=>61,"name"=>'ARAURE'),
array("id"=>451,"name"=>'AROA (CENTRO Y CURAIGUIRE)'),
array("id"=>586,"name"=>'AV. INTERCOMUNAL LAS CABILLAS'),
array("id"=>64,"name"=>'BACHAQUERO'),
array("id"=>65,"name"=>'BAHIA DE PLATA'),
array("id"=>524,"name"=>'BAILADORES'),
array("id"=>66,"name"=>'BAJO GRANDE'),
array("id"=>67,"name"=>'BARBACOAS'),
array("id"=>68,"name"=>'BARCELONA'),
array("id"=>33,"name"=>'BARINAS'),
array("id"=>69,"name"=>'BARINITAS'),
array("id"=>44,"name"=>'BARQUISIMETO'),
array("id"=>70,"name"=>'BARRANCAS'),
array("id"=>71,"name"=>'BARRANCAS DEL ORINOCO (EDO MONAGAS)'),
array("id"=>72,"name"=>'BEJUMA'),
array("id"=>73,"name"=>'BETIJOQUE'),
array("id"=>74,"name"=>'BIRUACA'),
array("id"=>75,"name"=>'BISCUCUY'),
array("id"=>76,"name"=>'BOCA DE AROA'),
array("id"=>77,"name"=>'BOCA DE POZO'),
array("id"=>510,"name"=>'BOCA DE TOCUYO'),
array("id"=>577,"name"=>'BOCA DE UCHIRE'),
array("id"=>78,"name"=>'BOCA DEL RIO'),
array("id"=>79,"name"=>'BOCONO'),
array("id"=>80,"name"=>'BOCONOITO'),
array("id"=>650,"name"=>'BOHORDAL'),
array("id"=>81,"name"=>'BORAURE (AV. PRINCIPAL, MELINTON CAMBERO)'),
array("id"=>473,"name"=>'BRAMON'),
array("id"=>82,"name"=>'BRUZUAL'),
array("id"=>571,"name"=>'BUCHUACO'),
array("id"=>83,"name"=>'BUENA VISTA'),
array("id"=>84,"name"=>'BURBUSAY'),
array("id"=>29,"name"=>'CABIMAS'),
array("id"=>7,"name"=>'CABUDARE'),
array("id"=>86,"name"=>'CAGUA'),
array("id"=>87,"name"=>'CAICARA DE MATURIN'),
array("id"=>541,"name"=>'CAICARA DEL ORINOCO'),
array("id"=>88,"name"=>'CAJA SECA'),
array("id"=>2,"name"=>'CALABOZO'),
array("id"=>90,"name"=>'CAMAGUAN'),
array("id"=>91,"name"=>'CAMATAGUA'),
array("id"=>97,"name"=>'CAMURI CHICO'),
array("id"=>98,"name"=>'CAMURI GRANDE'),
array("id"=>99,"name"=>'CANCHUNCHU'),
array("id"=>101,"name"=>'CANO ZANCUDO'),
array("id"=>551,"name"=>'CANTAGALLO (CASERIO)'),
array("id"=>102,"name"=>'CANTAURA'),
array("id"=>474,"name"=>'CAPACHO'),
array("id"=>103,"name"=>'CAPATARIDA'),
array("id"=>104,"name"=>'CAPITANEJO'),
array("id"=>426,"name"=>'CARABALLEDA'),
array("id"=>19,"name"=>'CARACAS'),
array("id"=>611,"name"=>'CARACOLITO'),
array("id"=>107,"name"=>'CARBONERO'),
array("id"=>108,"name"=>'CARIACO'),
array("id"=>109,"name"=>'CARIAQUITO'),
array("id"=>110,"name"=>'CARIPE'),
array("id"=>111,"name"=>'CARIPITO'),
array("id"=>42,"name"=>'CARORA'),
array("id"=>113,"name"=>'CARRE PANAMERICANA DEL KM 8 AL 30'),
array("id"=>582,"name"=>'CARRETERA LARA-ZULIA'),
array("id"=>585,"name"=>'CARRETERA N'),
array("id"=>115,"name"=>'CARRIZAL'),
array("id"=>20,"name"=>'CARUPANO'),
array("id"=>116,"name"=>'CARVAJAL'),
array("id"=>117,"name"=>'CASANAY'),
array("id"=>495,"name"=>'CASIGUA EL CUBO'),
array("id"=>425,"name"=>'CATIA LA MAR'),
array("id"=>118,"name"=>'CAUCAGUA (EXCEPTUANDO CAPAYA)'),
array("id"=>120,"name"=>'CAUJARAO'),
array("id"=>100,"name"=>'CAÑO TIGRE'),
array("id"=>649,"name"=>'CAÑO TIGRE'),
array("id"=>601,"name"=>'CEREZAL'),
array("id"=>121,"name"=>'CHABASQUEN'),
array("id"=>618,"name"=>'CHACARACUAL'),
array("id"=>122,"name"=>'CHAGUARAMAS'),
array("id"=>616,"name"=>'CHAGUARAMAS DE LOERO'),
array("id"=>599,"name"=>'CHAMARIAPA'),
array("id"=>26,"name"=>'CHARALLAVE'),
array("id"=>123,"name"=>'CHARALLAVE (EDO.SUCRE)'),
array("id"=>125,"name"=>'CHICHIRIVICHE'),
array("id"=>126,"name"=>'CHIGUARA'),
array("id"=>38,"name"=>'CHIVACOA'),
array("id"=>593,"name"=>'CHUPARIN ABAJO'),
array("id"=>127,"name"=>'CHURUGUARA'),
array("id"=>614,"name"=>'CHURUPAL'),
array("id"=>475,"name"=>'CHURURU'),
array("id"=>11,"name"=>'CIUDAD BOLIVAR'),
array("id"=>128,"name"=>'CIUDAD BOLIVIA'),
array("id"=>527,"name"=>'CIUDAD GUAYANA'),
array("id"=>28,"name"=>'CIUDAD OJEDA'),
array("id"=>540,"name"=>'CIUDAD PIAR'),
array("id"=>580,"name"=>'CLARINES'),
array("id"=>608,"name"=>'COCOLI'),
array("id"=>130,"name"=>'COCOROTE'),
array("id"=>476,"name"=>'COLON'),
array("id"=>131,"name"=>'COLONCITO'),
array("id"=>132,"name"=>'COLONIA DE TUREN'),
array("id"=>133,"name"=>'COLONIA TOVAR'),
array("id"=>477,"name"=>'CORDERO'),
array("id"=>12,"name"=>'CORO'),
array("id"=>134,"name"=>'CRIOGENICO JOSE'),
array("id"=>605,"name"=>'CRUZ DE PUERTO SANTO'),
array("id"=>135,"name"=>'CUA'),
array("id"=>447,"name"=>'CUBIRO'),
array("id"=>10,"name"=>'CUMANA'),
array("id"=>137,"name"=>'CUMANACOA'),
array("id"=>138,"name"=>'CUMAREBO'),
array("id"=>139,"name"=>'CUMBRE ROJA'),
array("id"=>531,"name"=>'CUPIRA'),
array("id"=>141,"name"=>'CURBATI'),
array("id"=>142,"name"=>'DABAJURO'),
array("id"=>466,"name"=>'DOS CAMINOS'),
array("id"=>143,"name"=>'DUACA'),
array("id"=>144,"name"=>'EJIDO'),
array("id"=>581,"name"=>'EL AMPARO (APURE)'),
array("id"=>501,"name"=>'EL AMPARO (TRUJILLO)'),
array("id"=>145,"name"=>'EL CALLAO'),
array("id"=>147,"name"=>'EL CARDON'),
array("id"=>148,"name"=>'EL CARMELO'),
array("id"=>479,"name"=>'EL COBRE'),
array("id"=>150,"name"=>'EL CONSEJO'),
array("id"=>535,"name"=>'EL COROZO'),
array("id"=>151,"name"=>'EL DANTO'),
array("id"=>489,"name"=>'EL DIVIDIVE'),
array("id"=>564,"name"=>'EL DORADO'),
array("id"=>641,"name"=>'EL ESPINAL'),
array("id"=>152,"name"=>'EL FURRIAL'),
array("id"=>553,"name"=>'EL GUAFAL'),
array("id"=>153,"name"=>'EL GUAMACHE'),
array("id"=>544,"name"=>'EL GUAPO (EDO MIRANDA)'),
array("id"=>496,"name"=>'EL GUAYABO (EDO. ZULIA)'),
array("id"=>587,"name"=>'EL JUNKO'),
array("id"=>506,"name"=>'EL JUNQUITO'),
array("id"=>643,"name"=>'EL LIMON'),
array("id"=>155,"name"=>'EL MACO'),
array("id"=>156,"name"=>'EL MENE'),
array("id"=>157,"name"=>'EL MENITO'),
array("id"=>158,"name"=>'EL MOJAN (LA GUAJIRA)'),
array("id"=>499,"name"=>'EL MORALITO'),
array("id"=>159,"name"=>'EL MORRO DE PUERTO'),
array("id"=>604,"name"=>'EL MORRO DE PUERTO SANTO'),
array("id"=>160,"name"=>'EL MUCO'),
array("id"=>638,"name"=>'EL NULA'),
array("id"=>161,"name"=>'EL PALITO'),
array("id"=>631,"name"=>'EL PARAMO'),
array("id"=>162,"name"=>'EL PEÑON'),
array("id"=>525,"name"=>'EL PEÑON (EDO. MERIDA)'),
array("id"=>163,"name"=>'EL PIACHE'),
array("id"=>164,"name"=>'EL PILAR'),
array("id"=>165,"name"=>'EL PIÑAL'),
array("id"=>626,"name"=>'EL PLANETARIO (LA GUAJIRA)'),
array("id"=>166,"name"=>'EL PLAYON'),
array("id"=>167,"name"=>'EL PRADO'),
array("id"=>562,"name"=>'EL RASTRO'),
array("id"=>168,"name"=>'EL RETIRO'),
array("id"=>169,"name"=>'EL RINCON'),
array("id"=>170,"name"=>'EL ROSADO'),
array("id"=>171,"name"=>'EL SAMAN'),
array("id"=>172,"name"=>'EL SOCORRO'),
array("id"=>173,"name"=>'EL SOMBRERO'),
array("id"=>570,"name"=>'EL SUPI'),
array("id"=>174,"name"=>'EL TAMBOR'),
array("id"=>175,"name"=>'EL TEJERO'),
array("id"=>30,"name"=>'EL TIGRE'),
array("id"=>176,"name"=>'EL TIGRITO'),
array("id"=>177,"name"=>'EL TOCUYO'),
array("id"=>558,"name"=>'EL VALLE (EDO. MERIDA)'),
array("id"=>411,"name"=>'EL VALLE DEL ESPIRITU SANTO'),
array("id"=>178,"name"=>'EL VENADO'),
array("id"=>32,"name"=>'EL VIGIA'),
array("id"=>179,"name"=>'EL YAQUE (AEROPUERTO)'),
array("id"=>180,"name"=>'ELORZA'),
array("id"=>497,"name"=>'ENCONTRADOS'),
array("id"=>181,"name"=>'ESCUQUE'),
array("id"=>182,"name"=>'ESTACION DE SERVICIO CARORA'),
array("id"=>526,"name"=>'ESTANQUEZ'),
array("id"=>183,"name"=>'FLOR DE PATRIA'),
array("id"=>552,"name"=>'FLORES (CASERIO)'),
array("id"=>589,"name"=>'FUERTE TIUNA'),
array("id"=>185,"name"=>'GUACA'),
array("id"=>186,"name"=>'GUACARA'),
array("id"=>188,"name"=>'GUAMA (SOLO SECTOR CENTRO)'),
array("id"=>189,"name"=>'GUANADITO'),
array("id"=>43,"name"=>'GUANARE'),
array("id"=>190,"name"=>'GUANARITO'),
array("id"=>191,"name"=>'GUANTA'),
array("id"=>8,"name"=>'GUARENAS'),
array("id"=>192,"name"=>'GUASDUALITO'),
array("id"=>194,"name"=>'GUASIPATI'),
array("id"=>195,"name"=>'GUATAPANARE'),
array("id"=>196,"name"=>'GUATIRE'),
array("id"=>197,"name"=>'GUAYABAL'),
array("id"=>612,"name"=>'GUAYABERO'),
array("id"=>198,"name"=>'GUAYABONES'),
array("id"=>199,"name"=>'GUAYACAN DE LAS FLORES'),
array("id"=>200,"name"=>'GUIGUE'),
array("id"=>201,"name"=>'GUIRIA'),
array("id"=>202,"name"=>'GUIRIA DE LA PLAYA'),
array("id"=>539,"name"=>'GURI'),
array("id"=>507,"name"=>'HIGUEROTE'),
array("id"=>621,"name"=>'HUMACARO ALTO'),
array("id"=>622,"name"=>'HUMACARO BAJO'),
array("id"=>203,"name"=>'IRAPA'),
array("id"=>204,"name"=>'ISNOTU'),
array("id"=>628,"name"=>'JAJO'),
array("id"=>205,"name"=>'JUAN GRIEGO'),
array("id"=>206,"name"=>'JUDIBANA'),
array("id"=>207,"name"=>'JUSEPIN'),
array("id"=>565,"name"=>'KM. 88 VIA SANTA ELENA DE UAIREN'),
array("id"=>208,"name"=>'KM.01 AL 50 (55 KM) VIA PERIJA'),
array("id"=>563,"name"=>'LA APARICION DE OSPINO'),
array("id"=>209,"name"=>'LA ASUNCION'),
array("id"=>210,"name"=>'LA AZULITA'),
array("id"=>211,"name"=>'LA BEATRIZ'),
array("id"=>106,"name"=>'LA CARAMUCA'),
array("id"=>212,"name"=>'LA CAÑADA DE URDANETA'),
array("id"=>213,"name"=>'LA CEJITA'),
array("id"=>214,"name"=>'LA CONCEPCION'),
array("id"=>215,"name"=>'LA CONCEPCION(TRUJILLO)'),
array("id"=>557,"name"=>'LA CULATA'),
array("id"=>529,"name"=>'LA ENCRUCIJADA'),
array("id"=>217,"name"=>'LA ENSENADA'),
array("id"=>218,"name"=>'LA ESMERALDA'),
array("id"=>219,"name"=>'LA FRIA'),
array("id"=>220,"name"=>'LA FUENTE'),
array("id"=>221,"name"=>'LA GRITA'),
array("id"=>222,"name"=>'LA GUAIRA'),
array("id"=>223,"name"=>'LA GUARDIA'),
array("id"=>225,"name"=>'LA HOYADA'),
array("id"=>576,"name"=>'LA LEONA'),
array("id"=>227,"name"=>'LA MESA DE ESNUJAQUE'),
array("id"=>448,"name"=>'LA MIEL'),
array("id"=>231,"name"=>'LA PALMITA'),
array("id"=>481,"name"=>'LA PEDRERA'),
array("id"=>234,"name"=>'LA PLAZUELA'),
array("id"=>235,"name"=>'LA PUERTA'),
array("id"=>590,"name"=>'LA RINCONADA'),
array("id"=>238,"name"=>'LA SABANA DE GUACUCO'),
array("id"=>240,"name"=>'LA SALINA'),
array("id"=>241,"name"=>'LA SIERRA'),
array("id"=>498,"name"=>'LA TENDIDA'),
array("id"=>242,"name"=>'LA TOSCANA'),
array("id"=>243,"name"=>'LA VELA DE CORO'),
array("id"=>244,"name"=>'LA VICTORIA'),
array("id"=>245,"name"=>'LAGUNETICA'),
array("id"=>468,"name"=>'LAGUNILLAS'),
array("id"=>246,"name"=>'LAGUNILLAS (ZULIA)'),
array("id"=>610,"name"=>'LAS CASITAS'),
array("id"=>620,"name"=>'LAS CHARAS'),
array("id"=>534,"name"=>'LAS DARAS'),
array("id"=>247,"name"=>'LAS FLORES'),
array("id"=>248,"name"=>'LAS GILES'),
array("id"=>588,"name"=>'LAS MAYAS'),
array("id"=>249,"name"=>'LAS MERCEDES DEL LLANO'),
array("id"=>595,"name"=>'LAS MESAS'),
array("id"=>627,"name"=>'LAS MESAS DE ESNOJAQUE'),
array("id"=>250,"name"=>'LAS MOROCHAS'),
array("id"=>528,"name"=>'LAS PIEDRAS (EDO. FALCON)'),
array("id"=>520,"name"=>'LAS PIEDRAS (EDO. MERIDA)'),
array("id"=>251,"name"=>'LAS TEJERIAS'),
array("id"=>640,"name"=>'LAS VEGAS'),
array("id"=>609,"name"=>'LAS VEGAS'),
array("id"=>253,"name"=>'LECHERIAS'),
array("id"=>254,"name"=>'LIBERTAD - DOLORES'),
array("id"=>606,"name"=>'LLANADA DE PUERTO SANTO'),
array("id"=>607,"name"=>'LLANADA DE RIO CARIBE'),
array("id"=>493,"name"=>'LOBATERA'),
array("id"=>625,"name"=>'LOMA LINDA (LA GUAJIRA)'),
array("id"=>255,"name"=>'LOS BOGRES'),
array("id"=>578,"name"=>'LOS GUAYOS'),
array("id"=>523,"name"=>'LOS LLANITOS DE TABAY'),
array("id"=>256,"name"=>'LOS MILLANES'),
array("id"=>461,"name"=>'LOS PUERTOS DE ALTAGRACIA'),
array("id"=>258,"name"=>'LOS ROBLES'),
array("id"=>259,"name"=>'LOS TAQUES'),
array("id"=>21,"name"=>'LOS TEQUES'),
array("id"=>594,"name"=>'LOS VIDRIALES'),
array("id"=>260,"name"=>'MACARAPANA'),
array("id"=>261,"name"=>'MACHIQUES'),
array("id"=>262,"name"=>'MAGDALENO'),
array("id"=>23,"name"=>'MAIQUETIA'),
array("id"=>263,"name"=>'MANTECAL'),
array("id"=>264,"name"=>'MANZANILLO'),
array("id"=>624,"name"=>'MAR BEACH (LA GUAJIRA)'),
array("id"=>6,"name"=>'MARACAIBO'),
array("id"=>3,"name"=>'MARACAY'),
array("id"=>265,"name"=>'MARIARA'),
array("id"=>266,"name"=>'MARIGUITAR'),
array("id"=>268,"name"=>'MATARUCA'),
array("id"=>22,"name"=>'MATURIN'),
array("id"=>613,"name"=>'MAURACO'),
array("id"=>269,"name"=>'MENDOZA FRIA'),
array("id"=>270,"name"=>'MENE GRANDE'),
array("id"=>18,"name"=>'MERIDA'),
array("id"=>271,"name"=>'MESA BOLIVAR'),
array("id"=>273,"name"=>'MICHELENA'),
array("id"=>274,"name"=>'MIRAFLORES'),
array("id"=>275,"name"=>'MIRANDA (CARABOBO)'),
array("id"=>536,"name"=>'MIRI'),
array("id"=>443,"name"=>'MIRIMIRE'),
array("id"=>430,"name"=>'MONAY'),
array("id"=>276,"name"=>'MONTALBAN'),
array("id"=>277,"name"=>'MORON'),
array("id"=>572,"name"=>'MORUY'),
array("id"=>629,"name"=>'MOSQUEY'),
array("id"=>279,"name"=>'MOTATAN'),
array("id"=>280,"name"=>'MUCUCHIES'),
array("id"=>639,"name"=>'MUCUJEPE'),
array("id"=>281,"name"=>'MUCURUBA'),
array("id"=>600,"name"=>'MUELLE DE CARIACO'),
array("id"=>282,"name"=>'NIRGUA'),
array("id"=>283,"name"=>'NUEVA BOLIVIA'),
array("id"=>623,"name"=>'NUEVA LUCHA (LA GUAJIRA)'),
array("id"=>284,"name"=>'OBISPO'),
array("id"=>285,"name"=>'OCUMARE DEL TUY'),
array("id"=>550,"name"=>'ORTIZ'),
array("id"=>286,"name"=>'OSPINO'),
array("id"=>287,"name"=>'PALMA SOLA'),
array("id"=>288,"name"=>'PALMIRA'),
array("id"=>289,"name"=>'PALO NEGRO'),
array("id"=>290,"name"=>'PAMPAN'),
array("id"=>291,"name"=>'PAMPANITO'),
array("id"=>292,"name"=>'PAMPATAR'),
array("id"=>598,"name"=>'PANTOÑO'),
array("id"=>293,"name"=>'PAPELON'),
array("id"=>294,"name"=>'PARACOTOS (ZONA INDUSTRIAL LA CUMACA)'),
array("id"=>465,"name"=>'PARAPARA DE ORTIZ'),
array("id"=>295,"name"=>'PARIAGUAN'),
array("id"=>633,"name"=>'PAYARA'),
array("id"=>296,"name"=>'PEDRAZA'),
array("id"=>297,"name"=>'PEDREGAL'),
array("id"=>298,"name"=>'PEDREGALES'),
array("id"=>299,"name"=>'PEQUIVEN'),
array("id"=>300,"name"=>'PETROQUIMICA'),
array("id"=>301,"name"=>'PIRITU'),
array("id"=>302,"name"=>'PIRITU(EDO ANZOATEGUI)'),
array("id"=>303,"name"=>'PLAYA EL ANGEL'),
array("id"=>304,"name"=>'PLAYA GRANDE (EDO SUCRE)'),
array("id"=>15,"name"=>'PORLAMAR'),
array("id"=>484,"name"=>'PREGONERO'),
array("id"=>305,"name"=>'PRIMERO DE MAYO'),
array("id"=>519,"name"=>'PUEBLO LLANO'),
array("id"=>568,"name"=>'PUEBLO NUEVO PARAGUANA'),
array("id"=>35,"name"=>'PUERTO AYACUCHO'),
array("id"=>31,"name"=>'PUERTO CABELLO'),
array("id"=>9,"name"=>'PUERTO LA CRUZ'),
array("id"=>307,"name"=>'PUERTO MIRANDA'),
array("id"=>308,"name"=>'PUERTO NUTRIAS - CIUDAD NUTRIAS'),
array("id"=>14,"name"=>'PUERTO ORDAZ'),
array("id"=>309,"name"=>'PUERTO PAEZ'),
array("id"=>310,"name"=>'PUERTO PIRITU'),
array("id"=>603,"name"=>'PUERTO SANTO'),
array("id"=>311,"name"=>'PUNTA CARDON'),
array("id"=>312,"name"=>'PUNTA DE LEIVA'),
array("id"=>313,"name"=>'PUNTA DE MATA'),
array("id"=>314,"name"=>'PUNTA DE PALMA'),
array("id"=>315,"name"=>'PUNTA DE PIEDRAS'),
array("id"=>316,"name"=>'PUNTA GORDA'),
array("id"=>13,"name"=>'PUNTO FIJO'),
array("id"=>615,"name"=>'PUTUCUTAL'),
array("id"=>617,"name"=>'QUEBRADA SECA'),
array("id"=>318,"name"=>'QUEREMENE'),
array("id"=>319,"name"=>'QUIBOR'),
array("id"=>321,"name"=>'QUIRIQUIRE'),
array("id"=>323,"name"=>'RIO ACARIGUA'),
array("id"=>324,"name"=>'RIO CARIBE'),
array("id"=>513,"name"=>'RIO CHICO'),
array("id"=>325,"name"=>'RUBIO'),
array("id"=>326,"name"=>'SABANA DE LIBRE'),
array("id"=>327,"name"=>'SABANA DE MENDOZA'),
array("id"=>328,"name"=>'SABANA DE PARRA'),
array("id"=>329,"name"=>'SABANETA (BARINAS)'),
array("id"=>334,"name"=>'SAN ANTONIO (PORLAMAR)'),
array("id"=>332,"name"=>'SAN ANTONIO DE CAPAYACUAR MATURIN'),
array("id"=>331,"name"=>'SAN ANTONIO DE LOS ALTOS'),
array("id"=>333,"name"=>'SAN ANTONIO DEL GOLFO'),
array("id"=>16,"name"=>'SAN ANTONIO DEL TACHIRA'),
array("id"=>1,"name"=>'SAN CARLOS'),
array("id"=>335,"name"=>'SAN CARLOS DEL ZULIA'),
array("id"=>336,"name"=>'SAN CASIMIRO'),
array("id"=>17,"name"=>'SAN CRISTOBAL'),
array("id"=>337,"name"=>'SAN DIEGO DE LOS ALTOS'),
array("id"=>37,"name"=>'SAN FELIPE'),
array("id"=>338,"name"=>'SAN FELIX'),
array("id"=>36,"name"=>'SAN FERNANDO'),
array("id"=>339,"name"=>'SAN FRANCISCO DE ASIS'),
array("id"=>340,"name"=>'SAN FRANCISCO DE MACANAO'),
array("id"=>341,"name"=>'SAN FRANCISCO DE YARE'),
array("id"=>343,"name"=>'SAN JOAQUIN'),
array("id"=>459,"name"=>'SAN JOSE (YARACUY)'),
array("id"=>344,"name"=>'SAN JOSE DE AEROCUAR'),
array("id"=>345,"name"=>'SAN JOSE DE GUANIPA'),
array("id"=>542,"name"=>'SAN JOSE DE GUARIBE'),
array("id"=>346,"name"=>'SAN JOSE DE LOS ALTOS'),
array("id"=>347,"name"=>'SAN JOSE DE PERIJA'),
array("id"=>348,"name"=>'SAN JOSE OBRERO'),
array("id"=>469,"name"=>'SAN JUAN DE LAGUNILLAS'),
array("id"=>508,"name"=>'SAN JUAN DE LOS CAYOS'),
array("id"=>39,"name"=>'SAN JUAN DE LOS MORROS'),
array("id"=>350,"name"=>'SAN JUAN DE PAYARA'),
array("id"=>353,"name"=>'SAN MATEO'),
array("id"=>354,"name"=>'SAN MIGUEL'),
array("id"=>355,"name"=>'SAN PABLO'),
array("id"=>356,"name"=>'SAN PEDRO DE LOS ALTOS'),
array("id"=>445,"name"=>'SAN RAFAEL DE ONOTO'),
array("id"=>521,"name"=>'SAN RAFAEL MUCUCHIES'),
array("id"=>358,"name"=>'SAN SEBASTIAN'),
array("id"=>359,"name"=>'SAN SEBASTIAN DE LOS REYES'),
array("id"=>361,"name"=>'SAN TOME'),
array("id"=>449,"name"=>'SANARE'),
array("id"=>549,"name"=>'SANARE (EDO. FALCON)'),
array("id"=>491,"name"=>'SANTA ANA (TACHIRA)'),
array("id"=>573,"name"=>'SANTA ANA DE PARAGUANA'),
array("id"=>365,"name"=>'SANTA ANA(TRUJILLO)'),
array("id"=>367,"name"=>'SANTA BARBARA DE BARINAS'),
array("id"=>368,"name"=>'SANTA BARBARA DE MONAGAS'),
array("id"=>366,"name"=>'SANTA BARBARA DEL ZULIA'),
array("id"=>369,"name"=>'SANTA CRUZ DE ARAGUA'),
array("id"=>370,"name"=>'SANTA CRUZ DE MARA (LA GUAJIRA)'),
array("id"=>371,"name"=>'SANTA CRUZ DE MORA'),
array("id"=>548,"name"=>'SANTA CRUZ DEL ZULIA'),
array("id"=>515,"name"=>'SANTA ELENA DE ARENALES (EDO. MERIDA)'),
array("id"=>567,"name"=>'SANTA ELENA DE UAIREN'),
array("id"=>372,"name"=>'SANTA LUCIA DEL TUY'),
array("id"=>380,"name"=>'SANTA MARIA DE IPIRE'),
array("id"=>373,"name"=>'SANTA RITA'),
array("id"=>642,"name"=>'SANTA RITA - ARAGUA'),
array("id"=>27,"name"=>'SANTA TERESA DEL TUY'),
array("id"=>470,"name"=>'SANTO DOMINGO'),
array("id"=>363,"name"=>'SARARE'),
array("id"=>376,"name"=>'SAUCEDO'),
array("id"=>492,"name"=>'SEBORUCO'),
array("id"=>377,"name"=>'SIQUISIQUE'),
array("id"=>378,"name"=>'SOCOPO'),
array("id"=>379,"name"=>'SOLEDAD'),
array("id"=>381,"name"=>'SUPER OCTANOS'),
array("id"=>522,"name"=>'TABAY'),
array("id"=>383,"name"=>'TACARIGUA'),
array("id"=>514,"name"=>'TACARIGUA DE MAMPORAL'),
array("id"=>384,"name"=>'TAMARE'),
array("id"=>385,"name"=>'TAPARITO'),
array("id"=>386,"name"=>'TARATARA'),
array("id"=>388,"name"=>'TARIBA'),
array("id"=>389,"name"=>'TEMBLADOR'),
array("id"=>602,"name"=>'TERRANOVA'),
array("id"=>390,"name"=>'TIA JUANA'),
array("id"=>391,"name"=>'TIMOTES'),
array("id"=>392,"name"=>'TINACO'),
array("id"=>393,"name"=>'TINAQUILLO'),
array("id"=>509,"name"=>'TOCUYO DE LA COSTA'),
array("id"=>429,"name"=>'TOSTOS'),
array("id"=>395,"name"=>'TOVAR'),
array("id"=>41,"name"=>'TRUJILLO'),
array("id"=>5,"name"=>'TUCACAS'),
array("id"=>397,"name"=>'TUCANIZON'),
array("id"=>398,"name"=>'TUCUPIDO'),
array("id"=>399,"name"=>'TUCUPITA'),
array("id"=>400,"name"=>'TUMEREMO'),
array("id"=>401,"name"=>'TUNAPUY'),
array("id"=>402,"name"=>'TUREN'),
array("id"=>403,"name"=>'TURIACAS'),
array("id"=>404,"name"=>'TURMERO'),
array("id"=>630,"name"=>'TUÑAME'),
array("id"=>494,"name"=>'UMUQUENA'),
array("id"=>406,"name"=>'UPATA'),
array("id"=>407,"name"=>'URACHICHE'),
array("id"=>409,"name"=>'URENA'),
array("id"=>4,"name"=>'VALENCIA'),
array("id"=>34,"name"=>'VALERA'),
array("id"=>40,"name"=>'VALLE DE LA PASCUA'),
array("id"=>410,"name"=>'VALLE DE PEDRO GONZALEZ'),
array("id"=>547,"name"=>'VALLE GUANAPE'),
array("id"=>413,"name"=>'VALLE VERDE'),
array("id"=>583,"name"=>'VALMORE RODRIGUEZ'),
array("id"=>537,"name"=>'VEGUITAS'),
array("id"=>414,"name"=>'VENEPAL'),
array("id"=>555,"name"=>'VIENTO FRESCO'),
array("id"=>415,"name"=>'VILLA DE CURA'),
array("id"=>416,"name"=>'VILLA DEL ROSARIO'),
array("id"=>417,"name"=>'VILLA ROSA'),
array("id"=>584,"name"=>'VILLA TAMARE'),
array("id"=>418,"name"=>'YAGUARAPARO'),
array("id"=>419,"name"=>'YARACAL'),
array("id"=>420,"name"=>'YARITAGUA'),
array("id"=>460,"name"=>'YUMARE'),
array("id"=>421,"name"=>'ZARAZA'),
array("id"=>422,"name"=>'ZEA'));

	foreach($destinos as $destino){
		$div = new HTMLElement(array(
			"tag"=>"div",
			"inside"=>"<a href=\"?url=".(($url===FALSE)?"":$url)."&area=".$destino["id"]."\">".$destino["name"]."</a>"
		));
		$mainDiv->addChildElement($div);
	}
	
	$body->addChildElement($mainDiv);
	
	$document->display();
	
	
?>