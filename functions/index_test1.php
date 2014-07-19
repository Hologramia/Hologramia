<!DOCTYPE html>
<html>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<head>
		<title>
			Hologramia
		</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h1>Hologramia</h1>
		<?php includePHP("toolbar") ?>
		<div id="toolbar" data-extern-class="toolbar" data-extern-id="toolbar">
			<?php include "toolbar.php"; ?>
		</div>
		<div id="searchbar" data-extern-class="searchbar" data-extern-id="searchbar">
			<?php include "searchbar.php"; ?>
		</div>
		<div id="cart" data-extern-class="cart" data-extern-id="cart">
			<?php include "cart.php"; ?>
		</div>
		<div id="products" data-extern-class="products" data-extern-id="products">
			<?php include "products.php"; ?>
		</div>
	</body>
</html>