<nav class="navbar navbar-default navbar-fixed-top labarra fill1" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <div class="logotipo">
    	<a class="eti" href="base.php"><img class="img-responsive" src="Imagens/Hologramia.png"/></a>
    </div>
  </div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  
				<!-- compra segura -->  
  <div class="collapse navbar-collapse navbar-ex1-collapse">
  	
    <!-- /compra segura -->
    <div class="prr navbar-nav">
    	<ul class="nav navbar-nav">
            <li class="dropdown">
            	<div class="dropdown-toggle" data-toggle="dropdown">
                	<div class="miniaturas" id="franela"></div>
                    <b class="caret menu"></b>
                </div>
                <div class="dropdown-menu miniatura">
                	<div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<div class="redsocial" id="franela"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="sweater"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="franelilla"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="pantalon"></div>
                        </div>
                    </div>
                    <div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<div class="redsocial" id="bermuda"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="leggins"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="boxer"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="falda"></div>
                        </div>
                    </div>
                    <div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<div class="redsocial" id="vestido"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="panty"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="bota"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="tacon"></div>
                        </div>
                    </div>
                    <div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<div class="redsocial" id="zandalia"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="zapato"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="lente"></div>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="sombrero"></div>
                        </div>
                    </div>
                    <div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<div class="redsocial" id="accesorio"></div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
      	<form class="navbar-form navbar-left" role="search" style="width:300px">
      		<div class="form-group">
            	<div class="input-group">
        			<input type="text" class="form-control" id="busqueda" placeholder="Buscar" action="{$link->getPageLink('search')|escape:'html'}" method="get">
      				<span class="input-group-btn">
                    	<button type="submit" class="btn btn-search fa fa-search" style="height:34px"></button>
                    </span>
                </div>
            </div>
      	</form>
      		</li>
        </ul>
    </div>
  

    <ul class="nav navbar-nav navbar-right">
    	<div class="prr navbar-nav">
    	  <ul class="nav navbar-nav">
      		<li>
        		<div class="globo serv">
      				<div class="miniaturas" id="bs"></div>
                	<div class="chat">Puedes pagar con deposito, a domicilio, tarjeta de crédito o débito y con trasferencias. Todo es en bolívares.
        				<div class="chat-arrow-border"></div>
						<div class="chat-arrow"></div>
        			</div>
      	  		</div>
      		</li>
        	<li>
        		<div class="globo serv">
            		<div class="miniaturas" id="candado"></div>
                	<div class="chat">Hologramia cuenta con un sistema de seguridad infalible. Tus datos están absolutamente seguros.
        				<div class="chat-arrow-border"></div>
						<div class="chat-arrow"></div>
        			</div>
            	</div>
        	</li>
        	<li>
        		<div class="globo serv">
            		<div class="miniaturas" id="envios"></div>
                	<div class="chat">Si tu compra es mayor que Bs. 2000,00 tu envío es gratis.
        				<div class="chat-arrow-border"></div>
						<div class="chat-arrow"></div>
        			</div>
            	</div>
        	</li>
        	<li>
        		<div class="globo serv">
            		<div class="miniaturas" id="quince"></div>
                	<div class="chat">Después de recibir tu mercancía tienes 15 días continuos para el cambio gratis.
                		<div class="chat-arrow-border"></div>
						<div class="chat-arrow"></div>
        			</div>
            	</div>
        	</li>
    	  </ul>
  	</div>
      <li><div class="divisor"></div></li>
      <li class="dropdown">
            	<a href="#" class="dropdown-toggle fa fa-users fa-2x" data-toggle="dropdown"><b class="caret"></b></a>
            	<div class="dropdown-menu miniatura navbar-default navbar-nav">
                	<div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<a href="#" class="fa fa-facebook fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<a href="#" class="fa fa-twitter fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<a href="#" class="fa fa-instagram fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<a href="#" class="fa fa-youtube fa-2x"></a>
                        </div>
                    </div>
                    <div class="row text-center sn">
                    	<div class="col-xs-3">
                        	<a href="#" class="fa fa-pinterest fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<a href="#" class="fa fa-tumblr fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<a href="#" class="fa fa-google-plus fa-2x"></a>
                        </div>
                        <div class="col-xs-3">
                        	<div class="redsocial" id="soundcloud"></div>
                        </div>
                    </div>
                </div>
            </li>
      <li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle fa fa-user fa-2x" data-toggle="dropdown">+</a>
      </li>
      <li><div class="divisor"></div></li>
      <li>
      	<form class="navbar-form navbar-left">
        	<div class="input-group" style="width:50px">
            	<span class="input-group-btn">
                	<button class="btn fa fa-shopping-cart" href="#" style="height:34px; font-size:21px;"></button> 
                </span>
      			<div readonly="readonly" class="ajax_cart_quantity form-control" style="width:50px; text-align:center; cursor:pointer;">{$cart_qties}</div>
            </div>
      	</form>
      </li>
      <li><div class="divisor"></div></li>
      <li class="dropdown">
      	<!--<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Usuario<b class="caret"></b></a>
        <ul class="dropdown-menu">
            	<li><a href="#">Mi cuenta</a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="fa fa-gift"></i> Mi lista de Deseos</a></li>
                <li><a href="#"><i class="fa fa-shopping-cart"></i> Mi carrito</a></li>
            	<li class="divider"></li>
                <li><a href="#">Cerrar Sesión</a></li>
        </ul>-->
        	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-fw"></i>Ingresar<b class="caret"></b></a>
        	<ul class="dropdown-menu">
            	<li><a href="#">Iniciar Sesión</a></li>
                <li class="divider"></li>
                <li><a data-toggle="modal" href="#facelog" class="btn btn-primary btn-lg"><i class="fa fa-facebook fa-fw"></i>Iniciar con Facebook</a></li>
                <li><a href="#"><i class="fa fa-twitter fa-fw"></i>Iniciar con Twitter</a></li>
        	</ul>
      </li>
      <li><div href"#"></div></li>
    </ul>
    <ul class="nav navbar-right sn2">
    	
    </ul>
  </div>
</nav>