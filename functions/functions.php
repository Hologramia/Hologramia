
<?php

require_once("con.php");

class Holo {

	public static function getCurrentCart()
		{
			if (($cart_id=Helper::getSessionValue("cart",FALSE)) !== FALSE){
				if (($cart=DB::getCartById($cart_id)) !== FALSE){
					return $cart;
				}else{
					unset($_SESSION["cart"]);
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}
		
	public static function updateCartUserId($cart_id,$user_id)
	{
		DB::updateValues("cart",array("user_id"=>$user_id),"id=?",array($cart_id));
		Holo::updateCartTime($cart_id);
	}	
	public static function updateCartTime($cart_id)
		{
			return DB::updateValues("cart",array(),"id=?",array($cart_id),array("time"=>"NOW()"));
		}
	
	public static function reserveProduct($product_id)
		{
			if (($product = DB::getProductById($product_id)) !== FALSE){
				$stock = $product["stock"];
				$reserved = $product["reserved"];
				$bought = $product["bought"];
				if ($stock-$reserved-$bought<=0){
					return FALSE;
				}else{
					$reserved += 1;
					return DB::updateValues("product",array("reserved"=>$reserved),"id=?",array($product_id));
				}
			}else{
				return FALSE;
			}
		}
	public static function releaseProduct($product_id,$count)
		{
			if (($product = DB::getProductById($product_id)) !== FALSE){
				$reserved = $product["reserved"];
				$reserved -= $count;
				if ($reserved<0){
					$reserved = 0;
				}
				return DB::updateValues("product",array("reserved"=>$reserved),"id=?",array($product_id));
			}else{
				return FALSE;
			}
		}
	
		public static function isProductInCart($product_id)
		{
			if (($products=Holo::getCurrentCartProducts()) !== FALSE){
				foreach ($products as $value) {
					if ($value["product_id"]===$product_id){
						return TRUE;
					}
				}
				return FALSE;
			}else{
				return FALSE;
			}
			
		}
		
	public static function getCurrentCartProducts()
		{
			if (($cart=Holo::getCurrentCart()) !== FALSE){
				return Holo::getCartProducts($cart["content"]);
			}else{
				return FALSE;
			}
		}
		
	public static function getCartProducts($content)
	{
		$products = array();
		$itemArray = explode(";",$content);
		foreach ($itemArray as $value){
			$values = explode("|",$value);
			if (count($values)==2){
				$products[] = array("product_id"=>$values[0]+0,"count"=>$values[1]+0);
			}
		}
		return $products;
	}
	
	public static function getCartContent($products){
		$count = 0;
		$content = "";
		foreach ($products as $value){
			if ($count>0){
				$content .= ";";
			}
			$content .= $value["product_id"]."|".$value["count"];
			$count+=1;
		}
		return $content;
	}
	
	public static function updateCart($cart_id,$cart_products)
	{
		if (count($cart_products)==0){
			DB::removeCartWithId($cart_id);
			if (Helper::getSessionValue("cart",FALSE)===$cart_id){
				unset($_SESSION["cart"]);
			}
			return;
		}
		
		$content = Holo::getCartContent($cart_products);
		DB::updateValues("cart",array("content"=>$content,"shipping_done"=>0),"id=?",array($cart_id));
		Holo::updateCartTime($cart_id);
	}
	
	public static function logOut()
	{
		unset($_SESSION["user"]);
		unset($_SESSION["cart"]);
		unset($_SESSION["shipping-area"]);
		//unset($_SESSION["shipping-price"]);
	}
	
	public static function computeShippingPrice()
	{
		//Use shipping area!
		
		if (($cart=Holo::getCurrentCart()) !== FALSE){
			updateCartTime($cart["id"]);
			return DB::updateValues("cart",array("shipping_price"=>50,"shipping_done"=>1),"id=?",array($cart["id"]),array("time"=>"NOW()"));
		}else{
			return FALSE;
		}
		
		//$_SESSION["shipping-price"] = 50;
	}

	public static function updateShippingArea($area)
	{
		$_SESSION["shipping-area"] = $area;
	}

	public static function currentShippingArea()
	{
		return Helper::getSessionValue("shipping-area",FALSE);
	}
	
	public static function createAccount($name,$email,$pass)
	{
		//$hash = password_hash($pass, PASSWORD_DEFAULT);
		$hash = $pass;
		
		$user = DB::getUserByIdentifier($email);
		
		if ($user !== FALSE){
			return FALSE;
		}
		
		if (($old = Helper::getArrayValue($_SESSION,"user",FALSE)) !== FALSE){
			Holo::logOut();
		}
		
		if (($id = DB::insertUser($name,$email,$hash)) !== FALSE && ($user = DB::getUserById($id)) !== FALSE){
			$_SESSION["user"] = $user;
			
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
	public static function login($email,$pass)
	{
		//$hash = password_hash($pass, PASSWORD_DEFAULT);
		
		//echo("obtained hash $hash from pass $pass");
		
		$user = DB::getUserByIdentifier($email);
		
		if ($user===FALSE){
			return FALSE;
		}
		
		//if (!password_verify($pass,$user["password"])){
		if ($pass !== $user["password"]){
			//echo("Login failed because saved pass=".$user["password"]." and pass=$pass not not match!");
			return FALSE;
		}
		
		if (($old = Helper::getArrayValue($_SESSION,"user",FALSE)) !== FALSE){
			Holo::logOut();
		}
		
		$_SESSION["user"] = $user;
		
		if (($cart = Holo::getCurrentCart()) !== FALSE){
			Holo::updateCartUserId($cart["id"],$user["id"]);
		}
		
		return TRUE;
	}

	public static function userEmailExists($email)
	{
		return (DB::getUserByIdentifier($email) !== FALSE);
	}

	public static function saveURL($url)
	{
		$key = uniqid("url",TRUE);
		$urls = Helper::getArrayValue($_SESSION,"urls",array());
		$urls[$key] = $url;
		$_SESSION["urls"] = $urls;
		return $key;
	}

	public static function currentUser()
	{
		if (array_key_exists("user",$_SESSION)){
			return $_SESSION["user"];
		}else{
			return FALSE;
		}
	}
	
	public static function currentShippingPrice()
	{
		
		if (($cart=Holo::getCurrentCart()) !== FALSE){
			
			if ($cart["shipping_done"]>0){
				return $cart["shipping_price"];
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	public static function updateActionDictionary(&$actionDict,$actionArray)
	{
		if (!($key = array_search($actionArray,$actionDict))){
			$key = uniqid("holo",TRUE);
			$actionDict[$key] = $actionArray;
		}
		
		return $key;
	}
	
	public static function categoryStringByAdding($id,$categoryIdArray,&$allCats,&$oldString)
	{
		if (array_key_exists($id,$allCats["categories"])){
			$categoryNoArray = $categoryIdArray["no"];
			$categoryYesArray = $categoryIdArray["yes"];
			
			$catype_id = $allCats["categories"][$id]["catype_id"];
			
			if (array_key_exists($catype_id,$categoryNoArray) && in_array($id,$categoryNoArray[$catype_id])){
				Helper::removeArrayValue($categoryNoArray[$catype_id],$id);
			}else if (array_key_exists($catype_id,$categoryYesArray) && !in_array($id,$categoryYesArray[$catype_id])){
				$categoryYesArray[$catype_id][] = $id;
			}
			
			return Holo::categoryStringFromIdArray(array("yes"=>$categoryYesArray,"no"=>$categoryNoArray));
			
		}elseif (array_key_exists(-$id,$allCats["catypes"])){
			$catype_id = -$id;
			$categoryNoArray = $categoryIdArray["no"];
			$categoryYesArray = $categoryIdArray["yes"];
			
			if (array_key_exists($catype_id,$categoryYesArray)){
				$yesArray = $categoryYesArray[$catype_id];
				unset($categoryYesArray[$catype_id]);
				$noArray = array();
				$allCategories = $allCats["structure"][$catype_id];
				foreach ($allCategories as $category_id){
					if (!in_array($category_id,$yesArray)){
						$noArray[] = $category_id;
					}
				}
				$categoryNoArray[$catype_id] = $noArray;
				
				return Holo::categoryStringFromIdArray(array("yes"=>$categoryYesArray,"no"=>$categoryNoArray));
			}
		}
		
		return $oldString;
		
	}
	
	public static function categoryStringByRemoving($id,&$categoryIdArray,&$allCats,&$oldString)
	{
		if (array_key_exists($id,$allCats["categories"])){
			$categoryNoArray = $categoryIdArray["no"];
			$categoryYesArray = $categoryIdArray["yes"];
			
			$catype_id = $allCats["categories"][$id]["catype_id"];
			
			if (array_key_exists($catype_id,$categoryNoArray) && !in_array($id,$categoryNoArray[$catype_id])){
				$categoryNoArray[$catype_id][] = $id;
			}else if (array_key_exists($catype_id,$categoryYesArray) && in_array($id,$categoryYesArray[$catype_id])){
				Helper::removeArrayValue($categoryYesArray[$catype_id],$id);
				if (count($categoryYesArray[$catype_id])==0){
					return $oldString;
				}
			}
			
			return Holo::categoryStringFromIdArray(array("yes"=>$categoryYesArray,"no"=>$categoryNoArray));
			
		}elseif (array_key_exists(-$id,$allCats["catypes"])){
			$catype_id = -$id;
			$categoryNoArray = $categoryIdArray["no"];
			$categoryYesArray = $categoryIdArray["yes"];
			
			if (array_key_exists($catype_id,$categoryNoArray)){
				$noArray = $categoryNoArray[$catype_id];
				unset($categoryNoArray[$catype_id]);
				$yesArray = array();
				$allCategories = $allCats["structure"][$catype_id];
				foreach ($allCategories as $category_id){
					if (!in_array($category_id,$noArray)){
						$yesArray[] = $category_id;
					}
				}
				if (count($yesArray)>0){
					$categoryYesArray[$catype_id] = $yesArray;
					return Holo::categoryStringFromIdArray(array("yes"=>$categoryYesArray,"no"=>$categoryNoArray));
				}
			}
		}
		
		return $oldString;
	}
	
	public static function categoryStringFromIdArray($categoryIdArray){
		$categoryNoArray = $categoryIdArray["no"];
		$categoryYesArray = $categoryIdArray["yes"];
		$result = array();
		foreach ($categoryNoArray as $catype_id=>$categories){
			$result[] = (-$catype_id).":".implode(",",$categories);
		}
		foreach ($categoryYesArray as $catype_id=>$categories){
			$result[] = $catype_id.":".implode(",",$categories);
		}
		return implode(";",$result);
	}
}

class Helper {

	public static function unsetArrayValue(&$array, $value, $strict = TRUE)
	{
    	if(($key = array_search($value, $array, $strict)) !== FALSE) {
        	unset($array[$key]);
    	}
	}

	public static function removeArrayValue(&$array, $value, $strict = TRUE)
	{
    	if(($key = array_search($value, $array, $strict)) !== FALSE) {
        	array_splice($array,$key,1);
    	}
	}

	public static function arrayUnion($array,$array1)
	{
		$booleanArray = Helper::getBooleanArray($array);
		foreach($array1 as $value){
			if (!Helper::arrayKeyIsTRUE($value,$booleanArray)){
				$array[] = $value;
			}
		}
		return $array;
	}
	public static function urlData($array)
	{
		$count = 0;
		$text = "";
		foreach($array as $key=>$value){
			if ($count>0){
				$text .= "&";
			}
			$text .= $key."=".urlencode($value);
			$count += 1;
		}
		return $text;
	}
	public static function updatedArray($array,$array1)
	{
		foreach($array1 as $key=>$value){
			$array[$key] = $value;
		}
		return $array;
	}

	public static function getArrayValue(&$array,$key,$default)
	{
		if (array_key_exists($key,$array)){
			return $array[$key];
		}
		
		return $default;
	}
	
	public static function getSessionValue($key,$default)
	{
		if (array_key_exists($key,$_SESSION)){
			return $_SESSION[$key];
		}
		
		return $default;
	}
	
	public static function dereference($x){
		$y = array();
		foreach($x as $key=>$value){
			$y[$key] = $value;
		}
		return $y;
	}
	
	public static function loadArrayIfNULL(&$local_data,$get){
		if($local_data==NULL){
			$local_data = array();
			foreach($get as $key=>$value){
				$local_data[$key] = $value;
			}
		}
	}
	
	public static function getBooleanArray($array){
		$result = array();
		foreach ($array as $value){
			$result[$value] = TRUE;
		}
		return $result;
	}
	
	public static function arrayKeyIsTRUE($key,$array){
		return (array_key_exists($key,$array) && $array[$key]);
	}
	
	public static function arrayMinusKeys($array,$keys){
		foreach ($keys as $key){
			unset($array[$key]);
		}
		return $array;
	}
	
	public static function array_path_exists($path,$array){
		if (count($path)==0){
			return TRUE;
		}
		$key = $path[0];
		if (count($path)==1 && $key===$array){
			return TRUE;
		}
		if (gettype($array) != "array"){
			return FALSE;
		}
		if (count($array)==0){
			return FALSE;
		}
		if ($key===NULL){
			array_splice($path,0,1);
			foreach($array as $value){
				if (Helper::array_path_exists($path,$value)){
					return TRUE;
				}
			}
			return FALSE;
		}elseif(gettype($key) != "string" && gettype($key) != "integer"){
			return FALSE;
		}else{
			array_splice($path,0,1);
			return (array_key_exists($key,$array) && Helper::array_path_exists($path,$array[$key]));
		}
	}
}

class HTMLElement {
	public $defaultHTML = "";
	public $tag = "";
	public $ends = TRUE;
	public $slash = FALSE;
	public $params = array();
	public $fileName = "";
	public $childElements = array();
	public $localData = array();
	public $inside = "";
	public $insideFunction = FALSE;
	public function postConstruct()
	{
		if ($this->tag=="!DOCTYPE html"){
			$this->ends = FALSE;
		}
	}
	public function __construct()
	{
		$a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        }
        $this->postConstruct();
	}
	public function __construct0()
	{
		//nothing
	}
	public function __construct1($value)
	{
		switch(gettype($value)){
			case "array":
				$this->defaultHTML 		= Helper::getArrayValue($value,"defaultHTML"	,$this->defaultHTML		);
				$this->tag 				= Helper::getArrayValue($value,"tag"			,$this->tag				);
				$this->ends 			= Helper::getArrayValue($value,"ends"			,$this->ends			);
				$this->slash 			= Helper::getArrayValue($value,"slash"			,$this->slash			);
				$this->params 			= Helper::getArrayValue($value,"params"			,$this->params			);
				$this->fileName 		= Helper::getArrayValue($value,"fileName"		,$this->fileName		);
				$this->childElements	= Helper::getArrayValue($value,"childElements"	,$this->childElements	);
				$this->localData 		= Helper::getArrayValue($value,"localData"		,$this->localData		);
				$this->inside	 		= Helper::getArrayValue($value,"inside"			,$this->inside			);
				$this->insideFunction	= Helper::getArrayValue($value,"insideFunction"	,$this->insideFunction	);
				break;
			default:
				$this->tag = $value;
		}
	}
	public function beginning()
	{
		if (strlen($this->fileName)>0){
			return "";
		}
		if (strlen($this->defaultHTML)>0){
			return $this->defaultHTML;
		}
		$beg = "";
		$beg_slash = "";
		if ($this->slash){
			$beg_slash = "/";
		}
		if (strlen($this->tag)>0){
			$beg = "<".$beg_slash.($this->tag);
			foreach($this->params as $key=>$value){
				$beg .= " ".$key;
				if (gettype($value) != "boolean"){
					$beg .= "=\"".$value."\"";
				}
				
			}
			$beg .= ">";
		}
		return $beg;
	}
	public function ending()
	{
		if (strlen($this->tag)<1 || !($this->ends) || $this->slash || strlen($this->defaultHTML)>0 || strlen($this->fileName)>0){
			return "";
		}
		return "</".($this->tag).">";
	}
	/*public function fullHTML()
	{
		$text = $this->beginning();
		$text .= $this->inside;
		foreach($this->childElements as $value){
			$text .= $value->fullHTML();
		}
		$text .= $this->ending();
		return $text;
	}*/
	public function display()
	{
		print($this->beginning());
		print($this->inside);
		if ($this->insideFunction){
			$insideF = $this->insideFunction;
			$insideF();
		}
		if (strlen($this->fileName)>0){
			$local_data = $this->localData;
			include $fileName;
			return;
		}
		foreach($this->childElements as $value){
			$value->display();
		}
		print($this->ending());
	}
	public function addChildElement($child)
	{
		if (gettype($child)=="array"){
			foreach($child as $value){
				$this->addChildElement($value);
			}
			return;
		}
		$this->childElements[] = $child;
	}
	public static function hiddenInputs($array)
	{
		$inputArray = array();
		foreach($array as $key=>$value){
			$inputArray[] = new HTMLElement(array(
				"tag"=>"input",
				"params"=>array("type"=>"hidden",
								"name"=>$key,
								"value"=>htmlentities($value))
			));
		}
		return $inputArray;
	}
}


class DB{


//High level functions

	public static function catypeAndCategoryIdsFromString($string,&$allCats)
	{
		$array = explode(";",$string);
		$resultYes = array();
		$resultNo = array();
		foreach ($array as $catypeString){
			//echo("<br><br>Analysing: $catypeString<br><br>");
			$catypeObject = explode(":",$catypeString);
			//var_dump($catypeObject);
			$catypeObjectCount = count($catypeObject);
			if ($catypeObjectCount==2){
				$catypeId = $catypeObject[0]+0;
				$yes = true;
				if ($catypeId<0){
					$yes = false;
					$catypeId = -$catypeId;
				}
				//echo("<br><br>Catype id: $catypeId, yes:".($yes?"true":"false")."<br><br>");
				if ($catype = Helper::getArrayValue($allCats["catypes"],$catypeId,FALSE)){
					$categories = array();
					$categoryIds = explode(",",$catypeObject[1]);
					foreach($categoryIds as $value){
						$id = $value+0;
						if ($cat = Helper::getArrayValue($allCats["categories"],$id,FALSE)){
							$categories[] = $id;
						}
					}
				}
				if ($yes){
					$resultYes[$catypeId] = $categories;
				}else{
					$resultNo[$catypeId] = $categories;
				}
				
			}
			
		}
		$allCatypes = $allCats["catypes"];
		foreach($allCatypes as $catype_id=>$value){
			if (!array_key_exists($catype_id,$resultYes) && !array_key_exists($catype_id,$resultNo)){
				$resultNo[$catype_id] = array();
			}
		}
		return array("yes"=>$resultYes,"no"=>$resultNo);
	}

	public static function categoryIdsFromString($string)
	{
		$array = explode(",",$string);
		$result = array();
		foreach($array as $value){
			$id = $value+0;
			if ($cat = DB::getCategoryById($id)){
				$result[] = $id;
			}
		}
		return $result;
	}

	public static function insertCart($user_id)
	{
		
		$insert_id = DB::insertValues("cart",array("user_id"=>$user_id));
		Holo::updateCartTime($insert_id);
		return $insert_id;		
	}
	
	public static function insertProduct($name, $description, $price, $thumb)
	{
		$insert_id = DB::insertValues("product",array("name"=>$name,"description"=>$description,"price"=>$price,"thumb"=>$thumb));
		DB::updateTextForProduct($insert_id);
		return $insert_id;
	}

	public static function getProductById($id)
	{
		return DB::getUniqueValue("product",array(),"id=?",array($id));
	}
	
	public static function updateTextForProduct($product_id)
	{
		$product = DB::getProductById($product_id);
		$text = $product["name"]." ".$product["description"];
		$categoryArray = DB::getCategoryIdsForProduct($product_id);
		//print("categoryArray: ");
		//var_dump($categoryArray);
		$numCategories = count($categoryArray);
		for ($i=0;$i<$numCategories;$i+=1){
			$category = DB::getCategoryById($categoryArray[$i]["category_id"]);
			$text = $text." ".$category["name"];
		}
		DB::updateValues("product",array("text"=>$text),"id=?",array($product_id));
		
	}

	public static function insertUser($name, $identifier, $password)
	{
		return DB::insertValues("user",array("name"=>$name,"identifier"=>$identifier,"password"=>$password));
	}

	public static function getUserById($id)
	{
		return DB::getUniqueValue("user",array(),"id=?",array($id));
	}
	
	public static function getCartById($id)
	{
		return DB::getUniqueValue("cart",array(),"id=?",array($id));
	}

	public static function getUserByIdentifier($identifier)
	{
		return DB::getUniqueValue("user",array(),"identifier=?",array($identifier));
	}

	public static function insertCategoryType($name, $allows_multiple)
	{
		return DB::insertValues("catype",array("name"=>$name,"allows_multiple"=>$allows_multiple));
	}

	public static function getCategoryTypeById($id)
	{
		return DB::getUniqueValue("catype",array(),"id=?",array($id));	
	}

	public static function insertCategory($name, $catype_id)
	{
		return DB::insertValues("category",array("name"=>$name,"catype_id"=>$catype_id));
	}

	public static function getCategoryById($id)
	{
		return DB::getUniqueValue("category",array(),"id=?",array($id));
	}
	
	public static function getCategoryByName($name)
	{
		return DB::getUniqueValue("category",array(),"name=?",array($name));
	}

	public static function addCategoryToProduct($product_id, $category_id)
	{
		DB::insertValues("product_has_category",array("product_id"=>$product_id,"category_id"=>$category_id));
		DB::updateTextForProduct($product_id);
		if(DB::getUniqueValue("product_has_category",array(),"product_id=? AND category_id=?",array($product_id,$category_id))){
			return TRUE;
		}
		return FALSE;
	}
	
	public static function addCategoryNameToProduct($product_id,$category_name)
	{
		if ($category = DB::getCategoryByName($category_name)){
			return DB::addCategoryToProduct($product_id,$category["id"]);
		}else{
			return;
		}
	}

	public static function removeProductWithId($id)
	{
		return (
					DB::removeValues("product_has_category","product_id=?",array($id))
					&& DB::removeValues("product","id=?",array($id))
				);
	}

	public static function removeUserWithId($id)
	{
		return DB::removeValues("user","id=?",array($id));
	}
	
	public static function removeCartWithId($id)
	{
		return DB::removeValues("cart","id=?",array($id));
	}

	public static function removeCategoryWithId($id)
	{

		return (
					DB::removeValues("product_has_category","category_id=?",array($id))
					&& DB::removeValues("category","id=?",array($id))
				);
	}

	public static function removeCatypeWithId($id)
	{

		return (
					DB::removeValues("category","catype_id=?",array($id))
					&& DB::removeValues("catype","id=?",array($id))
				);
	}

	public static function getCategoryIdsForProduct($product_id)
	{
		return DB::getValues("product_has_category",array(),"product_id=?",array($product_id));
	}
	
	public static function getProducts($q,&$categoryIdArray,$resultLimit,$resultOffset)
	{
		//print("valueArray:<br/>");
		//var_dump($categoryIdArray);
	
		$categoryNoArray = $categoryIdArray["no"];
		$categoryYesArray = $categoryIdArray["yes"];
		
		$numNoCatypes = count($categoryNoArray);
		$numYesCatypes = count($categoryYesArray);

		$filterArray = array();
		$valueArray = array();
		$i = 1;
		
		foreach ($categoryNoArray as $catype_id=>$categories){
			$filterSubarray = array();
			foreach ($categories as $category_id){
				$filterSubarray[] = "NOT EXISTS (SELECT * FROM product_has_category as table$i WHERE table$i.product_id=product.id AND table$i.category_id=?)";
				$valueArray[] = $category_id;
				$i++;
			}
			if (count($filterSubarray)>0){
				$filterArray[] = implode(" AND ",$filterSubarray);
			}
		}
		
		foreach ($categoryYesArray as $catype_id=>$categories){
			$filterSubarray = array();
			foreach ($categories as $category_id){
				$filterSubarray[] = "EXISTS (SELECT * FROM product_has_category as table$i WHERE table$i.product_id=product.id AND table$i.category_id=?)";
				$valueArray[] = $category_id;
				$i++;
			}
			if (count($filterSubarray)>0){
				$filterArray[] = implode(" OR ",$filterSubarray);
			}
		}
		
		$whereText = 1;
		
		if (count($filterArray)==1){
			$whereText = $filterArray[0];
		}else if (count($filterArray)>1){
			$whereText = "(".implode(") AND (",$filterArray).")";
		}
		
		if ($q != ""){
			$whereText = "MATCH (product.text) AGAINST (? IN BOOLEAN MODE) AND ".$whereText;
			$valueArray = array_merge(array($q),$valueArray);
		}
		
		$whereText = $whereText." LIMIT $resultOffset,$resultLimit";
	
// 		$numCategories = count($categoryIdArray);
// 		$whereText = "1";
// 		for ($i=0;$i<$numCategories;$i+=1){
// 			$where_i = "EXISTS (SELECT * FROM product_has_category AS table$i WHERE table$i.product_id=product.id AND table$i.category_id=?)";
// 			if ($i==0){
// 				$whereText = $where_i;
// 			}else{
// 				$whereText = $whereText." AND ".$where_i;
// 			}
// 		}
// 		
// 		$valueArray = $categoryIdArray;
// 		
// 		if ($q != ""){
// 			$whereText = "MATCH (product.text) AGAINST (? IN BOOLEAN MODE) AND ".$whereText;
// 			$valueArray = array_merge(array($q),$valueArray);
// 		}
// 		
// 		$whereText = $whereText." LIMIT $resultOffset,$resultLimit";
		
		
		
		return DB::getValues("product",array(),$whereText,$valueArray);
	}
	

	public static function insertCategories($catype_name, $categoryNames, $allows_multiple)
	{
		//print("inserting...");
		$insertionIds = array();
		//print("inserting...");
		if (!($existingCatype = DB::getUniqueValue("catype",array("id"),"name=?",array($catype_name)))){
			$existingCatype = array("id"=>(DB::insertCategoryType($catype_name,$allows_multiple)));
		}
		//print("inserting...");
		$catype_id = $existingCatype["id"];
		//print("inserting...");
		foreach($categoryNames as $value){
			if ($inserted = DB::insertCategory($value,$catype_id)){
				$insertionIds[] = $inserted;
			}
		}
		//print("inserting...");
		return $insertionIds;
	}

		// Return the la funcion = array de todos los ids de insercion obtenidos.
						
		//-------------

	        
	public static function getAllCatypes(){
		//Devuelve todas las catypes existentes. Toda la tabla catype.
		
		$id_array = array();
        $array_con_todo = array();
		
		if ($stmt = CON::connection()->prepare("SELECT id, name, allows_multiple FROM catype")) {
			//$stmt->bind_param(,);
			$stmt->execute();
			$stmt->bind_result($id, $name, $allows_multiple);
			$i = 0;
			while ($stmt->fetch()) {
				$id_array[$i] = $id;
				
				$array_con_todo[$i] = array("id"=>$id, "name"=>$name, "allows_multiple"=>$allows_multiple);
				
				$i = $i + 1;
			}

			$stmt->close();
		}
		
		
		if (count($id_array)<1){
		$result=NULL;
		}
		else{
		$result = $array_con_todo;
		}
		
		//print_r($array_con_todo);
		
		return $result;		
		
	}
	
	
	
	public static function getAllCategoriesWithCatypeId($catype_id){
		//Devuelve todas las categories que tengan ese catype_id
		
		$id_array = array();
        $array_con_todo = array();
		
	  if ($stmt = CON::connection()->prepare("SELECT id, name, catype_id FROM category WHERE catype_id = ?")) {
			$stmt->bind_param("i",$catype_id);
			$stmt->execute();
			$stmt->bind_result($category_id, $name, $catype_id);
			$i = 0;
			while ($stmt->fetch()) {
				$id_array[$i] = $category_id;
				
				$array_con_todo[$i] = array("id"=>$category_id, "name"=>$name, "catype_id"=>$catype_id);
				
				$i = $i + 1;
				
			}

			$stmt->close();
		}
		
		if (count($id_array)<1){
		$result=NULL;
		}
		else{
		$result = $array_con_todo;
		}
		
		return $result;		
	}
	
	
	public static function getAllProducts(){
		//Tabla product
		
		$id_array = array();
        $array_con_todo = array();
		
		if ($stmt = CON::connection()->prepare("SELECT id,name,description,price FROM product")) {
			//$stmt->bind_param(,);
			$stmt->execute();
			$stmt->bind_result($id,$name,$description,$price);
			$i = 0;
			while ($stmt->fetch()) {
				$id_array[$i] = $id;
				
				$array_con_todo[$i] = array("id"=>$id, "name"=>$name, "description"=>$description, "price"=>$price);
				
				$i = $i + 1;
			}

			$stmt->close();
		}
		
		
		if (count($id_array)<1){
		$result=NULL;
	
		}
		else{
		$result = $array_con_todo;
		}
		
		//print_r($array_con_todo);
		
		return $result;		
		
	}


// Helper functions

	public static function getUniqueValue($tableName,$valueArray,$whereStatement,$whereValues){
		$result = DB::getValues($tableName,$valueArray,$whereStatement,$whereValues);
		
		if ($result && count($result)==1){
			return $result[0];
		}else{
			return FALSE;
		}
	}


// Low level functions


	
	
public static function updateValues($tableName,$valueArray,$whereStatement,$whereValues,$extraUpdates=FALSE){
	
		$types = "";
		$keys = array_keys($valueArray);
		$values = array_values($valueArray);
		$refValues = array();
		$numKeys = count($keys);
		
		for ($i = 0; $i<$numKeys; $i+=1){
		
			$refValues[] = &$values[$i];
		
			if ($i==0){
				$questionMarks = "SET ".$keys[$i]."=?";
			}else{
				$questionMarks = $questionMarks.", ".$keys[$i]."=?";
			}
			
			switch(gettype($values[$i])){
				case "integer":
				case "boolean":
					$types = $types."i";
					break;
				case "double":
					$types = $types."d";
					break;
				default:
					$types = $types."s";
			}
			
		}
		
		if ($extraUpdates !== FALSE){
			$numExtra = count($extraUpdates);
			$i = 0;
			foreach ($extraUpdates as $key=>$value){
				if ($i+$numKeys==0){
					$questionMarks = "SET ".$key."=".$value;
				}else{
					$questionMarks .= ", ".$key."=".$value;
				}
				$i+=1;
			}
		}
		
		$refWhereValues = array();
		
		$numWhereValues = count($whereValues);
		
		$whereTypes = "";
		
		for ($i = 0; $i<$numWhereValues; $i+=1){
		
			$refWhereValues[] = &$whereValues[$i];
			
			switch(gettype($whereValues[$i])){
				case "integer":
				case "boolean":
					$whereTypes = $whereTypes."i";
					break;
				case "double":
					$whereTypes = $whereTypes."d";
					break;
				default:
					$whereTypes = $whereTypes."s";
			}
			
		}
		
		$allTypes = $types.$whereTypes;
		
		$statementText = "UPDATE $tableName $questionMarks WHERE $whereStatement";
		
		if ($stmt = CON::connection()->prepare($statementText)){
			call_user_func_array("mysqli_stmt_bind_param",array_merge(array(&$stmt,&$allTypes),$refValues,$refWhereValues));
			$stmt->execute();
			$stmt->close();
			
			return TRUE;
		}else{
			return FALSE;
		}
	}
	

	
	
	public static function removeValues($tableName,$whereStatement,$whereValues){
	
		$refWhereValues = array();
		
		$numWhereValues = count($whereValues);
		
		$whereTypes = "";
		
		for ($i = 0; $i<$numWhereValues; $i+=1){
		
			$refWhereValues[] = &$whereValues[$i];
			
			switch(gettype($whereValues[$i])){
				case "integer":
				case "boolean":
					$whereTypes = $whereTypes."i";
					break;
				case "double":
					$whereTypes = $whereTypes."d";
					break;
				default:
					$whereTypes = $whereTypes."s";
			}
			
		}
		
		$statementText = "DELETE FROM $tableName WHERE $whereStatement";
		
		
		if ($stmt = CON::connection()->prepare($statementText)){
			
			call_user_func_array("mysqli_stmt_bind_param",array_merge(array(&$stmt,&$whereTypes),$refWhereValues));
			
			$stmt->execute();
			
			return TRUE;
			
		}else{
			return FALSE;
		}
	}
	
	public static function getAllCategories(){
		$resultStructure = array();
		$resultCatypes = array();
		$resultCategories = array();
		$catypes = DB::getValues("catype,category",array("catype.id","category.id"),"catype.id=category.catype_id",array());
		//var_dump($catypes);
		//return;
		foreach ($catypes as $value){
			//var_dump($value);
			$catype_id = $value["catype.id"];
			$category_id = $value["category.id"];
			if (!array_key_exists($catype_id,$resultStructure)){
				$resultStructure[$catype_id] = array();
				$resultCatypes[$catype_id] =  DB::getCategoryTypeById($catype_id);
			}
			$resultStructure[$catype_id][] = $category_id;
			$resultCategories[$category_id] = DB::getCategoryById($category_id);
		}
		return array("structure"=>$resultStructure,"catypes"=>$resultCatypes,"categories"=>$resultCategories);
	}
	
	public static function getValues($tableName,$valueArray,$whereStatement,$whereValues){
	
		$multipleTables = (strpos($tableName,",")!==false);
	
		$values = "*";
		
		if ($valueArray != NULL && $valueArray != "*" && count($valueArray)>0){
			$values = implode(",",$valueArray);
		}
		
		$refWhereValues = array();
		
		$numWhereValues = count($whereValues);
		
		$whereTypes = "";
		
		for ($i = 0; $i<$numWhereValues; $i+=1){
		
			$refWhereValues[] = &$whereValues[$i];
			
			switch(gettype($whereValues[$i])){
				case "integer":
				case "boolean":
					$whereTypes = $whereTypes."i";
					break;
				case "double":
					$whereTypes = $whereTypes."d";
					break;
				default:
					$whereTypes = $whereTypes."s";
			}
			
		}
		
		$statementText = "SELECT $values FROM $tableName WHERE $whereStatement";
		
		/*print("<br/>STATEMENT:<br/>");
			
		var_dump($statementText);
			
		print("<br/>TYPES:<br/>");
			
		var_dump($whereTypes);
			
		print("<br/>VALUES:<br/>");
			
		var_dump($refWhereValues);
			
		print("<br/>");*/
		
		
		if ($stmt = CON::connection()->prepare($statementText)){
			
			/*print("statement:</br>");
			var_dump($statementText);
			print("wheretypes:</br>");
			var_dump($whereTypes);
			print("refwherevalues:</br>");
			var_dump($refWhereValues);*/
			
			if (count($refWhereValues)>0){
				call_user_func_array("mysqli_stmt_bind_param",array_merge(array(&$stmt,&$whereTypes),$refWhereValues));
			}
			
			
			
			$stmt->execute();
			
			$data = mysqli_stmt_result_metadata($stmt);
			
			$fields = array();
        	$out = array();

       		while($field = mysqli_fetch_field($data)) {
            	$fields[] = &$out[$multipleTables?($field->table.".".$field->name):($field->name)];
        	}
        	
        	
        	
        	call_user_func_array("mysqli_stmt_bind_result",array_merge(array(&$stmt),$fields));
			
			$result = array();
			while($stmt->fetch()){
				//print("<br/>FETCHED OUT:<br/>");
				
				//var_dump($out);
				
				$result[] = Helper::dereference($out);
			}
			
			$stmt->close();
			
			return $result;
			
		}else{
			die($statementText."<br/>".CON::connection()->error);
			return FALSE;
		}
	}
	
		public static function insertValues($tableName,$valueArray){
	
		$types = "";
		$keys = array_keys($valueArray);
		$values = array_values($valueArray);
		$refValues = array();
		$numKeys = count($keys);
		
		for ($i = 0; $i<$numKeys; $i+=1){
		
			$refValues[] = &$values[$i];
		
			if ($i==0){
				$questionMarks = "?";
			}else{
				$questionMarks = $questionMarks.",?";
			}
			
			switch(gettype($values[$i])){
				case "integer":
				case "boolean":
					$types = $types."i";
					break;
				case "double":
					$types = $types."d";
					break;
				default:
					$types = $types."s";
			}
			
		}
		
		$statementText = "INSERT INTO $tableName (".implode(",",$keys).") VALUES ($questionMarks)";
		
		if ($stmt = CON::connection()->prepare($statementText)){
			call_user_func_array("mysqli_stmt_bind_param",array_merge(array(&$stmt,&$types),$refValues));
			$stmt->execute();
			$stmt->close();
			
			return mysqli_insert_id(CON::connection());
		}else{
			return FALSE;
		}
	}
	

}

?>