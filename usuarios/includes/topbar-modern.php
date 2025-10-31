<!-- Topbar Moderno -->
<header class="modern-topbar">
  
  <!-- Lado Izquierdo -->
  <div class="topbar-left">
    <h1 class="topbar-title"><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></h1>
    
    <?php if(isset($breadcrumbs) && is_array($breadcrumbs)): ?>
      <nav class="topbar-breadcrumb" aria-label="breadcrumb">
        <?php foreach($breadcrumbs as $index => $crumb): ?>
          <?php if($index > 0): ?>
            <span class="breadcrumb-separator">/</span>
          <?php endif; ?>
          
          <?php if(isset($crumb['url'])): ?>
            <a href="<?= $crumb['url'] ?>" style="color: var(--text-secondary); text-decoration: none;">
              <?= $crumb['name'] ?>
            </a>
          <?php else: ?>
            <span style="color: var(--text-primary); font-weight: 600;">
              <?= $crumb['name'] ?>
            </span>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
  </div>

  <!-- Lado Derecho -->
  <div class="topbar-right">
    
    <!-- Búsqueda Global -->
    <div class="topbar-search">
      <i class="fas fa-search topbar-search-icon"></i>
      <input 
        type="text" 
        class="topbar-search-input" 
        placeholder="Buscar en el sistema..."
        aria-label="Búsqueda global"
      >
    </div>

    <!-- Notificaciones -->
    <button class="topbar-icon-btn" data-toggle="notifications" aria-label="Notificaciones">
      <i class="fas fa-bell"></i>
      <?php 
      // Aquí puedes poner la lógica para contar notificaciones no leídas
      $notificaciones_count = 0; // Cambiar por query real
      if($notificaciones_count > 0): 
      ?>
        <span class="badge"><?= $notificaciones_count > 9 ? '9+' : $notificaciones_count ?></span>
      <?php endif; ?>
    </button>

    <!-- Mensajes -->
    <button class="topbar-icon-btn" data-toggle="messages" aria-label="Mensajes">
      <i class="fas fa-envelope"></i>
    </button>

    <!-- Ayuda -->
    <button class="topbar-icon-btn" data-toggle="help" aria-label="Ayuda" onclick="window.open('https://docs.google.com', '_blank')">
      <i class="fas fa-question-circle"></i>
    </button>

    <!-- Usuario -->
    <div class="topbar-user" data-dropdown="user-menu">
      <div class="topbar-user-avatar">
        <?php 
        // Iniciales del usuario
        $nombreUsuario = $_SESSION["dataAdicional"]["nombre_completo"] ?? 'Usuario';
        $iniciales = '';
        $palabras = explode(' ', $nombreUsuario);
        foreach($palabras as $palabra) {
          if(!empty($palabra)) {
            $iniciales .= strtoupper($palabra[0]);
            if(strlen($iniciales) >= 2) break;
          }
        }
        echo $iniciales;
        ?>
      </div>
      <div class="topbar-user-info">
        <div class="topbar-user-name"><?= $nombreUsuario ?></div>
        <div class="topbar-user-role">
          <?= $_SESSION["dataAdicional"]["nombre_rol"] ?? 'Usuario' ?>
        </div>
      </div>
      <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-secondary);"></i>
    </div>

  </div>

</header>

<!-- Dropdown de Usuario (oculto por defecto) -->
<div id="user-menu" class="dropdown-menu" style="display: none; position: fixed; background: white; border-radius: 12px; box-shadow: var(--shadow-xl); padding: 8px; min-width: 200px; z-index: 1001;">
  <a href="perfil-editar.php" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: var(--text-primary); text-decoration: none; border-radius: 8px; transition: background 150ms ease;">
    <i class="fas fa-user-circle" style="font-size: 18px;"></i>
    <span>Mi Perfil</span>
  </a>
  <a href="configuracion.php" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: var(--text-primary); text-decoration: none; border-radius: 8px; transition: background 150ms ease;">
    <i class="fas fa-cog" style="font-size: 18px;"></i>
    <span>Configuración</span>
  </a>
  <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--border-color);">
  <a href="../salir.php" onclick="return confirm('¿Estás seguro de cerrar sesión?')" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: var(--error-color); text-decoration: none; border-radius: 8px; transition: background 150ms ease;">
    <i class="fas fa-sign-out-alt" style="font-size: 18px;"></i>
    <span>Cerrar Sesión</span>
  </a>
</div>

<style>
.dropdown-menu a:hover {
  background: var(--bg-hover);
}
.dropdown-menu.show {
  display: block !important;
  animation: fadeInDown 200ms ease;
}
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

