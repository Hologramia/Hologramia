<?php
class DB{
	public static $connection = NULL;

	public static function connection(){
		if (self::$connection != NULL){
			return self::$connection;
		}
		
		$con = mysql_connect("localhost","root","");
		mysql_select_db("hologramia_scheme");
		
		self::$connection = $con;
		
		return $con;
	}
	
	//JUSTO:
	//For every query that you do, please do:
	//mysql_query( $yourQuery , DB::connection());
	//Don't worry, this doesn't mean the connection is performed again and again
	// every time.
	//If you read the function above, you will see it is not performed every time.
	
	public static function getProductById($id){
		//JUSTO:
		//This function must read from the products table and return the product with
		//this id. If it is not found, then it returns NULL
	}
	
	public static function insertProduct($name,$description,$price){
		//JUSTO:
		//Add product to database table
		//Return the insertion id using this: http://www.php.net//manual/en/function.mysql-insert-id.php
	}




	
}
?>