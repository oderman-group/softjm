<?php include("sesion.php");?>
<?php
$idPagina = 39;
$paginaActual['pag_nombre'] = "Categorías de Productos";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<!-- styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css2020/font-awesome-ie7.min.css">
<![endif]-->
<link href="css2020/tablecloth.css" rel="stylesheet">
<link href="css2020/responsive-tables.css" rel="stylesheet">
<link href="css2020/styles.css" rel="stylesheet">
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css2020/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css2020/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css2020/ie/ie9.css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
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
	<?php include("encabezado.php");?>
    
    
	<div class="main-wrapper">
    <div class="container-fluid">
		
	<div class="row-fluid ">
        <div class="span12">
          <div class="content-widgets gray">
            <div class="widget-head blue clearfix">
              <h3 class="pull-left">Buzón de salida</h3>
              <div class="btn-toolbar top-toolbar pull-right">
                <div class="btn-group"> <a href="#" id="gridview" class="switcher btn btn-icon"><i class="icon-th-large"></i></a> </div>
                <div class="btn-group "> <a href="#" id="listview" class="switcher active btn btn-icon"><i class=" icon-list"></i></a> </div>
              </div>
            </div>
            <div class="widget-container">
              <div>
                <div class="clearfix list-search-bar">
                  <div class="input-append pull-left">
                    <input type="text" size="16" class="input-medium">
                    <button type="button" class="btn">Search</button>
                  </div>
                  <div class="pagination pull-right">
                    <ul>
                      <li class="disabled"><a href="#">Prev</a></li>
                      <li class="active"><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">Next</a></li>
                    </ul>
                  </div>
                </div>
                <div class="list-wrap">
                  <ul id="products" class="list clearfix">
                    <!-- row 1 -->
                    <li class="clearfix">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J423</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$45.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 2 -->
                    <li class="clearfix alt">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J424</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$55.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 3 -->
                    <li class="clearfix third">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J425</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$32.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 4 -->
                    <li class="clearfix alt">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J426</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$70.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 5 -->
                    <li class="clearfix">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J427</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$99.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 6 -->
                    <li class="clearfix alt third">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J428</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$45.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 7 -->
                    <li class="clearfix">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J429</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$25.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 8 -->
                    <li class="clearfix alt">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J430</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$60.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                    <!-- row 9 -->
                    <li class="clearfix third">
                      <section class="left"> <img src="images/list-default-thumb.png" alt="default thumb" class="thumb">
                        <h3>Product Name</h3>
                        <span class="meta">Product ID: 543J431</span>
                        <p> Nam eget lacus at felis iaculis fringilla non a felis. Suspendisse eu turpis non arcu rhoncus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                      </section>
                      <section class="right"> <span class="price">$102.00</span> <span class="rate-it"></span> <span class="darkview"> <a href="javascript:void(0);" class="btn btn-extend">Read more</a> <a href="javascript:void(0);" class="btn btn-info">Add to list</a> </span> </section>
                    </li>
                  </ul>
                </div>
                <div class="clearfix list-search-bar">
                  <div class="input-append pull-left">
                    <input type="text" size="16" class="input-medium">
                    <button type="button" class="btn">Search</button>
                  </div>
                  <div class="pagination pull-right">
                    <ul>
                      <li class="disabled"><a href="#">Prev</a></li>
                      <li class="active"><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">Next</a></li>
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
	
	
	</div>
	<?php include("pie.php");?>
</div>
</body>
</html>