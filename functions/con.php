<?php

class CON{
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
}

?>