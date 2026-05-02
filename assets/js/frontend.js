(function($) {
    'use strict';

    $(document).ready(function() {
        $('#zteam-see-all').on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $container = $button.closest('.team-members-container');
            var $grid = $container.find('.team-members-grid');
            var imagePosition = $container.data('image-position');

            if ($button.hasClass('loading')) {
                return;
            }

            $button.addClass('loading').text('Loading...');

            $.ajax({
                url: zteam_ajax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'zteam_load_all_members',
                    nonce: zteam_ajax.nonce,
                    image_position: imagePosition
                },
                success: function(response) {
                    if (response) {
                        $grid.html(response);
                        $button.parent().fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading team members:', error);
                    $button.removeClass('loading').text('See All');
                }
            });
        });
    });

})(jQuery);
