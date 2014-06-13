<?php 
session_start(); 
include_once("config.php"); //Include configuration file.
require_once('inc/facebook.php' ); //include fb sdk

//ADOLFO:CAMBIO: Obtengo el access token que me pasaron desde JavaScript
$accessToken = $_GET["accessToken"];


/* Detect HTTP_X_REQUESTED_WITH header sent by all recent browsers that support AJAX requests. */
if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
{		


	//initialize facebook sdk
	$facebook = new Facebook(array(
		'appId' => $appId,
		'secret' => $appSecret,
	));

        //ADOLFO:CAMBIO: Asigno el access token
        $facebook->setAccessToken($accessToken);
	
	$fbuser = $facebook->getUser();

	
	if ($fbuser) {
		try {
			// Proceed knowing you have a logged in user who's authenticated.

			$me = $facebook->api('/me'); //user


			$uid = $facebook->getUser();

		}
		catch (FacebookApiException $e) 
		{

			//print_r($e);
			$fbuser = null;
		}
	}
	
	// redirect user to facebook login page if empty data or fresh login requires
	if (!$fbuser){
		$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$return_url, false));
		header('Location: '.$loginUrl);
	}
	
	//user details
	$fullname = $me['first_name'].' '.$me['last_name'];
	$email = $me['email'];
     
	/* connect to mysql using mysqli */
	
	$mysqli = new mysqli($hostname, $db_username, $db_password,$db_name);
	if ($mysqli->connect_error) {
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	

        //ADOLFO:CAMBIO: Creo la tabla en caso de que no exista

        $mysqli->query("CREATE TABLE IF NOT EXISTS `usertable` (`id` int(20) NOT NULL AUTO_INCREMENT,`fbid` bigint(20) NOT NULL,`fullname` varchar(60) NOT NULL,`email` varchar(60) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");


	//Check user id in our database	
	$UserCount = $mysqli->query("SELECT COUNT(id) as usercount FROM usertable WHERE fbid=$uid")->fetch_object()->usercount; 
	$nombre=$me['first_name'].', '.$me['last_name'];$uid;

		//User is now connected, log him in
		login_user(true,$nombre,$uid);

	if($UserCount)
	{	

		//User exist, Show welcome back message
		$nombre=$me['first_name'].', '.$me['last_name'];$uid;

		//User is now connected, log him in
		login_user(true,$nombre,$uid);
	}
	else
	{
		// Insert user into Database.
		$mysqli->query("INSERT INTO usertable (fbid, fullname, email) VALUES ($uid, '$fullname','$email')");	
		login_user(true,$nombre,$uid);		
	}
	
	$mysqli->close();
}

function login_user($loggedin,$nombre,$id)
{
	/*
	function stores some session variables to imitate user login. 
	We will use these session variables to keep user logged in, until s/he clicks log-out link.
	*/
	$_SESSION['logged_in']=$loggedin;
	$_SESSION['user_name']=$nombre;
	$_SESSION['uid']=$id;
	header('location:index.php');
}
?>