
<?php

header("Content-type: text/css; charset: UTF-8");

$search_bar_height = 35;
$search_bar_width = 300;
$search_button_width = 60;
$search_bar_button_padding = 0;

$search_corner_radius = 4;

$almost_white_color = "#fdfdfd";
$very_light_gray_color = "rgb(243,243,243)";
$very_light_gray_trans_color = "rgba(243,243,243,0.7)";

$light_gray_color = "#cccccc";

$medium_gray_color = "#aaaaaa";

$selected_checkbox_color = "#44cc66";

$product_padding = 30;
$top_product_padding = 30;

$top_bar_height = 80;
$top_bar_bottom_margin = 10;
$filters_width = 170;
$cart_width = 250;

$logo_width = 200;

?>


body{
    font-family:'HelveticaNeue-Light';
    margin:0px;
    padding:0px;
    border:0px;
    background-color:<?php print($very_light_gray_color); ?>;
}

.main{
    position:absolute;
    left:50%;
    top:50%;
    -ms-transform: translate(-50%,-50%); /* IE 9 */
    -webkit-transform: translate(-50%,-50%); /* Chrome, Safari, Opera */
    transform: translate(-50%,-50%);
    width:300px;
    padding-bottom:50px;
}

.main-not-centered
{
	top:50px;
	-ms-transform: translate(-50%,0); /* IE 9 */
    -webkit-transform: translate(-50%,0); /* Chrome, Safari, Opera */
    transform: translate(-50%,0);
	text-align:center;
}

.log-in-main > div
{
    text-align:center;
				margin-top:10px;
}

.join-main > div
{
				text-align:center;
                font-weight:bold;
}


h1
{
    color:rgba(0,0,0,0);
    background-image:url(../logo.png);
    background-position:center;
    background-size:contain;
    background-repeat:no-repeat;
    margin:0px;
}

h1.main-header{
    display:inline-block;
    border:0px;
    padding:0px;
    margin-right:10px;
    font-weight:normal;
    height:<?php print($top_bar_height); ?>px;
    width:<?php print($logo_width); ?>px;
    vertical-align:middle;
}

h1.centered-header
{
    height:80px;
    margin-bottom:10px;
}

h2{font-weight:normal;text-align:center}

h2.sides-header
{
    padding:0px;
    margin:0px;
}

#filters h2{display:none;}

a {text-decoration:none}

#main-div>div{
    border-style:solid;
    border-width:0px;
    border-color:rgb(230,230,230);
    border-radius:6px;
    position:relative;
    margin-top:10px;
    background-color:<?php print($medium_gray_color); ?>;
    background-color:white;
    padding:10px;
}

.cart-price{
				text-align:right;
                position:absolute;
                bottom:10px;
                right:10px;
}

.cart-calculation{
    color:<?php print($light_gray_color); ?>;
}

.carrito{
    display:inline-block;
    width:35px;
    height:35px;
				background-image:url(../cart.png);
                background-position:center;
                background-size:100% auto;
                background-repeat:no-repeat;
                vertical-align:middle;
                margin-top:-7px;
                margin-right:5px;
}

.cart-total-container{
    overflow:auto;
}

.cart-subtotal-text{
    clear:both;
				float:left;
}

.cart-subtotal-value{
    clear:right;
				float:right;
}



h3{
    font-weight:normal;
    text-align:center;
    margin:0px;
    padding:0px;
}

h4{
    font-weight:normal;
    text-align:center;
    margin-top:5px;
    margin-bottom:5px;
}

#top-banner{
    position:fixed;
    padding:0px;
    top:0px;
    left:0px;
    right:0px;
    height:<?php print($top_bar_height); ?>px;
    background-color:<?php print($very_light_gray_color); ?>;
    z-index:50;
    box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
    -webkit-box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
    -moz-box-shadow: 0px 3px 3px <?php print($very_light_gray_color); ?>;
}

#top-right-bar{
    position:fixed;
    top:10px;
    right:10px;
    padding:10px;
    z-index:1000;
    font-family:"HelveticaNeue-Medium"
}


a:link{color:rgba(50,70,200,1)}
a:active{color:rgba(50,70,200,1)}
a:visited{color:rgba(50,70,200,1)}
a:hover{color:rgba(50,70,200,1)}



#search-box-container
{
    /*background-color:blue;*/
    position:relative;
    display:inline-block;
    vertical-align:middle;
    height:<?php print($search_bar_height); ?>px;
    width:<?php print($search_bar_width+$search_button_width+$search_bar_button_padding); ?>px;
}

#search-box-container>input[type=text]{
    position:absolute;
    top:0px;
    left:0px;
    height:<?php print($search_bar_height); ?>px;
    padding:0px; margin:0px;
    border-style:solid;border-color:<?php print($light_gray_color); ?>;border-width:1px;
    width:<?php print($search_bar_width); ?>px;
    font-size:15px;
    display:inline-block;
    z-index:100;
    border-top-left-radius:<?php print($search_corner_radius); ?>px;
    border-bottom-left-radius:<?php print($search_corner_radius); ?>px;
}
#search-box-container>input[type=submit]{
    position:absolute;
    top:0px;
    right:0px;
    height:<?php print($search_bar_height+2); ?>px;
    padding:0px;
    width:<?php print($search_button_width); ?>px;
    margin:0px;
    border-style:solid;border-color:<?php print($light_gray_color); ?>;border-width:1px;
    background-color:<?php print($light_gray_color); ?>;
    background-image:url(../magnifying.png);
    background-position:center;
    background-repeat:no-repeat;
    background-size: auto 70%;
    display:inline-block;
    cursor:pointer;
    border-top-right-radius:<?php print($search_corner_radius); ?>px;
    border-bottom-right-radius:<?php print($search_corner_radius); ?>px;
}

#filters, #right-column
{
    border-style:solid;
    border-width:0px;
    border-radius:0px;
}

#filters h3, #right-column h3{
    color:#555;
    padding:3px;
}

#filters{
    position:absolute;
    left:0px;
    top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
    width:<?php print($filters_width); ?>px;
    margin-right:<?php print($product_padding/2); ?>px;
}


#right-column{
    position:absolute;
    right:0px;
    top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
    width:<?php print($cart_width); ?>px;
    margin-left:<?php print($product_padding/2); ?>px;
}

#right-column>div{
    border-style:solid;
    border-width:0px;
    border-color:rgb(230,230,230);
    border-radius:6px;
    position:relative;
    margin-top:10px;
    background-color:<?php print($medium_gray_color); ?>;
    background-color:white;
    padding:10px;
}



.category-set{
    padding-top:7px;
    padding-bottom:7px;
    background-color:<?php print($almost_white_color); ?>;
    /*box-shadow: inset 0px 2px 2px #eaeaea;
     -webkit-box-shadow: inset 0px 2px 2px #eaeaea;
     -moz-box-shadow: inset 0px 2px 2px #eaeaea;*/
    border-radius:5px;
}

#product-list{
    position:absolute;
    left:<?php print($filters_width); ?>px;
    right:<?php print($cart_width); ?>px;
    top:<?php print($top_bar_height+$top_bar_bottom_margin); ?>px;
    min-height:500px;
    text-align:center;
    padding-bottom:20px;
}

#product-list>div{
    position:relative;
    display:inline-block;
    border-style:solid;
    border-width:0px;
    border-color:light-gray;
    background-color:white;
    padding:5px;
    border-radius:5px;
    width:165px;
    height:270px;
    margin:<?php print($product_padding/2); ?>px;
    margin-top:<?php print($top_product_padding); ?>px;
    margin-bottom:<?php print($top_product_padding-$product_padding); ?>px;
    vertical-align:middle;
    overflow:hidden;
    box-shadow: 0px 2px 2px #ddd;
    -webkit-box-shadow: 0px 2px 2px #ddd;
    -moz-box-shadow: 0px 2px 2px #ddd;
}

.product-name{
    text-align:center;
    padding-top:20px;
    padding-bottom:20px;
    overflow:hidden;
}

.product-price{
    text-align:left;
    position:absolute;
    left:0px;
    bottom:0px;
    background:white;
    width:100%;
    box-shadow: 0px -10px 10px #fff;
    -webkitbox-shadow: 0px -10px 10px #fff;
    -moz-box-shadow: 0px -10px 10px #fff;
}

.cart-link,.cart-link-no{
    z-index:40;
    position:absolute;
    right:5px;
    bottom:0px;
    height:30px;
    width:30px;
    background-image:url(../cart.png);
    background-position:center;
    background-repeat:no-repeat;
    background-size:auto 100%;
}

.cart-link-no{
    pointer-events:none;
    opacity:0.3;
}

.product-thumb{
    display:block;
    height:130px;
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-position:center;
    margin-top:10px;
}

.category-box {
    padding:3px;
    padding-left:10px;
    border-style:solid;
    border-width:0px;
    border-color:rgb(230,230,230);
    position:relative;
}

.category-title {
    color:#999999;
}

.category-title + a, .category-title + a + a {
    border-style:solid;
    border-width:1px;
    border-color:#dddddd;
    background-color:#dddddd;
    position:absolute;
    top:2px;
    right:10px;
    bottom:2px;
    width:18px;
    border-radius:5px;
}

/*a:empty, click to select a+a:selected, click to unselect*/
.category-title + a{
    display:block;
    text-decoration:none;
    color:white;
    font-weight:bold;
}

.category-title + a + a {
    display:none;
    text-decoration:none;
    color:white;
    font-weight:bold;
}

.category-title[data-selected]{
    color:#000000;
}

.category-title[data-selected] + a{
    display:none;
}

.category-title[data-selected] + a + a{
    display:block;
    background-color:<?php print($selected_checkbox_color) ?>;
    border-color:<?php print($selected_checkbox_color) ?>;
    background-image:url(../checkmark.png);
    background-size:auto 90%;
    background-repeat:no-repeat;
    background-position:center;
}

.cart-product-name{
    width:70%;
    min-height:50px;
    margin-bottom:5px;
}

.remove-cart-link{
    position:absolute;
    top:5px;
    right:5px;
    width:20px;
    height:20px;
    border-style:solid;
    border-width:0px;
    border-color:<?php print($medium_gray_color); ?>;
    border-radius:11px;
    background-image:url(../redx.png);
    background-position:center;
    background-size:100% auto;
    background-repeat:no-repeat;
    background-color:white;
    
}



.increment-link, .decrement-link {
    display:inline-block;
    width:16px;
    height:16px;
    background-position:center;
    background-repeat:no-repeat;
    background-size:90% auto;
    vertical-align:middle;
    border-radius:5px;
    background-color:black;
}

.increment-link{
    margin-left:5px;
    background-image:url(../plus.png)
}

.decrement-link {
    margin-left:5px;
    background-image:url(../minus.png)
}


.cart-buttons-wrapper{
    clear:both;
    position:relative;
    padding-top:10px;
}

.shipping-link{
    display:block;
    clear:both;
    text-decoration:none;
    color:white;
    background-color:<?php print($medium_gray_color) ?>;
    padding-top:10px;
    padding-bottom:10px;
    border-radius:6px;
    text-align:center;
}

.checkout-link{
    background-color:rgb(50,200,50);
}


.explanation
{
				font-weight:bold;
}

form
{
    margin:0px;padding:0px;border:0px;
}

form.search-form{display:inline;}

form.log-in-form
{
				display:block;
                text-align:center;
}

form.log-in-form input[type='text'], form.log-in-form input[type='password']
{
				display:block;
                margin:0px;
                padding:0px;
                width:100%;
                margin-top:10px;
                height:35px;
                font-size:14pt;
                border-style:solid;
                border-width:1px;
                border-color:<?php print($light_gray_color); ?>;
                border-radius:6px;
}

form.log-in-form input[type='submit']
{
				display:block;
                margin:0px;
                padding:0px;
                width:100%;
                margin-top:10px;
                height:35px;
                border-style:solid;
                border-width:0px;
                border-radius:6px;
                font-size:18px;
                color:white;
                background-color:rgb(100,190,100);
                cursor:pointer;
}

.error-message
{
    color:red;
    margin-top:10px;
    text-align:center;
}

.normal-text
{
    margin-top:10px;
    text-align:center;
}




