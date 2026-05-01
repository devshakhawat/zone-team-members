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
});
