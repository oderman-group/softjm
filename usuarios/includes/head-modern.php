<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= BRAND_NAME ?> ERP & CRM - Sistema de gestión empresarial">
  <meta name="author" content="<?= BRAND_NAME ?> Team">
  <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= BRAND_NAME ?> ERP</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="ico/favicon.ico">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Modern Theme CSS -->
  <link rel="stylesheet" href="modern-ui/css/modern-theme.css">
  
  <!-- jQuery (mantener compatibilidad con código existente) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Axios (para peticiones AJAX) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
  
  <?php if(isset($additionalCSS)): ?>
    <!-- CSS Adicional de la página -->
    <?php foreach($additionalCSS as $css): ?>
      <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
  <?php endif; ?>
  
  <?php if(isset($additionalJS)): ?>
    <!-- JavaScript Adicional en head -->
    <?php foreach($additionalJS as $js): ?>
      <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <style>
    /* Estilos adicionales específicos de la página */
    <?= isset($inlineStyles) ? $inlineStyles : '' ?>
  </style>
</head>
<body>
  
  <!-- Layout Moderno -->
  <div class="modern-layout">

