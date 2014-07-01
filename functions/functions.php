
<?php
class DB{


//High level functions

	public static function insertProduct($name, $description, $price)
	{
		return DB::insertValues("product",array("name"=>$name,"description"=>$description,"price"=>$price));
	}

	public static function getProductById($id)
	{
		return DB::getUniqueValue("product",array(),"id=?",array($id));
	}

	public static function insertUser($name, $identifier, $password)
	{
		return DB::insertValues("user",array("name"=>$name,"identifier"=>$identifier,"password"=>$password));
	}

	public static function getUserById($id)
	{
		return DB::getUniqueValue("user",array(),"id=?",array($id));
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

	public static function addCategoryToProduct($product_id, $category_id)
	{
		DB::insertValues("product_has_category",array("product_id"=>$product_id,"category_id"=>$category_id));
		if(DB::getUniqueValue("product_has_category",array(),"product_id=? AND category_id=?",array($product_id,$category_id))){
			return TRUE;
		}
		return FALSE;
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
		return DB::getValues("product_has_category",array(),"id=?",array($id));
	}
	
	public static function getProducts($categoryIdArray,$resultLimit,$resultOffset)
	{
		$numCategories = count($categoryIdArray);
		$whereText = "1";
		for ($i=0;$i<$numCategories;$i+=1){
			$where_i = "EXISTS (SELECT * FROM product_has_category AS table$i WHERE table$i.product_id=product.id AND table$i.category_id=?)";
			if ($i==0){
				$whereText = $where_i;
			}else{
				$whereText = $whereText." AND ".$where_i;
			}
		}
		$whereText = $whereText." LIMIT $resultOffset,$resultLimit";
		
		return DB::getValues("product",array(),$whereText,$categoryIdArray);
	}
	

	public static function insertCategories($catype_name, $categoryNames, $allows_multiple)
	{
		//( 1 ) Primero se verifica si ya existe una entrada en la tabla catype que tenga
		//         name=$catypename.
		//(1.1) Si la hay, se obtiene el id, digamos $id, y se agregan entradas a la tabla
		//         category con "name" tomados del array $categorynames, y con catype_id=$id
		// Return the la funcion = array de todos los ids de insercion obtenidos.
       //-------------
	  //echo $catype_name;
	  //print_r($categoryNames);
	  //echo $allows_multiple;
	  
	 $catype_id=NULL;
	 $categories_ids=array();
	  
	if ($stmt = self::connection()->prepare("SELECT id, name, allows_multiple FROM catype WHERE name = ?")) {
				$stmt->bind_param("s", $catype_name);
				$stmt->execute();
				$stmt->bind_result($catype_id, $name, $allows_m);
				$stmt->fetch();
				$stmt->close();
			}
	
	
			if (count($catype_id)<1){
				//(1.2) Si no la hay, entonces, se crea una entrada de la tabla catype con
		//         name=$catypeName, se obtiene su id de insercion, digamos $id, y al igual que
		//         en (1.1), se agregan entradas a la tabla category con "name" tomados del
		//         array $categoryNames, y con catype_id=$id
		
		
		
		if ($stmt = self::connection()->prepare("INSERT INTO catype (name, allows_multiple) VALUES (? ,?)")) {
			$stmt->bind_param("si", $name, $allows_multiple);
			$stmt->execute();
			$stmt->close();
		}

		$catype_id = mysqli_insert_id(self::connection());
		return $catype_id;
								
			}
			 
				
					//(1.1) Si la hay, se obtiene el id, digamos $id, y se agregan entradas a la tabla
		//         category con "name" tomados del array $categoryNames, y con catype_id=$id
		
		if (count($categoryNames)<1){
		return NULL;	
		}
	    else{
			
		
		$number_of_categories=sizeof($categoryNames);
		
		//echo $number_of_categories;
		
		for ($i=0;$i<=$number_of_categories-1;$i=$i+1){
		
			$categories_ids[$i]=self::insertCategory($categoryNames[$i], $catype_id);
			
		//print_r($categories_ids);
			
		}
				
		return $categories_ids;
		
		}
				
			}

		// Return the la funcion = array de todos los ids de insercion obtenidos.
						
		//-------------

	        
	public static function getAllCatypes(){
		//Devuelve todas las catypes existentes. Toda la tabla catype.
		
		$id_array = array();
        $array_con_todo = array();
		
		if ($stmt = self::connection()->prepare("SELECT id, name, allows_multiple FROM catype")) {
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
		
	  if ($stmt = self::connection()->prepare("SELECT id, name, catype_id FROM category WHERE catype_id = ?")) {
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
		
		if ($stmt = self::connection()->prepare("SELECT id,name,description,price FROM product")) {
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


	public static $connection = NULL;
	
	public static function connection()
	{
		if (self::$connection != NULL) {
			return self::$connection;
		}

		$con = new mysqli("localhost", "root", "", "hologramia_schema");
		if ($con->connect_errno) {
			$con = new mysqli("localhost", "root", "123", "hologramia_schema");
			if ($con->connect_errno) {
				echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
				self::$connection = NULL;
				return self::$connection;
			}
		}

		self::$connection = $con;
		return $con;
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
		
		if ($stmt = self::connection()->prepare($statementText)){
			call_user_func_array(mysqli_stmt_bind_param,array_merge(array(&$stmt,&$types),$refValues));
			$stmt->execute();
			$stmt->close();
			
			return mysqli_insert_id(self::connection());
		}else{
			return FALSE;
		}
	}
	
	public static function getValues($tableName,$valueArray,$whereStatement,$whereValues){
	
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
		
		
		if ($stmt = self::connection()->prepare($statementText)){
			
			call_user_func_array(mysqli_stmt_bind_param,array_merge(array(&$stmt,&$whereTypes),$refWhereValues));
			
			
			
			$stmt->execute();
			
			$data = mysqli_stmt_result_metadata($stmt);
			
			$fields = array();
        	$out = array();

       		while($field = mysqli_fetch_field($data)) {
       			
            	$fields[] = &$out[$field->name];
        	}
        	
        	
        	
        	call_user_func_array(mysqli_stmt_bind_result,array_merge(array(&$stmt),$fields));
			
			$result = array();
			while($stmt->fetch()){
				//print("<br/>FETCHED OUT:<br/>");
				
				//var_dump($out);
				
				$result[] = DB::dereference($out);
			}
			
			$stmt->close();
			
			return $result;
			
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
		
		
		if ($stmt = self::connection()->prepare($statementText)){
			
			call_user_func_array(mysqli_stmt_bind_param,array_merge(array(&$stmt,&$whereTypes),$refWhereValues));
			
			$stmt->execute();
			
			return TRUE;
			
		}else{
			return FALSE;
		}
	}
	
	
// General hepers

	public static function dereference($x){
		$y = array();
		foreach($x as $key=>$value){
			$y[$key] = $value;
		}
		return $y;
	}
	
	
}

?>