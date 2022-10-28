<dialog id="filtro">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Cotizaciones</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/1.php" target="_blank">  

										<div class="control-group">
											<label class="control-label">Vendedor </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="filtro" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>

				</dialog>
	
	



				<dialog id="masCotizaciones">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Clientes con más cotizaciones</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/2.php" target="_blank">  
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="masCotizaciones" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>



				<dialog id="cotizacionesValor">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Cotizaciones de más valor</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/3.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Vendedor </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="cotizacionesValor" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>




				<dialog id="gestionComercial">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Gestión comercial</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/4.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Vendedor </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="gestionComercial" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>






				<dialog id="embudoNegocios">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Embudo de negocios</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/5.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Vendedor </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="embudoNegocios" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>



				<dialog id="tickets">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Tickets</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/6.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Usuario </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="tickets" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>


				<dialog id="seguimientos">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Seguimientos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/7.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Usuario </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="seguimientos" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>



				<dialog id="gestionSeguimientos">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Gestión de Seguimientos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/8.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Usuario </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="gestionSeguimientos" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>


				<dialog id="productosVendidos">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Los productos más vendidos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/9.php" target="_blank">  


									<div class="control-group">
											<label class="control-label">Categorías </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="categoria">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="productosVendidos" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>




				<dialog id="usuariosVisitas">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Usuarios con más visitas</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/10.php" target="_blank">  
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="usuariosVisitas" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>



				<dialog id="sTecnico">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Clientes con más servicios ténicos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/11.php" target="_blank">  
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="sTecnico" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>



				<dialog id="proyectos">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Progreso de proyectos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/12.php" target="_blank"> 

									<div class="control-group">
											<label class="control-label">Usuario </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="proyectos" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>









				<dialog id="canales">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Progreso de proyectos</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/13.php" target="_blank"> 

									<div class="control-group">
											<label class="control-label">Usuario </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="canales" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>
				



	
	
	<dialog id="gestionMercadeo">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Gestión de mercadeo</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/14.php" target="_blank">  

										<div class="control-group">
											<label class="control-label">Asesores </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="gestionMercadeo" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>




				<dialog id="ventas">
					
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> Filtros Ventas</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="get" action="graficos/15.php" target="_blank">  

										<div class="control-group">
											<label class="control-label">Vendedor </label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="usuario">
													<option value="">Todos</option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label">Desde</label>
											<div class="controls">
												<input type="date" class="span12" name="desde">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Hasta</label>
											<div class="controls">
												<input type="date" class="span12" name="hasta">
											</div>
										</div>


										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
											<button name="ventas" onClick="modalFiltroClose(this)" type="button" class="btn btn-danger">Cerrar</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>

				</dialog>