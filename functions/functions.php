
<?php
class DB{
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

	// JUSTO:
	// For every query that you do, please use the following syntax:
	// mysql_query( $yourQuery , DB::connection());
	// Don't worry, this doesn't mean the connection is performed again and again
	// every time.
	// If you read the function above, you will see it is not performed every time.

	public static function insertProduct($name, $description, $price)
	{

		// JUSTO:
		// Add product to database table
		// Return the insertion id using this: http://www.php.net//manual/en/function.mysql-insert-id.php

		if ($stmt = self::connection()->prepare("INSERT INTO product (name, description, price) VALUES (?, ?, ?)")) {
			$stmt->bind_param("ssd", $name, $description, $price);
			$stmt->execute();
			$stmt->close();
		}

		$product_id = mysqli_insert_id(self::connection());
		return $product_id;
	}

	public static function getProductById($id)
	{

		// JUSTO:
		// This function must read from the products table and return the product having
		// this id. If the product is not found, then it returns NULL
		// $result=mysql_query("SELECT * FROM product WHERE id=".$id.";", DB::connection());
		// return mysql_fetch_row($result);

		if ($stmt = self::connection()->prepare("SELECT id,name,description,price FROM product WHERE id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $description, $price);
			$stmt->fetch();
			$stmt->close();
			if ($id == NULL) {
				return NULL;
			}
			else {
				return array(
					"id" => $id,
					"name" => $name,
					"description" => $description,
					"price" =>$price
				);
			}
		}
		else {
			return NULL;
		}
	}

	public static function insertUser($name, $identifier, $password)
	{

		// JUSTO: Insert user, return the insert id as in insertProduct();
		// $result=mysql_query( "INSERT INTO user (name, identifier, password) VALUES ("."'".$name."'"." ,"."'".$identifier."'"." ,"."'".$password."'".")" , DB::connection());

		if ($stmt = self::connection()->prepare("INSERT INTO user (name, identifier, password) VALUES (? ,?, ?)")) {
			$stmt->bind_param("sss", $name, $identifier, $password);
			$stmt->execute();
			$stmt->close();
		}

		$user_id = mysqli_insert_id(self::connection());
		return $user_id;
	}

	public static function getUserById($id)
	{
		if ($stmt = self::connection()->prepare("SELECT id, name, identifier, password FROM user WHERE id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $identifier, $password);
			$stmt->fetch();
			$stmt->close();
			if ($id == NULL) {
				return NULL;
			}
			else {
				return array(
					"id" => $id,
					"name" => $name,
					"identifier" => $identifier,
					"password" => $password
				);
			}
		}
		else {
			return NULL;
		}
	}

	public static function insertCategoryType($name, $allows_multiple)
	{

		// JUSTO: A "category type" is something like "Color, Size, Gender"
		// This is NOT the same as a "category", which would be something like "Red, 15, Male"
		// Do not edit this function unless this is clear.
		// This function inserts into the table catype.
		// $allows_multiple is a boolean (true or false).
		// In MySQL this is a TINYINT(1) (1 or 0). This means whether a product is allowed
		// to have multiple categories in this category type. For example: A product cannot have
		// multiple "Size" (talla), but it can have multiple "Color".

		if ($stmt = self::connection()->prepare("INSERT INTO catype (name, allows_multiple) VALUES (? ,?)")) {
			$stmt->bind_param("si", $name, $allows_multiple);
			$stmt->execute();
			$stmt->close();
		}

		$catype_id = mysqli_insert_id(self::connection());
		return $catype_id;
	}

	public static function getCategoryTypeById($id)
	{
		if ($stmt = self::connection()->prepare("SELECT id, name, allows_multiple FROM catype WHERE id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $allows_multiple);
			$stmt->fetch();
			$stmt->close();
			if ($id == NULL) {
				return NULL;
			}
			else {
				return array(
					"id" => $id,
					"name" => $name,
					"allows_multiple" => $allows_multiple
				);
			}
		}
		else {
			return NULL;
		}
	}

	public static function insertCategory($name, $catype_id)
	{
		if ($stmt = self::connection()->prepare("INSERT INTO category (name, catype_id) VALUES (? ,?)")) {
			$stmt->bind_param("si", $name, $catype_id);
			$stmt->execute();
			$stmt->close();
		}

		$category_id = mysqli_insert_id(self::connection());
		return $category_id;
	}

	public static function getCategoryById($id)
	{
		if ($stmt = self::connection()->prepare("SELECT id, name, catype_id FROM category WHERE id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($id, $name, $catype_id);
			$stmt->fetch();
			$stmt->close();
			if ($id == NULL) {
				return NULL;
			}
			else {
				return array(
					"id" => $id,
					"name" => $name,
					"catype_id" => $catype_id
				);
			}
		}
		else {
			return NULL;
		}
	}

	public static function addCategoryToProduct($product_id, $category_id)
	{

		// In case this is not clear yet: category_id is the id of a
		// category, not of a catype. Make sure you understand the difference
		// between these two.
		// HOW TO MAKE THIS FUNCTION: Insert these values into the table product_has_category

		if ($stmt = self::connection()->prepare("INSERT INTO product_has_category (product_id, category_id) VALUES (? ,?)")) {
			$stmt->bind_param("ii", $product_id, $category_id);
			$stmt->execute();
			$stmt->close();
		}

		if ($stmt = self::connection()->prepare("SELECT product_id, name, description, price FROM product WHERE id = ?")) {
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$stmt->bind_result($product_id, $name, $description, $price);
			$stmt->fetch();
			$stmt->close();
		}

		if ($stmt = self::connection()->prepare("SELECT category_id, name, catype_id FROM category WHERE id = ?")) {
			$stmt->bind_param("i", $category_id);
			$stmt->execute();
			$stmt->bind_result($category_id, $name, $catype_id);
			$stmt->fetch();
			$stmt->close();
		}

		if ($product_id == NULL OR $category_id == NULL) {
			return FALSE;
		}
		else {
			return TRUE;
		}

		// $product_has_category_id=mysqli_insert_id(self::connection());
		// return $product_has_category_id;

	}

	public static function removeProductWithId($id)
	{

		// This should also remove all entries in product_has_category with this product_id

		$i = 0;
		$everythig_deleted = FALSE;
		while ($everythig_deleted == FALSE) {
			$i = $i + 1;
			if ($stmt = self::connection()->prepare("SELECT product_id, name, description, price FROM product WHERE id = ?")) {
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($product_id, $name, $description, $price);
				$stmt->fetch();
				$stmt->close();
			}

			if ($product_id == NULL) {
				$everythig_deleted = TRUE;
			}
			else {
				if ($stmt = self::connection()->prepare("DELETE FROM product WHERE id = ?")) {
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->close();
				}

				if ($stmt = self::connection()->prepare("DELETE FROM product_has_category WHERE product_id = ?")) {
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->close();
				}
			}
		}

		if ($i == 1) {
			return FALSE;
		}
		else {
			return TRUE;
		}
	}

	public static function removeUserWithId($id)
	{
		if ($stmt = self::connection()->prepare("SELECT user_id, name, identifier, password FROM user WHERE id = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($user_id, $name, $identifier, $password);
			$stmt->fetch();
			$stmt->close();
		}

		if ($user_id == NULL) {
			return FALSE;
		}
		else {
			if ($stmt = self::connection()->prepare("DELETE FROM user WHERE id = ?")) {
				$stmt->bind_param("i", $user_id);
				$stmt->execute();
				$stmt->close();
			}

			return TRUE;
		}
	}

	public static function removeCategoryWithId($id)
	{

		// This should also remove all entries in product_has_category with those category_id

		$i = 0;
		$everythig_deleted = FALSE;
		while ($everythig_deleted == FALSE) {
			$i = $i + 1;
			if ($stmt = self::connection()->prepare("SELECT category_id, name, catype_id FROM category WHERE id = ?")) {
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($category_id, $name, $catype_id);
				$stmt->fetch();
				$stmt->close();
			}

			if ($category_id == NULL) {
				$everythig_deleted = TRUE;
			}
			else {
				if ($stmt = self::connection()->prepare("DELETE FROM category WHERE id = ?")) {
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->close();
				}

				if ($stmt = self::connection()->prepare("DELETE FROM product_has_category WHERE category_id = ?")) {
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->close();
				}
			}
		}

		if ($i == 1) {
			return FALSE;
		}
		else {
			return TRUE;
		}
	}

	public static function removeCatypeWithId($id)
	{

		// This should also remove all categories with this catype_id
		// And subsequently, all entries in product_has_category with those category_id

		$i = 0;
		$everythig_deleted = FALSE;
		while ($everythig_deleted == FALSE) {
			$i = $i + 1;
			if ($stmt = self::connection()->prepare("SELECT catype_id, name, allows_multiple FROM catype WHERE id = ?")) {
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($catype_id, $name, $allows_multiple);
				$stmt->fetch();
				$stmt->close();
			}

			if ($catype_id == NULL) {
				$everythig_deleted = TRUE;
			}
			else {
				if ($stmt = self::connection()->prepare("DELETE FROM catype WHERE id = ?")) {
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->close();
				}

				if ($stmt = self::connection()->prepare("SELECT category_id, name, catype_id FROM category WHERE catype_id = ?")) {
					$stmt->bind_param("i", $catype_id);
					$stmt->execute();
					$stmt->bind_result($category_id, $name, $catype_id);
					$k = 0;
					while ($stmt->fetch()) {
						$cat_id[$k] = strval($category_id);
						$k = $k + 1;
					}

					$stmt->close();
				}

				for ($kk = 0; $kk < $k; $kk = $kk + 1) {
					DB::removeCategoryWithId($cat_id[$kk]);
				}
			}
		}

		if ($i == 1) {
			return FALSE;
		}
		else {
			return TRUE;
		}
	}

	public static function getCategoryIdsForProduct($product_id)
	{

		// Fetch all categories of a product. use product_has_category

		if ($stmt = self::connection()->prepare("SELECT product_id, category_id FROM product_has_category WHERE product_id = ?")) {
			$stmt->bind_param("i", $product_id);
			$stmt->execute();
			$stmt->bind_result($prod_id, $category_id);
			$i = 0;
			while ($stmt->fetch()) {
				$cat_ids[$i] = $category_id;
				$i = $i + 1;
			}

			$stmt->close();
		}

		if ($prod_id == NULL) {
			return FALSE;
		}
		else {
			return $cat_ids;
		}
	}
	
	public static function getProducts($categoryIdArray,$resultLimit,$resultOffset)
	{
		//$categoryIdArray is an array of category id's
		//Esto puede ser un poquito complicado desde el punto de vista logico pero tu eres bien inteligente.
		//Creo que el truco es usar EXISTS un verguero de veces: http://dev.mysql.com/doc/refman/5.0/en/exists-and-not-exists-subqueries.html
		//En resumen va a ser asi: SELECT * FROM product WHERE EXISTS(...) AND EXISTS(...) AND ...
		//Donde hay un exist por cada categoris, y ese EXIST es un SELECT de product_has_category
		//No lo tengo totalmente resuelto en mi mente, pero tu seguramente puedes figure it out tan rapido
		//como yo. Echale machete pues.
		
		if($categoryIdArray==NULL || count($categoryIdArray)==0){
			return FALSE;
		}else{
			$number_of_categories = sizeof($categoryIdArray);
			$sql_statement= "SELECT product_id, category_id FROM product_has_category AS table1 WHERE category_id = ?";
			for($i=1;$i<=$number_of_categories-1;$i=$i+1){
				$sql_statement=$sql_statement." AND EXISTS (SELECT product_id, category_id FROM product_has_category AS table".strval($i+1)." WHERE category_id = ? AND table".strval($i).".product_id = table".strval($i+1).".product_id";
			}
		}
			
		$type = "i";
		for($i=1;$i<=$number_of_categories-1;$i=$i+1){
			$sql_statement=$sql_statement.")";
			$type = $type."i";
		}
		
		function refValues($arr){ 
			$refs = array();
	
			foreach ($arr as $key => $value){
				$refs[$key] = &$arr[$key]; 
			}
	
			return $refs;
		}
			
		if ($stmt = self::connection()->prepare($sql_statement)) {
			$param = $categoryIdArray;
			call_user_func_array('mysqli_stmt_bind_param', array_merge(array($stmt, $type),refValues($categoryIdArray)));
			$stmt->execute();
			$stmt->bind_result($product_id, $category_id);
			$i = 0;
			while ($stmt->fetch()) {
				$prod_ids[$i] = $product_id;
				echo $product_id;
				$i = $i + 1;
			}
			$stmt->close();
		}
	}
	

	public static function insertCategories($catype_name, $categoryNames, $allows_multiple)
	{

		//( 1 ) Primero se verifica si ya existe una entrada en la tabla catype que tenga
		//         name=$catypename.
		//(1.1) Si la hay, se obtiene el id, digamos $id, y se agregan entradas a la tabla
		//         category con "name" tomados del array $categorynames, y con catype_id=$id
		// Return the la funcion = array de todos los ids de insercion obtenidos.
       //-------------
	  
	  
	if ($stmt = self::connection()->prepare("SELECT id, name, allows_multiple FROM catype WHERE name = ?")) {
				$stmt->bind_param("s", $catype_name);
				$stmt->execute();
				$stmt->bind_result($catype_id, $name, $allows_m);
				$stmt->fetch();
				$stmt->close();
			}
		
			
			if ($catype_id==NULL){
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
			else{
				
					//(1.1) Si la hay, se obtiene el id, digamos $id, y se agregan entradas a la tabla
		//         category con "name" tomados del array $categoryNames, y con catype_id=$id
		
				
				}
				
				
		if ($categoryNames=NULL){
		return NULL;	
		}
	    else{
		$number_of_categories=sizeof($categoryNames);
		
		
		for ($i=0;$i<=$number_of_categories-1;$i=$i+1){
		
			$categories_ids[$i]=self::insertCategory($categoryNames[$i], $catype_id);
			
		}
		
		
		return $categories_ids;
		
		}
				
			}

		// Return the la funcion = array de todos los ids de insercion obtenidos.
		
		
		
		
		
		//-------------
	
	

	
	        
	public static function getAllCatypes(){
		//Devuelve todas las catypes existentes. Toda la tabla catype.
		
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
		
		
		if ($id_array==NULL){
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
		
	  if ($stmt = self::connection()->prepare("SELECT id, name, catype_id FROM category WHERE catype_id = ?")) {
			$stmt->bind_param("i",$catype_id);
			$stmt->execute();
			$stmt->bind_result($category_id, $name, $catype_id);
			$i = 0;
			while ($stmt->fetch()) {
				$id_array[$i] = $id;
				
				$array_con_todo[$i] = array("id"=>$category_id, "name"=>$name, "catype_id"=>$catype_id);
				
				$i = $i + 1;
			}

			$stmt->close();
		}
		
		
		if ($id_array==NULL){
		$result=NULL;
		
		}
		else{
		$result = $array_con_todo;
		}
		
		
		return $result;		
		
	}
	
	public static function getAllProducts(){
		//Tabla product
		
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
		
		
		if ($id_array==NULL){
		$result=NULL;
	
		}
		else{
		$result = $array_con_todo;
		}
		
		//print_r($array_con_todo);
		
		return $result;		
		
	}
}

?>