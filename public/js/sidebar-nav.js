/**
 * Sidebar Navigation Active State Management
 * Handles active states for dropdown menus and their children
 */

(function($) {
    'use strict';

    // Function to set active states based on current URL
    function setActiveNavigation() {
        var currentUrl = window.location.href;
        var currentPath = window.location.pathname;

        // Remove all existing active classes first
        $('.navbar-nav .nav-item').removeClass('active');
        $('.navbar-nav .nav-link').removeClass('active');
        $('.navbar-nav .dropdown-toggle').removeClass('active');

        // Find the matching nav link
        $('.navbar-nav a.nav-link').each(function() {
            var linkHref = $(this).attr('href');

            // Skip if no href or it's a collapse toggle
            if (!linkHref || $(this).attr('data-toggle') === 'collapse') {
                return;
            }

            // Check if current page matches this link
            if (currentUrl === linkHref || currentPath === new URL(linkHref, window.location.origin).pathname) {
                // Add active class to the link and its parent nav-item
                $(this).addClass('active');
                $(this).closest('.nav-item').addClass('active');

                // If this is inside a collapsible menu, open it and mark parent as active
                var $collapseParent = $(this).closest('.collapse');
                if ($collapseParent.length) {
                    // Show the collapse menu
                    $collapseParent.addClass('show');

                    // Find and mark the dropdown toggle as active
                    var collapseId = $collapseParent.attr('id');
                    var $dropdownToggle = $('a[href="#' + collapseId + '"][data-toggle="collapse"]');

                    if ($dropdownToggle.length) {
                        $dropdownToggle.addClass('active');
                        $dropdownToggle.attr('aria-expanded', 'true');
                        $dropdownToggle.closest('.nav-item').addClass('active');
                    }
                }

                // Found a match, no need to continue
                return false;
            }
        });
    }

    // Function to handle collapse state persistence
    function initCollapsePersistence() {
        // When a collapse is shown, save its state
        $('.collapse').on('show.bs.collapse', function() {
            var collapseId = $(this).attr('id');
            if (collapseId) {
                localStorage.setItem('sidebar_collapse_' + collapseId, 'open');
            }
        });

        // When a collapse is hidden, remove its state
        $('.collapse').on('hide.bs.collapse', function() {
            var collapseId = $(this).attr('id');
            if (collapseId) {
                localStorage.removeItem('sidebar_collapse_' + collapseId);
            }
        });
    }

    // Function to restore collapse states from localStorage
    function restoreCollapseStates() {
        $('.collapse').each(function() {
            var collapseId = $(this).attr('id');
            if (collapseId) {
                var state = localStorage.getItem('sidebar_collapse_' + collapseId);
                if (state === 'open') {
                    $(this).addClass('show');
                    $('a[href="#' + collapseId + '"][data-toggle="collapse"]').attr('aria-expanded', 'true');
                }
            }
        });
    }

    // Function to prevent collapse panels from closing when clicking child links
    function preventCollapseOnChildClick() {
        $('.collapse .nav-link').on('click', function(e) {
            // Don't prevent default - we want the link to work
            // Just stop propagation so parent collapse doesn't toggle
            e.stopPropagation();
        });
    }

    // Enhanced collapse toggle behavior
    function enhanceCollapseToggle() {
        // Handle dropdown toggle clicks
        $('a[data-toggle="collapse"]').on('click', function(e) {
            e.preventDefault();

            var targetId = $(this).attr('href');
            var $target = $(targetId);

            // Close other dropdowns that are not parents of this one
            $('.collapse.show').each(function() {
                if ($(this).attr('id') !== targetId.substring(1)) {
                    $(this).collapse('hide');
                }
            });

            // Toggle this dropdown
            $target.collapse('toggle');
        });
    }

    // Enhanced sidebar toggle functionality
    function enhanceSidebarToggle() {
        // Ensure toggle works on both navigation bar and sidebar
        $('.collapseSidebar').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $wrapper = $('.wrapper');
            var $vertical = $('.vertical');
            var $sidebar = $('#leftSidebar');

            // For narrow screens (mobile)
            if ($vertical.hasClass('narrow')) {
                $vertical.toggleClass('open');
                $sidebar.toggleClass('show');
            } else {
                // For desktop screens
                $vertical.toggleClass('collapsed');

                // Remove hover class if present
                if ($vertical.hasClass('hover')) {
                    $vertical.removeClass('hover');
                }
            }

            // Save state to localStorage
            if ($vertical.hasClass('collapsed')) {
                localStorage.setItem('sidebar_state', 'collapsed');
            } else {
                localStorage.setItem('sidebar_state', 'expanded');
            }
        });

        // Restore sidebar state on load
        var savedState = localStorage.getItem('sidebar_state');
        if (savedState === 'collapsed' && !$('.vertical').hasClass('narrow')) {
            $('.vertical').addClass('collapsed');
        }
    }

    // Close sidebar when clicking outside on mobile
    function initMobileSidebarClose() {
        $(document).on('click', function(e) {
            var $sidebar = $('#leftSidebar');
            var $toggle = $('.collapseSidebar');
            var $vertical = $('.vertical');

            // Only on mobile (narrow) screens
            if ($vertical.hasClass('narrow') && $vertical.hasClass('open')) {
                // If click is outside sidebar and toggle button
                if (!$sidebar.is(e.target) && $sidebar.has(e.target).length === 0 &&
                    !$toggle.is(e.target) && $toggle.has(e.target).length === 0) {
                    $vertical.removeClass('open');
                    $sidebar.removeClass('show');
                }
            }
        });
    }

    // Initialize everything when document is ready
    $(document).ready(function() {
        // Enhance sidebar toggle first
        enhanceSidebarToggle();

        // Initialize mobile sidebar close
        initMobileSidebarClose();

        // Restore saved collapse states
        restoreCollapseStates();

        // Then set active navigation (which might override some states)
        setActiveNavigation();

        // Initialize persistence
        initCollapsePersistence();

        // Prevent collapse on child clicks
        preventCollapseOnChildClick();

        // Enhance collapse toggle
        enhanceCollapseToggle();
    });

})(jQuery);

