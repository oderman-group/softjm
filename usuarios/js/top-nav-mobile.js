/**

 * Navegación móvil — drawer, acordeón, notificaciones y usuario por tap.

 */

(function ($) {

	'use strict';



	var MOBILE_QUERY = window.matchMedia('(max-width: 1399px)');
	var DESKTOP_NAV_QUERY = window.matchMedia('(min-width: 1400px)');



	function isMobileNav() {

		return MOBILE_QUERY.matches;

	}



	$(function () {

		var $topNav = $('.top-nav');

		var $drawer = $('.top-nav-main-collapse');

		var $backdrop = $('.top-nav-mobile-backdrop');

		var $body = $('body');

		var $toggle = $('.top-nav-toggle');

		var $draggableMenu = $('.draggable-menu');

		var $notifyMenu = $topNav.find('.header-notify-menu');

		var $userMenu = $topNav.find('.header-user-menu');



		if (!$topNav.length) {

			return;

		}



		function updateDrawerOffset() {

			var inner = $topNav.find('.navbar-inner')[0];

			if (!inner) {

				return;

			}

			document.documentElement.style.setProperty(

				'--top-nav-offset',

				inner.getBoundingClientRect().bottom + 'px'

			);

		}



		function resetAccordion() {

			$drawer.find('.is-expanded').removeClass('is-expanded').find('[aria-expanded="true"]').attr('aria-expanded', 'false');

		}



		function closeDrawer() {

			$drawer.removeClass('in');

			$body.removeClass('top-nav-main-menu-open');

			$toggle.removeClass('is-active').attr('aria-expanded', 'false');

			$drawer.attr('aria-hidden', 'true');

			resetAccordion();

		}



		function openDrawer() {

			closeNotifyMenu();

			closeUserMenu();

			updateDrawerOffset();

			resetAccordion();

			$drawer.addClass('in');

			$body.addClass('top-nav-main-menu-open');

			$toggle.addClass('is-active').attr('aria-expanded', 'true');

			$drawer.attr('aria-hidden', 'false');

		}



		function closeNotifyMenu() {

			$notifyMenu.removeClass('open is-mobile-open');

			$notifyMenu.find('.top-nav-notify-toggle').attr('aria-expanded', 'false');

			$body.removeClass('top-nav-notify-menu-open');

		}



		function closeUserMenu() {

			$userMenu.removeClass('open is-mobile-open');

			$userMenu.find('.top-nav-user-toggle').attr('aria-expanded', 'false');

			$body.removeClass('top-nav-user-menu-open');

		}



		function closeAllPanels() {

			closeDrawer();

			closeNotifyMenu();

			closeUserMenu();

		}



		function syncToolbarToggles() {

			var mobile = isMobileNav();

			$topNav.find('.top-nav-notify-toggle, .top-nav-user-toggle').each(function () {

				if (mobile) {

					$(this).removeAttr('data-toggle');

				} else {

					$(this).attr('data-toggle', 'dropdown');

				}

			});

		}



		function closeDesktopNavDropdowns() {

			$topNav.find('.draggable-menu > li.dropdown.open').removeClass('open')

				.find('.top-nav-menu-trigger').attr('aria-expanded', 'false');

		}



		function syncMode() {

			var mobile = isMobileNav();

			$topNav.toggleClass('is-mobile', mobile);



			/* Escritorio: solo hover CSS — sin data-toggle para evitar .open persistente */

			$topNav.find('.top-nav-menu-trigger').removeAttr('data-toggle');



			syncToolbarToggles();



			if ($draggableMenu.data('ui-sortable')) {

				if (mobile) {

					$draggableMenu.sortable('disable');

				} else {

					$draggableMenu.sortable('enable');

					closeAllPanels();

					resetAccordion();

				}

			}



			if (!mobile) {

				closeAllPanels();

				resetAccordion();

				closeDesktopNavDropdowns();

			}



			updateDrawerOffset();

		}



		function toggleAccordion($item) {

			var willExpand = !$item.hasClass('is-expanded');

			$item.siblings('.is-expanded').removeClass('is-expanded')

				.find('[aria-expanded="true"]').attr('aria-expanded', 'false');



			$item.toggleClass('is-expanded', willExpand);

			$item.children('[aria-expanded]').attr('aria-expanded', willExpand ? 'true' : 'false');

		}



		function toggleNotifyMenu() {

			var willOpen = !$notifyMenu.hasClass('is-mobile-open');

			closeDrawer();

			closeUserMenu();

			$notifyMenu.toggleClass('is-mobile-open open', willOpen);

			$notifyMenu.find('.top-nav-notify-toggle').attr('aria-expanded', willOpen ? 'true' : 'false');

			$body.toggleClass('top-nav-notify-menu-open', willOpen);

		}



		function toggleUserMenu() {

			var willOpen = !$userMenu.hasClass('is-mobile-open');

			closeDrawer();

			closeNotifyMenu();

			$userMenu.toggleClass('is-mobile-open open', willOpen);

			$userMenu.find('.top-nav-user-toggle').attr('aria-expanded', willOpen ? 'true' : 'false');

			$body.toggleClass('top-nav-user-menu-open', willOpen);

		}



		syncMode();

		MOBILE_QUERY.addEventListener('change', syncMode);

		$(window).on('resize', updateDrawerOffset);



		$toggle.on('click', function (e) {

			if (!isMobileNav()) {

				return;

			}

			e.preventDefault();

			e.stopPropagation();

			if ($drawer.hasClass('in')) {

				closeDrawer();

			} else {

				openDrawer();

			}

		});



		$backdrop.on('click', closeAllPanels);



		$topNav.on('click', '[data-mobile-close="notify"]', function (e) {

			e.preventDefault();

			closeNotifyMenu();

		});



		$topNav.on('click', '[data-mobile-close="user"]', function (e) {

			e.preventDefault();

			closeUserMenu();

		});



		$topNav.on('click', '.top-nav-notify-toggle', function (e) {

			if (!isMobileNav()) {

				return;

			}

			e.preventDefault();

			e.stopImmediatePropagation();

			toggleNotifyMenu();

		});



		$topNav.on('click', '.top-nav-user-toggle', function (e) {

			if (!isMobileNav()) {

				return;

			}

			e.preventDefault();

			e.stopImmediatePropagation();

			toggleUserMenu();

		});



		$topNav.on('click', '.top-nav-menu-trigger', function (e) {

			if (!isMobileNav()) {

				e.preventDefault();

				e.stopImmediatePropagation();

				closeDesktopNavDropdowns();

				return;

			}

			e.preventDefault();

			e.stopImmediatePropagation();

			toggleAccordion($(this).closest('li.dropdown'));

		});



		$topNav.on('mouseenter', '.draggable-menu > li.dropdown', function () {

			if (!DESKTOP_NAV_QUERY.matches) {

				return;

			}

			$(this).siblings('.dropdown.open').removeClass('open')

				.find('.top-nav-menu-trigger').attr('aria-expanded', 'false');

		});



		$topNav.on('mouseleave', '.draggable-menu', function () {

			if (!DESKTOP_NAV_QUERY.matches) {

				return;

			}

			closeDesktopNavDropdowns();

		});



		$topNav.on('click', '.top-nav-submenu-trigger', function (e) {

			if (!isMobileNav()) {

				e.preventDefault();

				return;

			}

			e.preventDefault();

			e.stopImmediatePropagation();

			var $submenu = $(this).closest('.dropdown-submenu');

			var willExpand = !$submenu.hasClass('is-expanded');

			$submenu.siblings('.dropdown-submenu.is-expanded').removeClass('is-expanded')

				.find('[aria-expanded="true"]').attr('aria-expanded', 'false');

			$submenu.toggleClass('is-expanded', willExpand);

			$(this).attr('aria-expanded', willExpand ? 'true' : 'false');

		});



		$topNav.on('click', '.top-nav-main-collapse a[href]:not([href="#"])', function () {

			if (isMobileNav()) {

				closeDrawer();

			}

		});



		$topNav.on('click', '.top-nav-notify-dropdown a[href]', function () {

			if (isMobileNav()) {

				closeNotifyMenu();

			}

		});



		$topNav.on('click', '.header-user-dropdown a[href]', function () {

			if (isMobileNav()) {

				closeUserMenu();

			}

		});



		$(document).on('keydown', function (e) {

			if (e.key === 'Escape' && isMobileNav()) {

				closeAllPanels();

			}

		});

	});

}(jQuery));


