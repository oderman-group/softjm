<?php 
include("sesion.php");
include("head.php");
?>

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
<script src="js/jquery.sparkline.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.selection.js"></script>
<script src="js/excanvas.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.time.js"></script>
<script src="js/jquery.flot.tooltip.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.collapsible.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.gritter.js"></script>
<script src="js/tiny_mce/jquery.tinymce.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script>
/*===============================================
TEXT EDITOR
==================================================*/

        $(function() {
		$('textarea.chat-inputbox').tinymce({
			script_url : 'js/tiny_mce/tiny_mce.js',
			theme : "simple"
			});
		});

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
	  
/**=========================
SPARKLINE MINI CHART
==============================**/
$(function () {
    $(".line-min-chart").sparkline([50, 10, 2, 3, 40, 5, 26, 10, 15, 20, 40, 60], {
        type: 'line',
        width: '80',
        height: '40',
        lineColor: '#2b2b2b',
        fillColor: '#e5e5e5',
        lineWidth: 2,
        highlightSpotColor: '#0e8e0e',
        spotRadius: 3,
        drawNormalOnTop: true
    });
    $(".bar-min-chart").sparkline([50, 10, 2, 3, 40, 5, 26, 10, -15, 20, 40, 60], {
        type: 'bar',
        height: '40',
        barWidth: 4,
        barSpacing: 1,
        barColor: '#007f00'
    });
    $(".pie-min-chart").sparkline([3, 5, 2, 10, 8], {
        type: 'pie',
        width: '40',
        height: '40'
    });
	/* facturación*/
    $(".tristate-min-chart").sparkline([1, 1, 0, 1, -1, -1, 1, -1, 0, 0, 1, 1], {
        type: 'tristate',
        height: '40',
        posBarColor: '#bf005f',
        negBarColor: '#ff7f00',
        zeroBarColor: '#545454',
        barWidth: 4,
        barSpacing: 1
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
<script type="text/javascript">
/*===============================================
FLOT BAR CHART
==================================================*/

    var data7_1 = [
        [1354586000000, 153],
        [1354587000000, 658],
        [1354588000000, 198],
        [1354589000000, 663],
        [1354590000000, 801],
        [1354591000000, 1080],
        [1354592000000, 353],
        [1354593000000, 749],
        [1354594000000, 523],
        [1354595000000, 258],
        [1354596000000, 688],
        [1354597000000, 364]
    ];
    var data7_2 = [
        [1354586000000, 53],
        [1354587000000, 65],
        [1354588000000, 98],
        [1354589000000, 83],
        [1354590000000, 80],
        [1354591000000, 108],
        [1354592000000, 120],
        [1354593000000, 74],
        [1354594000000, 23],
        [1354595000000, 79],
        [1354596000000, 88],
        [1354597000000, 36]
    ];
    $(function () {
        $.plot($("#visitors-chart #visitors-container"), [{
            data: data7_1,
            label: "Page View",
            lines: {
                fill: true
            }
        }, {
            data: data7_2,
            label: "Online User",
            points: {
                show: true
            },
            lines: {
                show: true
            },
            yaxis: 2
        }
        ],
        {
            series: {
                lines: {
                    show: true,
                    fill: false
                },
                points: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: "#ffffff",
                    symbol: "circle",
                    radius: 5,
                },
                shadowSize: 0,
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1
            },
            colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
				  shifts: { 
					  x: -100                     //10
				  },
                defaultTheme: false
            },
            xaxis: {
                mode: "time",
                timeformat: "%0m/%0d %0H:%0M"
            },
            yaxes: [{
                /* First y axis */
            }, {
                /* Second y axis */
                position: "right" /* left or right */
            }]
        }
        );
    });
</script>
<script type="text/javascript">
/*===============================================
FLOT PIE CHART
==================================================*/

    $(function () {
        var data = [{
            label: "Page View",
            data: 70
        }, {
            label: "Online User",
            data: 30
        }];
        var options = {
            series: {
                pie: {
                    show: true,
					innerRadius: 0.5,
            show: true
                }
            },
            legend: {
                show: true
            },
            grid: {
                hoverable: true,
                clickable: true
            },
			 colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
				shifts: { 
					  x: -100                     //10
				  },
                defaultTheme: false
            }
        };
        $.plot($("#pie-chart-donut #pie-donutContainer"), data, options);
    });
</script>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    <?php include("barra-izq.php");?>
	
	<div class="main-wrapper">
		<div class="container-fluid">
			
			<div style="padding: 5px; display: none;" align="center">
				<a href="<?=$configu['conf_url_top'];?>" target="_blank"><img src="../../usuarios/files/publicidad/<?=$configu['conf_banner_top'];?>"></a>
			</div>
			
			<div class="row-fluid ">
				<div class="span3">
					<div class="board-widgets orange small-widget">
						<a href="documentos.php"><span class="widget-icon icon-file"></span><span class="widget-label">Mis documentos</span></a>
					</div>
				</div>
                
                <div class="span3">
					<div class="board-widgets blue small-widget">
						<a href="materiales.php"><span class="widget-icon icon-suitcase"></span><span class="widget-label">Mis productos</span></a>
					</div>
				</div>

				
				<div class="span3">
					<div class="board-widgets bondi-blue small-widget">
						<a href="https://store.jmequipos.com" target="_blank"><span class="widget-icon icon-shopping-cart"></span><span class="widget-label">Tienda virtual</span></a>
					</div>
				</div>
				
				<div class="span3">
					<div class="board-widgets orange small-widget">
						<a href="contactos.php"><span class="widget-icon icon-group"></span><span class="widget-label">Mis contactos</span></a>
					</div>
				</div>
				
			</div>


			
			<!--
			<div class="row-fluid">
                <div class="span12">
                <div class="content-widgets white">
						<div class="widget-head light-blue">
							<h3><i class="icon-comments-alt"></i> Chat</h3>
						</div>
						<div class="widget-container">
							<div class="tab-widget tabbable tabs-left chat-widget">
								<ul class="nav nav-tabs" id="chat-tab">
									<li class="active"><a href="#user"><span class="user-online"></span><i class="icon-user"></i> Online User </a></li>
									<li><a href="#user1"><span class="user-offline"></span><i class="icon-user"></i> Offline User </a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="user">
										<div class="conversation">
											<a href="#" class="pull-left media-thumb"><img src="images/item-pic.png" width="34" height="34" alt="user"></a>
											<div class="conversation-body ">
												<h4 class="conversation-heading">Jhon Says:</h4>
												<p>
													 Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
												</p>
											</div>
										</div>
										
										
										<div class="conversation right-align">
											<a href="#" class="pull-right media-thumb"><img src="images/item-pic.png" width="34" height="34" alt="user"></a>
											<div class="conversation-body ">
												<h4 class="conversation-heading">Marfy Says:</h4>
												<p>
													 Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
												</p>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="user1">
										 User Offline
									</div>
									<div class="tab-pane" id="user2">
										 User Offline
									</div>

									<div class="tab-pane" id="user6">
										<div class="conversation">
											<a href="#" class="pull-left media-thumb"><img src="images/item-pic.png" width="34" height="34" alt="user"></a>
											<div class="conversation-body ">
												<h4 class="conversation-heading">Jhon Says:</h4>
												<p>
													 Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
												</p>
											</div>
										</div>
										
										<div class="conversation right-align">
											<a href="#" class="pull-right media-thumb"><img src="images/item-pic.png" width="34" height="34" alt="user"></a>
											<div class="conversation-body ">
												<h4 class="conversation-heading">Marfy Says:</h4>
												<p>
													 Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
												</p>
											</div>
										</div>
									</div>
									
								</div>
								<div class="chat-input">
									<textarea class="chat-inputbox span12" name="input"></textarea>
									<button class="btn btn-primary btn-large" type="button"><i class="icon-ok"></i> Send</button>
								</div>
							</div>
						</div>
					</div>
                    -->
                </div>
			</div>
		</div>
	</div>
	<?php include("pie.php");?>
</div>
</body>
</html>