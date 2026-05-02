jQuery(document).ready(function($) {
    var mediaUploader;

    $('#team_member_picture_upload').click(function(e) {
        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Team Member Picture',
            button: {
                text: 'Choose Picture'
            },
            multiple: false
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#team_member_picture').val(attachment.id);
            $('#team_member_picture_preview').attr('src', attachment.url).show();
            $('#team_member_picture_remove').show();
        });

        // Open the uploader dialog
        mediaUploader.open();
    });

    $('#team_member_picture_remove').click(function(e) {
        e.preventDefault();
        $('#team_member_picture').val('');
        $('#team_member_picture_preview').attr('src', '').hide();
        $(this).hide();
    });

    /**
     * Handle Import Data AJAX
     */
    $('#zteam-import-btn').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var $loader = $('.zteam-loader');
        var $messageContainer = $('#zteam-message-container');

        if ($btn.prop('disabled')) return;

        $btn.prop('disabled', true);
        $loader.fadeIn();
        $messageContainer.empty();

        $.ajax({
            url: zteam_admin.ajaxurl,
            type: 'POST',
            data: {
                action: 'team_import_data',
                security: zteam_admin.nonce
            },
            success: function(response) {
                $loader.hide();
                if (response.success) {
                    $messageContainer.html('<div class="updated notice is-dismissible"><p>' + response.data.message + '</p></div>');
                    $btn.find('.dashicons').removeClass('dashicons-cloud-upload').addClass('dashicons-cloud-saved');
                    $btn.find('.button-text').text('ALREADY IMPORTED');
                } else {
                    $messageContainer.html('<div class="error notice is-dismissible"><p>' + response.data.message + '</p></div>');
                    $btn.prop('disabled', false);
                }
            },
            error: function() {
                $loader.hide();
                $messageContainer.html('<div class="error notice is-dismissible"><p>Something went wrong. Please try again.</p></div>');
                $btn.prop('disabled', false);
            }
        });
    });

    /**
     * Handle Remove Data AJAX
     */
    $('#zteam-remove-btn').on('click', function(e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to remove all dummy team members?')) {
            return;
        }
        
        var $btn = $(this);
        var $importBtn = $('#zteam-import-btn');
        var $loader = $('.zteam-loader');
        var $messageContainer = $('#zteam-message-container');

        $btn.prop('disabled', true);
        $loader.fadeIn();
        $messageContainer.empty();

        $.ajax({
            url: zteam_admin.ajaxurl,
            type: 'POST',
            data: {
                action: 'team_remove_data',
                security: zteam_admin.nonce
            },
            success: function(response) {
                $loader.hide();
                $btn.prop('disabled', false);
                if (response.success) {
                    $messageContainer.html('<div class="updated notice is-dismissible"><p>' + response.data.message + '</p></div>');
                    $importBtn.prop('disabled', false);
                    $importBtn.find('.dashicons').removeClass('dashicons-cloud-saved').addClass('dashicons-cloud-upload');
                    $importBtn.find('.button-text').text('IMPORT DATA');
                } else {
                    $messageContainer.html('<div class="error notice is-dismissible"><p>' + response.data.message + '</p></div>');
                }
            },
            error: function() {
                $loader.hide();
                $btn.prop('disabled', false);
                $messageContainer.html('<div class="error notice is-dismissible"><p>Something went wrong. Please try again.</p></div>');
            }
        });
    });
});
