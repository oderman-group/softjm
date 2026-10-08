<?php
$filtro = '';
										if (!empty($_GET['vista']) && in_array($_GET['vista'], ['web', 'pdt', 'stock', 'utilidad'], true) && empty($_GET[$_GET['vista']])) {
											$_GET[$_GET['vista']] = 1;
										}
										if (isset($_GET['habilitado']) && ($_GET['habilitado'] === '0' || $_GET['habilitado'] === '1')) {
											$filtro .= " AND prod_habilitado='" . (int) $_GET['habilitado'] . "'";
										}
										if(isset($_GET["grupo1"])){
											if (is_numeric($_GET["grupo1"])) {
												$filtro .= " AND prod_grupo1='" . $_GET["grupo1"] . "'";
											}
										}
										if(isset($_GET["grupo2"])){
											if (is_numeric($_GET["grupo2"])) {
												$filtro .= " AND prod_categoria='" . $_GET["grupo2"] . "'";
											}
										}
										if(isset($_GET["marca"])){
											if (is_numeric($_GET["marca"])) {
												$filtro .= " AND prod_marca='" . $_GET["marca"] . "'";
											}
										}
										if(isset($_GET["grupo3"])){
											if (is_numeric($_GET["grupo3"])) {
												$filtro .= " AND prod_grupo3='" . $_GET["grupo3"] . "'";
											}
										}
										if(isset($_GET["web"])){
											if (is_numeric($_GET["web"])) {
												$filtro .= " AND prod_visible_web=1";
											}
										}
										if(isset($_GET["pdt"])){
											if (is_numeric($_GET["pdt"])) {
												$filtro .= " AND prod_precio_predeterminado=1";
											}
										}
										if(isset($_GET["utilidad"])){
											if ($_GET["utilidad"] == 1) {
												$filtro .= " AND prod_utilidad=0 OR prod_utilidad=''";
											}
										}
										if(isset($_GET["nopdt"])){
											if (is_numeric($_GET["nopdt"])) {
												$filtro .= " AND prod_precio_predeterminado=0";
											}
										}
										if(isset($_GET["stock"])){
											if (is_numeric($_GET["stock"])) {
												$filtro .= " AND prod_existencias<=0";
											}
										}
										if(isset($_GET["nodctomax"])){
											if (is_numeric($_GET["nodctomax"])) {
												$filtro .= " AND prod_descuento1<=0";
											}
										}
										if(isset($_GET["busqueda"])){
											if ($_GET["busqueda"] != "") {
												$filtro .= " AND (prod_referencia LIKE '%" . $_GET["busqueda"] . "%' OR prod_nombre LIKE '%" . $_GET["busqueda"] . "%')";
											}
										}

										$conteoProductos = $conexionBdPrincipal->query("SELECT COUNT(*) FROM productos WHERE prod_id_empresa='".$idEmpresa."' $filtro");
										$filaConteoProductos = mysqli_fetch_row($conteoProductos);
										$numTotalProductos = (int) ($filaConteoProductos[0] ?? 0);
										$paginasProductos = max(1, (int) ceil($numTotalProductos / $porPaginaProducto));
										$paginaListaProductos = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, (int) $_GET['inicio']) : 1;
										if ($paginaListaProductos > $paginasProductos) {
											$paginaListaProductos = $paginasProductos;
										}
										$offsetProductos = ($paginaListaProductos - 1) * $porPaginaProducto;
										$paramsPaginaProducto = $_GET;
										unset($paramsPaginaProducto['inicio'], $paramsPaginaProducto['todo']);
										$urlPaginaProducto = static function (int $pagina, int $por) use ($paramsPaginaProducto): string {
											$params = $paramsPaginaProducto;
											$params['por'] = $por;
											if ($pagina > 1) {
												$params['inicio'] = $pagina;
											}
											return 'productos.php?' . http_build_query($params);
										};

										$consulta = $conexionBdPrincipal->query("SELECT * FROM productos 
										LEFT JOIN productos_categorias ON catp_id=prod_categoria 
										LEFT JOIN (
											SELECT catp_id AS G2ID, catp_nombre AS G2NAME FROM productos_categorias
										) grupo1 ON G2ID=prod_grupo1
										LEFT JOIN marcas ON mar_id=prod_marca 
										WHERE prod_id=prod_id AND prod_id_empresa='".$idEmpresa."' 
										$filtro 
										LIMIT ".$offsetProductos.", ".$porPaginaProducto."
										");

										$no = $offsetProductos + 1;
										$visible = array("SI", "SI", "NO");
										$estadoVisible = array(2, 2, 1);
										$comision=0;
										$precioListaUSD=0;
										$precioWeb=0;

