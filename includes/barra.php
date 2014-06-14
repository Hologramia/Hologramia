<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<style type="text/css">
    	* {
		    margin: 0;
		    padding: 0;
		}
		/*** Sticky Menu ***/
		nav {
		    background-color:#e2e8e4;
		    height: 45px;
		    position: fixed;
		    top: 0;
		    left: 0;
		    width: 100%; 
			z-index: 1
		}
		nav table {
		    width: 100%;
		    margin: auto;
		}
		nav table tr {
			height: 45px;
		    line-height: 35px;
		    display: inline-block;
		    padding-right: 50px;
		}
		nav table tr td{
		    text-decoration: none;
			height: 45px;
		    color: #fff;
		    font-weight: bold;
		}
		nav table tr td img{
		    width:130px; 
		    height:30px;
		}
		nav table tr td select ,input[type=search]
		{
			margin-bottom:20px;
		}
    </style>

    <title></title>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript"> 
function surfto(form) 
{ 
var myindex=form.dest.selectedIndex 
window.open(form.dest.options[myindex].value,"_top",""); 
} 
</script>

</head>
<body>
<form name="form">
    <div>
  <nav><img src="Imagens/Hologramia.png" width="176" height="46"></nav>
  
</form>
</body>
</html>
