<?php
// Incluir lógica del menú si no está incluida
if (!isset($menu)) {
  include("logica-menu.php");
}
?>

<!-- Sidebar Moderno -->
<aside class="modern-sidebar">
  <!-- Header del Sidebar -->
  <div class="sidebar-header">
    <a href="index.php" class="sidebar-logo">
      <?php if(defined('USE_ICON_AS_LOGO') && USE_ICON_AS_LOGO): ?>
        <i class="<?= BRAND_LOGO_ICON ?> sidebar-logo-icon"></i>
        <span class="sidebar-logo-text"><?= BRAND_NAME ?></span>
      <?php else: ?>
        <img src="<?= BRAND_LOGO ?>" alt="<?= BRAND_NAME ?>" style="max-height: 40px;">
      <?php endif; ?>
    </a>
    <button class="sidebar-toggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Navegación del Sidebar -->
  <nav class="sidebar-nav">
    <ul style="list-style: none; padding: 0; margin: 0;">
      
      <!-- Dashboard (siempre visible) -->
      <li class="nav-item">
        <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
          <i class="fas fa-home nav-icon"></i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>

      <?php if (!empty($menu)): ?>
        <?php foreach ($menu as $menu_item): ?>
          
          <!-- Título de sección -->
          <?php if (!empty($menu_item['mod_nombre'])): ?>
            <li class="nav-section-title"><?= strtoupper($menu_item['mod_nombre']) ?></li>
          <?php endif; ?>

          <!-- Páginas directas del menú -->
          <?php if (!empty($menu_item['paginas'])): ?>
            <?php foreach ($menu_item['paginas'] as $pagina): ?>
              <li class="nav-item">
                <a href="<?= $pagina['ruta_pagina'] ?>" class="nav-link <?= basename($_SERVER['PHP_SELF']) == basename($pagina['ruta_pagina']) ? 'active' : '' ?>">
                  <i class="<?= !empty($pagina['mod_icono']) ? $pagina['mod_icono'] : 'fas fa-circle' ?> nav-icon"></i>
                  <span class="nav-text"><?= $pagina['mod_nombre'] ?></span>
                  <?php if (!empty($pagina['mod_notificacion']) && $pagina['mod_notificacion'] > 0): ?>
                    <span class="nav-badge"><?= $pagina['mod_notificacion'] ?></span>
                  <?php endif; ?>
                </a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Submenús -->
          <?php if (!empty($menu_item['submenus'])): ?>
            <?php foreach ($menu_item['submenus'] as $submenu): ?>
              
              <!-- Título del submenú como sección -->
              <?php if (!empty($submenu['mod_nombre']) && !empty($submenu['paginas'])): ?>
                <li class="nav-section-title"><?= strtoupper($submenu['mod_nombre']) ?></li>
              <?php endif; ?>

              <!-- Páginas del submenú -->
              <?php if (!empty($submenu['paginas'])): ?>
                <?php foreach ($submenu['paginas'] as $pagina): ?>
                  <li class="nav-item">
                    <a href="<?= $pagina['ruta_pagina'] ?>" class="nav-link <?= basename($_SERVER['PHP_SELF']) == basename($pagina['ruta_pagina']) ? 'active' : '' ?>">
                      <i class="<?= !empty($pagina['mod_icono']) ? $pagina['mod_icono'] : 'fas fa-circle' ?> nav-icon"></i>
                      <span class="nav-text"><?= $pagina['mod_nombre'] ?></span>
                      <?php if (!empty($pagina['mod_notificacion']) && $pagina['mod_notificacion'] > 0): ?>
                        <span class="nav-badge"><?= $pagina['mod_notificacion'] ?></span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>

            <?php endforeach; ?>
          <?php endif; ?>

        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Sección de Ayuda y Configuración (siempre al final) -->
      <li class="nav-section-title">SISTEMA</li>
      
      <li class="nav-item">
        <a href="configuracion.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'configuracion.php' ? 'active' : '' ?>">
          <i class="fas fa-cog nav-icon"></i>
          <span class="nav-text">Configuración</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a href="perfil-editar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'perfil-editar.php' ? 'active' : '' ?>">
          <i class="fas fa-user-circle nav-icon"></i>
          <span class="nav-text">Mi Perfil</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a href="../salir.php" class="nav-link" onclick="return confirm('¿Estás seguro de cerrar sesión?')">
          <i class="fas fa-sign-out-alt nav-icon"></i>
          <span class="nav-text">Cerrar Sesión</span>
        </a>
      </li>

    </ul>
  </nav>
</aside>

