/**
 * ELISAB ERP & CRM - Modern Core JavaScript
 * Funcionalidades principales del tema moderno
 * Version: 1.0.0
 */

(function() {
  'use strict';

  // ========================================
  // INICIALIZACIÓN
  // ========================================
  document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initTopbar();
    initTooltips();
    initDropdowns();
    initModals();
    initNotifications();
    console.log('✓ ELISAB Modern UI initialized');
  });

  // ========================================
  // SIDEBAR
  // ========================================
  function initSidebar() {
    const sidebar = document.querySelector('.modern-sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    const mainContent = document.querySelector('.modern-main-content');
    
    if (!sidebar || !toggleBtn) return;

    // Toggle sidebar
    toggleBtn.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      document.body.classList.toggle('sidebar-collapsed');
      
      // Guardar estado en localStorage
      const isCollapsed = sidebar.classList.contains('collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    // Restaurar estado del localStorage
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
      sidebar.classList.add('collapsed');
      document.body.classList.add('sidebar-collapsed');
    }

    // Cerrar sidebar en móvil al hacer click fuera
    if (window.innerWidth <= 992) {
      document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && sidebar.classList.contains('mobile-open')) {
          sidebar.classList.remove('mobile-open');
        }
      });
    }

    // Marcar item activo
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && currentPath.includes(href)) {
        link.classList.add('active');
      }
    });
  }

  // ========================================
  // TOPBAR
  // ========================================
  function initTopbar() {
    // Búsqueda en topbar
    const searchInput = document.querySelector('.topbar-search-input');
    if (searchInput) {
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          const query = this.value.trim();
          if (query) {
            console.log('Searching for:', query);
            // Aquí puedes implementar la lógica de búsqueda
          }
        }
      });
    }

    // Notificaciones
    const notificationBtn = document.querySelector('[data-toggle="notifications"]');
    if (notificationBtn) {
      notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Implementar dropdown de notificaciones
        showNotificationsDropdown();
      });
    }

    // Menú de usuario
    const userBtn = document.querySelector('.topbar-user');
    if (userBtn) {
      userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Implementar dropdown de usuario
        showUserDropdown();
      });
    }
  }

  // ========================================
  // TOOLTIPS
  // ========================================
  function initTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
      trigger.addEventListener('mouseenter', function() {
        const text = this.getAttribute('data-tooltip');
        const tooltip = createTooltip(text);
        
        document.body.appendChild(tooltip);
        positionTooltip(tooltip, this);
        
        // Guardar referencia
        this._tooltip = tooltip;
      });
      
      trigger.addEventListener('mouseleave', function() {
        if (this._tooltip) {
          this._tooltip.remove();
          this._tooltip = null;
        }
      });
    });
  }

  function createTooltip(text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'modern-tooltip';
    tooltip.textContent = text;
    tooltip.style.cssText = `
      position: absolute;
      background: #1a202c;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      z-index: 10000;
      pointer-events: none;
      opacity: 0;
      transition: opacity 200ms ease;
    `;
    
    // Trigger animation
    setTimeout(() => tooltip.style.opacity = '1', 10);
    
    return tooltip;
  }

  function positionTooltip(tooltip, trigger) {
    const triggerRect = trigger.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();
    
    const top = triggerRect.top - tooltipRect.height - 8;
    const left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
    
    tooltip.style.top = top + 'px';
    tooltip.style.left = left + 'px';
  }

  // ========================================
  // DROPDOWNS
  // ========================================
  function initDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-dropdown]');
    
    dropdownTriggers.forEach(trigger => {
      trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdownId = this.getAttribute('data-dropdown');
        const dropdown = document.getElementById(dropdownId);
        
        if (dropdown) {
          // Cerrar otros dropdowns
          document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdown) {
              menu.classList.remove('show');
            }
          });
          
          dropdown.classList.toggle('show');
          positionDropdown(dropdown, trigger);
        }
      });
    });
    
    // Cerrar dropdowns al hacer click fuera
    document.addEventListener('click', function() {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
      });
    });
  }

  function positionDropdown(dropdown, trigger) {
    const triggerRect = trigger.getBoundingClientRect();
    dropdown.style.top = (triggerRect.bottom + 8) + 'px';
    dropdown.style.left = triggerRect.left + 'px';
  }

  // ========================================
  // MODALES
  // ========================================
  function initModals() {
    // Botones que abren modales
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        const modalId = this.getAttribute('data-modal');
        openModal(modalId);
      });
    });
    
    // Cerrar modales
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('modal-backdrop') || 
          e.target.classList.contains('modal-close')) {
        closeAllModals();
      }
    });
    
    // ESC para cerrar modales
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });
  }

  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeAllModals() {
    document.querySelectorAll('.modal').forEach(modal => {
      modal.classList.remove('show');
    });
    document.body.style.overflow = '';
  }

  // ========================================
  // NOTIFICACIONES
  // ========================================
  function initNotifications() {
    // Sistema de notificaciones toast
    window.showNotification = function(message, type = 'info', duration = 3000) {
      const notification = createNotification(message, type);
      document.body.appendChild(notification);
      
      // Trigger animation
      setTimeout(() => notification.classList.add('show'), 10);
      
      // Auto-hide
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
      }, duration);
    };
  }

  function createNotification(message, type) {
    const colors = {
      success: '#48bb78',
      error: '#f56565',
      warning: '#ed8936',
      info: '#4299e1'
    };
    
    const icons = {
      success: 'fa-check-circle',
      error: 'fa-exclamation-circle',
      warning: 'fa-exclamation-triangle',
      info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = 'modern-notification';
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      border-left: 4px solid ${colors[type]};
      padding: 16px 20px;
      border-radius: 12px;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 300px;
      max-width: 500px;
      z-index: 10000;
      opacity: 0;
      transform: translateX(100%);
      transition: all 300ms ease;
    `;
    
    notification.innerHTML = `
      <i class="fas ${icons[type]}" style="font-size: 20px; color: ${colors[type]}"></i>
      <span style="flex: 1; color: #2d3748; font-weight: 500;">${message}</span>
      <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #a0aec0; cursor: pointer; font-size: 20px; padding: 0; width: 24px; height: 24px;">
        <i class="fas fa-times"></i>
      </button>
    `;
    
    // Show animation
    notification.classList.add('show');
    setTimeout(() => {
      notification.style.opacity = '1';
      notification.style.transform = 'translateX(0)';
    }, 10);
    
    return notification;
  }

  // ========================================
  // HELPERS DE NOTIFICACIONES DROPDOWN
  // ========================================
  function showNotificationsDropdown() {
    // Implementar aquí el dropdown de notificaciones
    console.log('Showing notifications dropdown');
  }

  function showUserDropdown() {
    // Implementar aquí el dropdown de usuario
    console.log('Showing user dropdown');
  }

  // ========================================
  // UTILIDADES GLOBALES
  // ========================================
  
  // Confirmar acciones
  window.confirmAction = function(message, callback) {
    if (confirm(message)) {
      callback();
    }
  };

  // Loading overlay
  window.showLoading = function() {
    let overlay = document.getElementById('loading-overlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'loading-overlay';
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
      `;
      overlay.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 12px; text-align: center;">
          <div class="spinner" style="width: 40px; height: 40px; border: 4px solid #e2e8f0; border-top-color: #667eea; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 16px;"></div>
          <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
          <p style="color: #2d3748; font-weight: 600; margin: 0;">Cargando...</p>
        </div>
      `;
      document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
  };

  window.hideLoading = function() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
      overlay.style.display = 'none';
    }
  };

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });

})();

