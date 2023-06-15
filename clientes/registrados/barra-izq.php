<div class="leftbar leftbar-close clearfix">
		<div class="admin-info clearfix">
			<div class="admin-thumb">
				<i class="icon-user"></i>
			</div>
			<div class="admin-meta">
				<ul>
					<li class="admin-username"><?=$datosUsuarioActual[1];?></li>
					<li><a href="perfil-editar.php">Editar Perfil</a></li>
					<li><a href="../salir.php"><i class="icon-lock"></i> Salir</a></li>
				</ul>
			</div>
		</div>
		<div class="left-nav clearfix">
			<p>&nbsp;</p>
			<div class="side-widget">
						<div class="board-widgets light-blue">
							<div class="board-widgets-head clearfix">
								<h4 class="pull-left">Saldo disponible</h4>
								<a href="#" class="widget-settings"><i class="icon-money"></i></a>
							</div>
							<div class="board-widgets-content">
								<div class="progress progress-striped active min progress-info">
									<div class="bar" style="width: 10%;">
									</div>
								</div>
								<div class="stat-text progress-stat">
                                    <span style="font-size: 17px;">$<?=number_format($datosUsuarioActual['cli_saldo'],0,",",".");?></span><br>
                                    <span style="font-size: 13px; color: darkblue;">Vence:<br> <?=$configu['conf_vencimiento_puntos'];?></span>
								</div>
							</div>
						</div>
					</div>
			<?php if($configu['conf_banner_lateral']!=""){?>
				<p style="padding: 5px;">
					<a href="<?=$configu['conf_url_lateral'];?>" target="_blank"><img src="../../usuarios/files/publicidad/<?=$configu['conf_banner_lateral'];?>"></a>
				</p>
			<?php }?>

			<div class="left-secondary-nav tab-content">
				<div class="tab-pane" id="forms">
					<h4 class="side-head">Forms</h4>
					<ul id="nav" class="accordion-nav">
						<li><a href="form-elements.html"><i class="icon-list-alt"></i> Form Elements <span class="notify-tip">50</span></a></li>
						<li><a href="form-components.html"><i class="icon-th"></i> Form Components </a></li>
						<li><a href="form-validation.html"><i class="icon-ok-circle"></i> Form Validation<span>Quisque commodo lectus sit amet sem </span></a></li>
						<li><a href="form-wizard.html"><i class="icon-external-link"></i> Wizard </a></li>
						<li><a href="text-editor.html"><i class="icon-pencil"></i> WYSIWYG editor <span>Quisque commodo lectus sit amet sem </span></a></li>
					</ul>
				</div>
				<div class="tab-pane" id="features">
					<h4 class="side-head">Features</h4>
					<ul class="accordion-nav">
						<li><a href="tables.html"><i class="icon-table"></i> Basic Tables</a></li>
						<li><a href="table-cloth.html"><i class="icon-table"></i> Tables-Theme</a></li>
						<li><a href="data-tables.html"><i class=" icon-th"></i> Data Tables</a></li>
						<li><a href="grid.html"><i class=" icon-columns"></i> Grid</a></li>
						<li><a href="typography.html"><i class=" icon-font"></i> Typography</a></li>
						<li><a href="calendar.html"><i class=" icon-calendar"></i> Calendar</a></li>
						<li><a href="file-manager.html"><i class=" icon-folder-open"></i> File Manager</a></li>
					</ul>
				</div>
				<div class="tab-pane" id="ui-elements">
					<h4 class="side-head">UI Elements</h4>
					<ul class="accordion-nav">
						<li><a href="components-widgets.html"><i class=" icon-list-alt"></i> UI Components</a></li>
						<li><a href="buttons-icons.html"><i class=" icon-th-large"></i> Buttons &amp; Icons</a></li>
					</ul>
				</div>
				<div class="tab-pane" id="pages">
					<h4 class="side-head">Pages</h4>
					<ul class="accordion-nav">
						<li><a href="#"><i class="icon-minus-sign"></i> Error Pages</a>
						<ul>
							<li><a href="page-403.html"><i class=" icon-file-alt"></i> 403 Error Page</a></li>
							<li><a href="page-404.html"><i class=" icon-file-alt"></i> 404 Error Page</a></li>
							<li><a href="page-405.html"><i class=" icon-file-alt"></i> 405 Error Page</a></li>
							<li><a href="page-500.html"><i class=" icon-file-alt"></i> 500 Error Page</a></li>
							<li><a href="page-503.html"><i class=" icon-file-alt"></i> 503 Error Page</a></li>
						</ul>
						</li>
						<li><a href="login.html"><i class=" icon-unlock"></i> Login Page</a></li>
						<li><a href="gallery.html"><i class=" icon-picture"></i> Gallery</a></li>
						<li><a href="pricing.html"><i class="icon-money"></i> Pricing Page</a></li>
						<li><a href="chat.html"><i class="icon-comments"></i> Chat Page</a></li>
					</ul>
				</div>
				<div class="tab-pane" id="chart">
					<h4 class="side-head">Charts</h4>
					<ul class="accordion-nav">
						<li><a href="flot-chart.html"><i class="icon-bar-chart"></i> Flot Charts</a></li>
						<li><a href="google-chart.html"><i class="icon-google-plus-sign"></i> Goolge<span>Quisque commodo lectus sit amet sem </span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>