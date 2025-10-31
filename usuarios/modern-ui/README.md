# 🎨 ELISAB Modern UI - Sistema de Diseño

Sistema de diseño moderno y profesional para ELISAB ERP & CRM

## 📁 Estructura

```
modern-ui/
├── css/
│   └── modern-theme.css    # Sistema completo de diseño
├── js/
│   └── modern-core.js       # Funcionalidades JavaScript
└── README.md               # Esta documentación
```

## 🚀 Implementación

### Componentes Base Creados

1. **`includes/head-modern.php`** - Header HTML moderno
2. **`includes/sidebar-modern.php`** - Menú lateral con navegación dinámica
3. **`includes/topbar-modern.php`** - Barra superior con búsqueda y usuario
4. **`includes/footer-modern.php`** - Footer y cierre de HTML

### Cómo Usar en una Página

```php
<?php
include("sesion.php");
$idPagina = X;

// Incluir configuración de marca
include("../brand-config.php");

// Configuración de la página
$pageTitle = "Título de la Página";
$breadcrumbs = [
  ['name' => 'Inicio', 'url' => 'index.php'],
  ['name' => 'Actual']
];

// CSS y JS adicionales (opcional)
$additionalCSS = ['ruta/al/archivo.css'];
$footerJS = ['ruta/al/archivo.js'];

// Incluir head moderno
include("includes/head-modern.php");

// Incluir sidebar
include("includes/sidebar-modern.php");
?>

<!-- Contenido Principal -->
<div class="modern-main-content">
  
  <?php include("includes/topbar-modern.php"); ?>

  <div class="page-container">
    
    <!-- Tu contenido aquí -->
    
  </div>

</div>

<?php
// JavaScript inline opcional
$inlineScript = "console.log('Hola mundo');";

include("includes/footer-modern.php");
?>
```

## 🎨 Sistema de Colores

### Colores Principales
- **Primary:** `#667eea` (Morado)
- **Secondary:** `#764ba2` (Violeta)
- **Accent:** `#48bb78` (Verde)

### Estados
- **Success:** `#48bb78` (Verde)
- **Error:** `#f56565` (Rojo)
- **Warning:** `#ed8936` (Naranja)
- **Info:** `#4299e1` (Azul)

### Uso en CSS

```css
/* Variables CSS disponibles */
var(--primary-color)
var(--secondary-color)
var(--success-color)
var(--error-color)
var(--warning-color)
var(--info-color)
```

## 📦 Componentes Disponibles

### Cards

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Título</h3>
  </div>
  <div class="card-body">
    <!-- Contenido -->
  </div>
</div>
```

### Botones

```html
<button class="btn-modern btn-primary">
  <i class="fas fa-save"></i>
  Guardar
</button>

<button class="btn-modern btn-outline">Cancelar</button>
<button class="btn-modern btn-success">Éxito</button>
<button class="btn-modern btn-danger">Eliminar</button>
```

### Cards de Estadísticas

```html
<div class="card">
  <div class="stat-card">
    <div class="stat-icon primary">
      <i class="fas fa-chart-line"></i>
    </div>
    <div class="stat-content">
      <div class="stat-label">Ventas</div>
      <div class="stat-value">$125,000</div>
      <div class="stat-change positive">
        <i class="fas fa-arrow-up"></i>
        <span>+12% vs mes anterior</span>
      </div>
    </div>
  </div>
</div>
```

### Grid System

```html
<div class="grid grid-cols-4">
  <div>Columna 1</div>
  <div>Columna 2</div>
  <div>Columna 3</div>
  <div>Columna 4</div>
</div>

<!-- Opciones: grid-cols-1, grid-cols-2, grid-cols-3, grid-cols-4 -->
```

### Tablas

```html
<table class="table-modern">
  <thead>
    <tr>
      <th>Columna 1</th>
      <th>Columna 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Dato 1</td>
      <td>Dato 2</td>
    </tr>
  </tbody>
</table>
```

## 🎯 JavaScript Utilities

### Notificaciones

```javascript
// Mostrar notificación
showNotification('Operación exitosa', 'success', 3000);
showNotification('Error al guardar', 'error', 3000);
showNotification('Advertencia', 'warning', 3000);
showNotification('Información', 'info', 3000);
```

### Loading Overlay

```javascript
// Mostrar loading
showLoading();

// Ocultar loading
hideLoading();
```

### Confirmación

```javascript
confirmAction('¿Estás seguro?', function() {
  // Acción si confirma
  console.log('Confirmado');
});
```

## 📱 Responsive

El sistema es completamente responsive con breakpoints:

- **Desktop:** > 992px
- **Tablet:** 768px - 992px
- **Mobile:** < 768px

## 🎨 Utilidades CSS

### Espaciado

```html
<div class="mt-4">Margin top</div>
<div class="mb-3">Margin bottom</div>
<!-- mt-0 a mt-5, mb-0 a mb-5 -->
```

### Alineación

```html
<div class="text-center">Centrado</div>
<div class="text-right">Derecha</div>
<div class="text-left">Izquierda</div>
```

## 🔧 Personalización

### Cambiar Colores

Edita `modern-ui/css/modern-theme.css` en la sección `:root`:

```css
:root {
  --primary-color: #TU_COLOR;
  /* ... más variables */
}
```

### Agregar Estilos Personalizados

```php
<?php
$inlineStyles = "
  .mi-clase-custom {
    color: red;
  }
";
?>
```

## 📄 Páginas de Ejemplo

### Dashboard Moderno
- **Archivo:** `index-modern.php`
- **Características:**
  - Accesos rápidos con cards coloridas
  - Estadísticas en tiempo real
  - Gráficos interactivos
  - Diseño responsive

## 🎓 Mejores Prácticas

1. **Siempre incluir brand-config.php** para tener acceso a constantes de marca
2. **Definir $pageTitle** para el título de la página
3. **Usar breadcrumbs** para mejorar navegación
4. **Incluir CSS/JS adicionales** solo si son necesarios
5. **Mantener la estructura** de layout para consistencia

## 🔄 Migración desde Diseño Antiguo

1. Reemplazar `include("includes/head.php")` por `include("includes/head-modern.php")`
2. Agregar `include("includes/sidebar-modern.php")`
3. Envolver contenido en:
   ```html
   <div class="modern-main-content">
     <?php include("includes/topbar-modern.php"); ?>
     <div class="page-container">
       <!-- Tu contenido aquí -->
     </div>
   </div>
   ```
4. Reemplazar `include("includes/pie.php")` por `include("includes/footer-modern.php")`

## 🐛 Debugging

Para debug, abre la consola del navegador. El sistema registra:
- `✓ ELISAB Modern UI initialized` cuando se carga correctamente
- Eventos de sidebar, tooltips, dropdowns, etc.

## 📞 Soporte

Para dudas o mejoras, contactar al equipo de desarrollo.

---

**Versión:** 1.0.0  
**Última actualización:** 2025

