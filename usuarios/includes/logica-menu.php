<?php
$sql = "SELECT m.*, p.pag_ruta AS ruta_pagina
				FROM modulos m
				LEFT JOIN paginas p ON m.mod_id_pagina = p.pag_id";

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

foreach ($menu as &$menu_item) {
  $menu_item['submenus'] = array();
  $menu_item['paginas'] = array();
  foreach ($submenus as $submenu) {
    if ($submenu['mod_padre'] == $menu_item['mod_id']) {
      $submenu['paginas'] = array();
      foreach ($paginas as $pagina) {
        if ($pagina['mod_padre'] == $submenu['mod_id']) {
          $submenu['paginas'][$pagina['mod_id']] = $pagina;
        }
      }
      $menu_item['submenus'][$submenu['mod_id']] = $submenu;
    }
  }
  foreach ($paginas as $pagina) {
    if ($pagina['mod_padre'] == $menu_item['mod_id']) {
      $menu_item['paginas'][] = $pagina;
    }
  }
  unset($menu_item);
}
