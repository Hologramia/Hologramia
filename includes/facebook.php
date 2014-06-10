<?php 
session_start(); 
include_once("config.php");

if(isset($_GET["logout"]) && $_GET["logout"]==1)
{
	//User clicked logout button, distroy all session variables.
	session_destroy();
	header('Location: '.$return_url);
}
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en-gb" lang="en-gb" >
<head>
<!-- load jQuery from google repository -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<title>login facebook</title>
<link href="style/buttonstyle.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
if(!isset($_SESSION['logged_in']))
{
    echo '<div id="results">';
    echo '<!-- results will be placed here -->';
    echo '</div>';
    echo '<div id="LoginButton">';
	echo '<a href="#" rel="nofollow" onClick="javascript:CallAfterLogin();return false;">Login en Facebook</a>';
    echo '</div>';

}
else
{
	echo $_SESSION['user_name'];
	echo '<a href="?logout=1" onclick="FB.logout(function(response) {});">cerrar sesion</a>';
}
?>

<div id="fb-root"></div>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
		appId: '<?php echo $appId; ?>',
		cookie: true,xfbml: true,
		channelUrl: '<?php echo $return_url; ?>channel.php',
		oauth: true
		});
	};
(function() {
	var e = document.createElement('script');
	e.async = true;e.src = document.location.protocol +'//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);}());

function CallAfterLogin(){
	FB.login(function(response) {		
		if (response.status === "connected") 
		{

			LodingAnimate(); //Animate login
			FB.api('/me', function(data) {
				
			  if(data.email == null)
			  {
					//Facbeook user email is empty, you can check something like this.
					alert("You must allow us to access your email id!"); 
					ResetAnimate();

			  }else{

                                        //ADOLFO:CAMBIO: Pasando el access token como parametro
					AjaxResponse(response.authResponse.accessToken);
			  }
			  
		  });
		 }
	},
	{scope:'<?php echo $fbPermissions; ?>'});
}

//functions
//ADOLFO:CAMBIO: Ahora esta funcion toma el access token como parametro
function AjaxResponse(accessToken)
{
	 //Load data from the server and place the returned HTML into the matched element using jQuery Load().
         //ADOLFO:CAMBIO: Pasando el access token actual para que el php lo use para obtener la informacion del usuario
	 $( "#results" ).load( "process_facebook.php?accessToken="+accessToken );
}

//Show loading Image
function LodingAnimate() 
{
	$("#LoginButton").hide(); //hide login button once user authorize the application
	$("#results").html('<img src="img/ajax-loader.gif" /> Please Wait Connecting...'); //show loading image while we process user
}

//Reset User button
function ResetAnimate() 
{
	$("#LoginButton").show(); //Show login button 
	$("#results").html(''); //reset element html
}

</script>

</body>
</html>