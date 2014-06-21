<?php
class DB{
	public static $connection = NULL;

	public static function connection(){
		if (self::$connection != NULL){
			return self::$connection;
		}
		
		$con = new mysqli("localhost", "root", "", "hologramia_schema");
		if ($con->connect_errno) {
    		echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
    		self::$connection = NULL;
    		return self::$connection;
		}
		
		self::$connection = $con;
		
		return $con;
	}
	
	//JUSTO:
	//For every query that you do, please use the following syntax:
	//mysql_query( $yourQuery , DB::connection());
	//Don't worry, this doesn't mean the connection is performed again and again
	// every time.
	//If you read the function above, you will see it is not performed every time.
	
	public static function insertProduct($name,$description,$price){
		//JUSTO:
		//Add product to database table
		//Return the insertion id using this: http://www.php.net//manual/en/function.mysql-insert-id.php
		 
		 if ($stmt = self::connection()->prepare("INSERT INTO catype (name, description, price) VALUES (? ,?, ?)")) {
	$stmt->bind_param("ssd",$name, $description, $price);
	$stmt->execute();
	$stmt->close();	
	}
		
		$product_id=mysql_insert_id();
		
		return $product_id;
	}
	
	public static function getProductById($id){
		//JUSTO:
		//This function must read from the products table and return the product having
		//this id. If the product is not found, then it returns NULL
		
		//$result=mysql_query("SELECT * FROM product WHERE id=".$id.";", DB::connection());

   //return mysql_fetch_row($result);
   
     if ($stmt = self::connection()->prepare("SELECT * FROM product WHERE id = ?")) {
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->bind_result($id,$name,$description,$price);
    $stmt->fetch();
    $stmt->close();
	if($id==NULL){
	return NULL;
	}
    else{
    return array($id,$name,$description,$price);
	}
	
    }else{
    return NULL;
    }
		
		
	}
	
	public static function insertUser($name,$identifier/*this is a string*/,$password){
		//JUSTO: Insert user, return the insert id as in insertProduct();
		
		 $result=mysql_query( "INSERT INTO user (name, identifier, password) VALUES ("."'".$name."'"." ,"."'".$identifier."'"." ,"."'".$password."'".")" , DB::connection());
		 
		  if ($stmt = self::connection()->prepare("INSERT INTO catype (name, identifier, password) VALUES (? ,?, ?)")) {
	$stmt->bind_param("ssd", $name, $identifier, $password);
	$stmt->execute();
	$stmt->close();	
	}
		 	 
		
		$user_id=mysql_insert_id();
		
		return $user_id;
		
	}
	
	public static function getUserById($id){


    if ($stmt = self::connection()->prepare("SELECT * FROM user WHERE id = ?")) {
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->bind_result($id,$identifier,$password);
    $stmt->fetch();
    $stmt->close();
	if($id==NULL){
	return NULL;
	}
    else{
    return array($id,$identifier,$password);
	}
	
    }else{
    return NULL;
    }

		
	}
	
	public static function insertCategoryType($name,$allows_multiple){
		//JUSTO: A "category type" is something like "Color, Size, Gender"
		//This is NOT the same as a "category", which would be something like "Red, 15, Male"
		//Do not edit this function unless this is clear.
		
		//This function inserts into the table catype.
		
		//$allows_multiple is a boolean (true or false).
		//In MySQL this is a TINYINT(1) (1 or 0). This means whether a product is allowed
		//to have multiple categories in this category type. For example: A product cannot have
		//multiple "Size" (talla), but it can have multiple "Color".
	    
		
		
		if ($stmt = self::connection()->prepare("INSERT INTO catype (name, allows_multiple) VALUES (? ,?)")) {
	$stmt->bind_param("si",$name,$allows_multiple);
	$stmt->execute();
	$stmt->close();	
	}
		$catype_id=mysql_insert_id();
		
		return $catype_id;
		 
	}
	
	public static function getCategoryTypeById($id){

   if ($stmt = self::connection()->prepare("SELECT * FROM catype WHERE id = ?")) {
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->bind_result($id,$name,$allows_multiple);
    $stmt->fetch();
    $stmt->close();
	if($id==NULL){
	return NULL;
	}
    else{
    return array($id,$name,$allows_multiple);
	}
	
    }else{
    return NULL;
    }
		
	}
	
	

	


	
}
?>