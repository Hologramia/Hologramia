<html>
    <head>
          <title></title>
        <style>
            .sliderDual label{ 
                font-weight: bolder;    
            }

           /* Ponemos la barra B sobre la A*/
            .sliderDual div:nth-child(1){ 
                 position: absolute;
            }

           /* Subimos de nivel del elemento desplazable de la barra A */
            .sliderDual div:first-child span.yui3-slider-thumb { 
                z-index:5;   
            }
        </style>
    </head>

    <body class="yui3-skin-sam">
       <div>
              <div id="sliderDual" class="sliderDual">
              <div id="sliderA"></div>
                <div id="sliderB"></div>
                <div><label id="txtSliderA"></label> - <label id="txtSliderB"></label></div>     
            </div>
       </div>

<script src="http://yui.yahooapis.com/3.14.1/build/yui/yui-min.js"></script>


<script>
YUI().use('slider', function (Y) {

    //Definimos los valor máximo y mínimo
    var MAX = 200;
    var MIN = 50;

    var MAJOR_STEP = 5;
    var MINOR_STEP = 1;

    var LENGTH = MAX - MIN;
    var MIN_RANGE = 5;

    //Objeto de configuración de ambos sliders
    var config = {
         max: MAX,
         min: MIN,
         majorStep: MAJOR_STEP,
         minorStep: MINOR_STEP,
         value: MAX,
         length: LENGTH,
         clickableRail: false
    };

    //Inicializamos las etiquetas de los sliders
    var sliderDual = Y.one("#sliderDual");

    sliderDual.one('#txtSliderA').setHTML(MIN);
    sliderDual.one('#txtSliderB').setHTML(MAX);

    //Slider superior
    var sliderA = new Y.Slider(config);
    sliderA.set('thumbUrl', 'thumb-x.png');
    sliderA.setValue(MIN);
    sliderA.render('#sliderA');

    //Slider inferior
    var sliderB = new Y.Slider(config).render('#sliderB');

    //Eventos de los sliders

   sliderA.after('thumbMove', function(ev){
      if ( this.getValue() > sliderB.getValue() - MIN_RANGE ){
          this.setValue( sliderB.getValue() - MIN_RANGE ); 
      }
      sliderDual.one('#txtSliderA').setHTML(this.getValue());        
   });

   sliderB.after('thumbMove', function(ev){
      if ( this.getValue() < sliderA.getValue() + MIN_RANGE ){
          this.setValue( sliderA.getValue() + MIN_RANGE ); 
      }
      sliderDual.one('#txtSliderB').setHTML(this.getValue());        
   });

});
</script>