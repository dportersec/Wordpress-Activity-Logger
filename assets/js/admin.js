(function ($) {
    'use strict';

    $(function () {
        var mediaFrame;

        $('.securepress-upload-logo').on('click', function (event) {
            event.preventDefault();

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media({
                title: 'Choose Login Logo',
                button: {
                    text: 'Use this logo'
                },
                multiple: false
            });

            mediaFrame.on('select', function () {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $('#securepress_login_logo_url').val(attachment.url).trigger('change');
            });

            mediaFrame.open();
        });
    });
})(jQuery);
