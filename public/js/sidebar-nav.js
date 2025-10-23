/**
 * Simple Sidebar Toggle & State Persistence
 * Handles open/close of sidebar, and remembers state in localStorage
 */
(function($) {
    'use strict';

    function enhanceSidebarToggle() {
        $('.collapseSidebar').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $vertical = $('.vertical');
            var $sidebar = $('#leftSidebar');

            // For mobile
            if ($vertical.hasClass('narrow')) {
                $vertical.toggleClass('open');
                $sidebar.toggleClass('show');
            } else {
                // For desktop
                $vertical.toggleClass('collapsed');
                // Save state for desktop
                if ($vertical.hasClass('collapsed')) {
                    localStorage.setItem('sidebar_state', 'collapsed');
                } else {
                    localStorage.setItem('sidebar_state', 'expanded');
                }
            }
        });

        // Restore sidebar state on desktop
        var savedState = localStorage.getItem('sidebar_state');
        if (savedState === 'collapsed' && !$('.vertical').hasClass('narrow')) {
            $('.vertical').addClass('collapsed');
        }
        // Always reset sidebar on mobile load
        if ($('.vertical').hasClass('narrow')) {
            $('.vertical').removeClass('open');
            $('#leftSidebar').removeClass('show');
        }
    }

    $(document).ready(function() {
        enhanceSidebarToggle();
    });
})(jQuery);
