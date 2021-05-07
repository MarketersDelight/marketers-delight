(function ($) {

    /**
     * Redirect Preview on Popups focus.
     *
     * @since 5.0
     */

    wp.customize.bind('preview-ready', function () {
        wp.customize.preview.bind('md-popups-edit', function (data) {
            if (data.popupId)
                wp.customize.preview.send('url', mdPopups.popupsDesigner + '&id=' + data.popupId);
        });
        wp.customize.preview.bind('md-popups-close', function (data) {
            wp.customize.preview.send('url', data.home_url);
        });
    });

    /**
     * Run preview actions on control updates.
     *
     * @since 5.0
     */

    $.each(mdPopups.popups, function (c, id) {

        // Text

        wp.customize('marketers_delight[popups_data][' + id + '][content][headline]', function (input) {
            input.bind(function (val) {
                $('.popup-title').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][content][subtitle]', function (input) {
            input.bind(function (val) {
                $('.popup-subtitle').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][content][description]', function (input) {
            input.bind(function (val) {
                $('.popup-text').html(val);
            });
        });

        // Featured Image

        wp.customize('marketers_delight[popups_data][' + id + '][featured_image][alignment]', function (input) {
            input.bind(function (val) {
                var defaultAlign = $('.popup-image').data('default-align');

                $('.md-popup').removeClass(function (index, className) {
                    return (className.match(/(^|\s)image-\S+/g) || []).join(' ');
                });
                if (val !== '')
                    $('.md-popup').addClass('image-' + val);
                else
                    $('.md-popup').addClass('image-' + defaultAlign);

                $('.popup-image').removeClass(function (index, className) {
                    return (className.match(/(^|\s)align\S+/g) || []).join(' ');
                });
                if (val !== '')
                    $('.popup-image').addClass(val);
                else
                    $('.popup-image').addClass(defaultAlign);

            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][featured_image][width]', function (input) {
            input.bind(function (val) {
                $('.popup-image').width(val + '%');
            });
        });

        // Buttons

        wp.customize('marketers_delight[popups_data][' + id + '][button][text]', function (input) {
            input.bind(function (val) {
                $('.button-main .button-text').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][button][subtext]', function (input) {
            input.bind(function (val) {
                $('.button-main .button-subtext').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][button_sec][text]', function (input) {
            input.bind(function (val) {
                $('.button-sec .button-text').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][button_sec][subtext]', function (input) {
            input.bind(function (val) {
                $('.button-sec .button-subtext').html(val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][button][footer_text]', function (input) {
            input.bind(function (val) {
                $('.popup-buttons-text').html(val);
            });
        });

        // Email

        wp.customize('marketers_delight[popups_data][' + id + '][email][email_name_label]', function (input) {
            input.bind(function (val) {
                if (val !== '')
                    $('.email-form .form-input-name').attr('placeholder', val);
                else
                    $('.email-form .form-input-name').attr('placeholder', 'Enter your name...');
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][email][email_email_label]', function (input) {
            input.bind(function (val) {
                if (val !== '')
                    $('.email-form .form-input-email').attr('placeholder', val);
                else
                    $('.email-form .form-input-email').attr('placeholder', 'Enter your email...');
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][email][email_submit_label]', function (input) {
            input.bind(function (val) {
                if (val !== '')
                    $('.email-form .form-submit').html(val);
                else
                    $('.email-form .form-submit').html('Join now!');
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][email][email_footer]', function (input) {
            input.bind(function (val) {
                $('.email-form-footer').html(val);
            });
        });

        // Design

        wp.customize('marketers_delight[popups_data][' + id + '][design][bg_color]', function (input) {
            input.bind(function (val) {
                $('.md-popup').css('background-color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][text_color]', function (input) {
            input.bind(function (val) {
                $('.popup-text, .popup-subtitle').css('color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][secondary_color]', function (input) {
            input.bind(function (val) {
                $('.popup-secondary-bg-color').css('background-color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][headline_color]', function (input) {
            input.bind(function (val) {
                $('.popup-title').css('color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][border_color]', function (input) {
            input.bind(function (val) {
                if (val !== '') {
                    $('.md-popup').addClass('popup-border');
                    $('.popup-border').css('border', '6px solid ' + val);
                } else {
                    $('.popup-border').css('border', '');
                    $('.md-popup').removeClass('popup-border');
                }
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][close_color]', function (input) {
            input.bind(function (val) {
                $('.md-popup-close-corner').css('color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][button_color]', function (input) {
            input.bind(function (val) {
                $('.button-main').css('background-color', val);
                $('.email-form-submit').css('background-color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][button_text_color]', function (input) {
            input.bind(function (val) {
                $('.button-main').css('color', val);
                $('.email-form-submit').css('color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][button_sec_color]', function (input) {
            input.bind(function (val) {
                $('.button-sec').css('background-color', val);
            });
        });

        wp.customize('marketers_delight[popups_data][' + id + '][design][button_sec_text_color]', function (input) {
            input.bind(function (val) {
                $('.button-sec').css('color', val);
            });
        });

        // Custom Template

        wp.customize('marketers_delight[popups_data][' + id + '][custom_template][custom_template]', function (input) {
            input.bind(function (val) {
                $('.popup-custom .popup-inner').html(val);
            });
        });

        // Custom CSS

        wp.customize('marketers_delight[popups_data][' + id + '][custom_template][custom_css]', function (input) {
            input.bind(function (val) {
                $('#md_popup_custom_css').text(val);
            });
        });

    });

})(jQuery);