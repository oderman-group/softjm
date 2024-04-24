<?php
$sql = "SELECT m.*, p.pag_ruta AS ruta_pagina FROM modulos_empresa
        INNER JOIN modulos m ON mod_id=mxe_id_modulo
				LEFT JOIN paginas p ON m.mod_id_pagina = p.pag_id
        WHERE mxe_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
        ORDER BY mxe_posicion";

$result = $conexionBdAdmin->query($sql);
$menu = array();
$submenus = array();
$paginas = array();

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if ($row['mod_tipo'] == 0) {
      $menu[$row['mod_id']] = $row;
    } elseif ($row['mod_tipo'] == 1) {
      $submenus[$row['mod_id']] = $row;
    } elseif ($row['mod_tipo'] == 2) {
      $paginas[$row['mod_id']] = $row;
    }
  }
}

// Ordenar los elementos dentro de cada menú según mxe_posicion
function sortByPosition($a, $b) {
    return $a['mxe_posicion'] - $b['mxe_posicion'];
}

// Ordenar los menús por posición
usort($menu, 'sortByPosition');

// Ordenar submenús y páginas dentro de cada menú
foreach ($menu as &$menu_item) {
    $menu_item['submenus'] = array();
    $menu_item['paginas'] = array();

    // Ordenar submenús por posición
    uasort($submenus, 'sortByPosition');
    foreach ($submenus as $submenu) {
        if ($submenu['mod_padre'] == $menu_item['mod_id']) {
            $submenu['paginas'] = array();

            // Ordenar páginas por posición
            uasort($paginas, 'sortByPosition');
            foreach ($paginas as $pagina) {
                if ($pagina['mod_padre'] == $submenu['mod_id']) {
                    $submenu['paginas'][$pagina['mod_id']] = $pagina;
                }
            }

            $menu_item['submenus'][$submenu['mod_id']] = $submenu;
        }
    }

    // Agregar páginas directamente al menú principal
    foreach ($paginas as $pagina) {
        if ($pagina['mod_padre'] == $menu_item['mod_id']) {
            $menu_item['paginas'][] = $pagina;
        }
    }
}
unset($menu_item);
