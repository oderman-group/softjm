<?php 
include("sesion.php");
$idPagina = 167;
include("includes/verificar-paginas.php");
include("includes/head.php");
?>

<!-- styles -->
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">


<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/responsive-tables.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/ZeroClipboard.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<script src="js/jquery.collapsible.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script>

/*===============================================
TBALE THEMES
==================================================*/
$(function() {
        $(".paper-table").tablecloth({
          theme: "paper",
          striped: true,
          sortable: true,
          condensed: false
        });
      });
$(function() {
	  $('.data-grid').dataTable({ "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		  });
      });	  
/**=========================
LEFT NAV ICON ANIMATION 
==============================**/
$(function () {
    $(".left-primary-nav a").hover(function () {
        $(this).stop().animate({
            fontSize: "30px"
        }, 200);
    }, function () {
        $(this).stop().animate({
            fontSize: "24px"
        }, 100);
    });
});
</script>
<script>
$(function(){
	$("a.switcher").bind("click", function(e){
		e.preventDefault();
		var theid = $(this).attr("id");
		var theproducts = $("ul#products");
		var classNames = $(this).attr('class').split(' ');
		var gridthumb = "images/grid-default-thumb.png";
		var listthumb = "images/list-default-thumb.png";
		if($(this).hasClass("active")) {
			// if currently clicked button has the active class
			// then we do nothing!
			return false;
		} else {
			// otherwise we are clicking on the inactive button
			// and in the process of switching views!
  			if(theid == "gridview") {
				$(this).addClass("active");
				$("#listview").removeClass("active");
				// remove the list class and change to grid
				theproducts.removeClass("list");
				theproducts.addClass("grid");
				// update all thumbnails to larger size
				$("img.thumb").attr("src",gridthumb);
			}
			else if(theid == "listview") {
				$(this).addClass("active");
				$("#gridview").removeClass("active");
				// remove the grid view and change to list
				theproducts.removeClass("grid")
				theproducts.addClass("list");
				// update all thumbnails to smaller size
				$("img.thumb").attr("src",listthumb);
			} 
		}
	});
});
</script>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
    <div class="container-fluid">
		
	<div class="row-fluid ">
        <div class="span12">
          <div class="content-widgets gray">
            <div class="widget-head blue clearfix">
              <h3 class="pull-left">Buzón de salida</h3>
              
            </div>
            <div class="widget-container">
              <div>
				  
                <div class="clearfix list-search-bar">
                  <div class="input-append pull-left">
					 <form action="buzon.php" method="get"> 
                    	<input type="text" size="20" name="busqueda" class="input-medium span12" value="<?=$_GET["busqueda"];?>">
                    	<button type="submit" class="btn">Buscar</button>
					</form>	 
                  </div>
					
					<!--
                  <div class="pagination pull-right">
                    <ul>
                      <li class="disabled"><a href="#">Anterior</a></li>
                      <li class="active"><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">Siguiente</a></li>
                    </ul>
                  </div>
					-->
					
                </div>
				  
                <div class="list-wrap">
                  <ul id="products" class="list clearfix">
                    
					<?php
					$filtro = '';
					  
					if($datosUsuarioActual['usr_tipo']!=1){$filtro .=" AND buz_usuario='".$_SESSION["id"]."'";}
					if($_GET["busqueda"]!=""){$filtro .=" AND (buz_destino LIKE '%".$_GET["busqueda"]."%' OR buz_observacion LIKE '%".$_GET["busqueda"]."%')";} 
					  
					$consulta = mysql_query("SELECT * FROM buzon_salida
					INNER JOIN usuarios ON usr_id=buz_usuario
					WHERE buz_id=buz_id $filtro
					ORDER BY buz_id DESC
					",$conexion);
					$no = 1;
					while($res = mysql_fetch_array($consulta)){
					?> 
					 <!-- row 1 -->
                    <li class="clearfix">
                      <section class="left"> 
                        <h3><?=$tipoBuzon[$res['buz_tipo']];?> </h3>
						<span class="meta">Fecha: <?=$res['buz_fecha'];?></span>  
						<span class="meta">Remitente: <?=$res['usr_nombre'];?></span>
                        <span class="meta">Correo destino: <?=$res['buz_destino'];?></span>
                        <p><?=$res['buz_observacion'];?></p>
						  <span class="meta">REF: <?=$res['buz_referencia'];?></span>
                      </section>
						
                      <section class="right"> <span class="price"><?=$estadoBuzon[$res['buz_estado']];?></span> 
						  <span class="rate-it"></span>
						  
						  <span class="darkview">
							  <?php if($res['buz_estado']==2){?>
							  	<a href="enviar-portafolios.php?cte=<?=$res['buz_cliente'];?>" class="btn btn-info">Reintentar</a>
							  <?php }?>
						  
							  <a href="#" class="btn btn-danger">Eliminar</a>
						  </span> 
						  
						</section>
						
                    </li>
					  
					  <?php }?>
					  
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
		
		</div>
	</div>	
	
	
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>