<?php
/**
 * Configuración de Marca del Sistema
 * 
 * Este archivo centraliza toda la información de la marca (nombre, logo, etc.)
 * para facilitar cambios futuros en un solo lugar.
 * 
 * @package ELISAB
 * @version 1.0
 */

// Nombre de la empresa/sistema
define('BRAND_NAME', 'ELISAB');
define('BRAND_NAME_FULL', 'ELISAB ERP & CRM');

// Logos
define('BRAND_LOGO', 'usuarios/files/elisab-logo.png');
define('BRAND_LOGO_MINI', 'assets-login/images/logo-mini.svg');
define('BRAND_FAVICON', 'assets-login/images/favicon.png');

// Información de la empresa
define('BRAND_DESCRIPTION', 'Sistema ERP y CRM ' . BRAND_NAME . ' - Gestión empresarial integral');
define('BRAND_AUTHOR', BRAND_NAME . ' Team');

// Copyright
define('BRAND_COPYRIGHT', 'Copyright &copy; ' . date('Y') . ' ' . BRAND_NAME . '. Todos los derechos reservados.');

// Logo temporal usando ícono
// En lugar de imagen, usaremos un ícono de Font Awesome
define('USE_ICON_AS_LOGO', true);
define('BRAND_LOGO_ICON', 'fas fa-chart-line'); // Ícono temporal para ERP
define('BRAND_LOGO_TEMP_URL', 'usuarios/files/elisab-logo.png'); // Ruta del logo definitivo
?>

