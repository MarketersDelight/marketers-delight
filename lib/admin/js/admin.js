(function (window, document, $) {
    window.MD = {
        init: function () {
            this.clone.init();
            this.sort();
            this.action();
            this.toggle();
            this.tabs();
            this.conditional();
            this.range();
            this.codeEditor();
            this.media();
            this.color();
            this.devices();
        },
        uniqueID: function () {
            var ret = '',
                n = Math.round(new Date().getTime() + (Math.random() * 100)),
                index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            for (var i = Math.floor(Math.log(parseInt(n)) / Math.log(index.length)); i >= 0; i--)
                ret = ret + index.substr((Math.floor(parseInt(n) / Math.floor(Math.pow(parseFloat(index.length), parseInt(i)))) % index.length), 1);
            return ret.split('').reverse().join('');
        },
        integrations: function () {
            $(document).on('click', '.md-integration-button', function (e) {
                e.preventDefault();
                var button = $(this),
                    actionType = button.data('md-integration-action'),
                    integration = button.data('md-integration'),
                    block = '.md-integration.' + integration,
                    loading = $(block + ' .md-loading');
                if (actionType == 'manual_refresh') {
                    $(block).addClass('manual-refresh');
                    $(block + ' .step-1').show();
                    $(block + ' .step-2').hide();
                    return;
                }
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'md_integrations',
                        form: $('#md-form').serialize(),
                        integration: integration,
                        action_type: actionType,
                    },
                    beforeSend: function () {
                        button.prop('disabled', true);
                        loading.css('display', 'inline-block');
                    },
                    success: function (template) {
                        loading.hide();
                        $(block).replaceWith(template);
                        $(block).addClass('open');
                    },
                    error: function () {
                        loading.hide();
                        $(block).addClass('invalid');
                        button.prop('disabled', false);
                    }
                });
            });
        },
        clone: {
            init: function () {
                this.new();
                this.delete();
            },
            new: function () {
                $(document).on('click', '.md-clone-add', function (e) {
                    var group = $(this).data('clone-group'),
                        groupID = $('#md_group_' + group),
                        newID = MD.uniqueID(),
                        empty = groupID.find('.md-group.empty'),
                        clone = empty.clone(true),
                        tags = clone.find('label, input, textarea, select'),
                        attrs = ['for', 'name', 'id'];
                    clone.insertAfter(groupID.find('.md-group').last());
                    clone.find('.md-clone-label').html(newID);
                    tags.each(function () {
                        var tag = $(this);
                        $.each(attrs, function (i, attr) {
                            var val = tag.attr(attr);
                            if (val)
                                tag.attr(attr, val.replace('{clone}', newID));
                        });
                    });
                    clone.addClass('md-clone-new');
                    clone.removeClass('empty');
                    clone.find('.md-populate-date').val(Math.round(new Date().getTime() / 1000));
                    clone.find('.md-populate-user-id').val(MDJS.user_id);
                    clone.show();
                    clone.find('.md-focus').focus();
                });
            },
            delete: function () {
                $(document).on('click', '.md-delete', function (e) {
                    $(this).parents('.md-group').slideUp('fast', function () {
                        $(this).remove();
                    });
                });
            }
        },
        sort: function () {
            var groups = document.getElementsByClassName('md-groups');
            for (var i = 0; i < groups.length; i++)
                new Sortable(groups[i], {
                    handle: '.md-reorder',
                    animation: 150
                });
            var canvas = document.getElementsByClassName('md-widgets-canvas');
            for (var i = 0; i < canvas.length; i++)
                new Sortable(canvas[i], {
                    handle: '.md-reorder',
                    animation: 150,
                    group: 'shared',
                    onAdd: function (e) {
                        var canvas = e.item.parentElement.getAttribute('data-canvas');
                        $(e.item).find('.canvas-status').val(canvas);
                    }
                });
        },
        tabs: function () {
            $(document).on('click', '.md-tab', function (e) {
                e.preventDefault();
                var tab = $(this).data('md-tab'),
                    parent = $(this).closest('.md-tabs');
                parent.find('.md-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                parent.children('.md-tab-content').removeClass('active');
                parent.children('.' + tab).addClass('active');
            });
        },
        conditional: function () {
            $(document).on('change', '.md-conditional-option', function (e) {
                var val = $(this).val(),
                    parent = $(this).parents('.md-conditional');
                parent.find('.md-conditional-item').hide();
                parent.find('.md-conditional-' + val).show();
            });
        },
        toggle: function () {
            $(document).on('click', '.md-widget-title', function () {
                var toggle = $(this);
                if (toggle.parent().hasClass('open'))
                    toggle.parent().removeClass('open');
                else {
                    toggle.parent().parent().find('.md-toggle').removeClass('open');
                    toggle.closest('.md-toggle').toggleClass('open');
                }
                toggle.parent().find('.md-group-color-picker').wpColorPicker();
            });
        },
        action: function () {
            $(document).on('click', '.md-action', function (e) {
                e.preventDefault();
                var trigger = $(this),
                    alert = trigger.data('md-alert');
                if (!confirm(alert))
                    return;
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'md_action',
                        nonce: MDJS.nonce,
                        action_type: trigger.data('md-action'),
                        dropin_id: trigger.data('md-dropin-id')
                    },
                    beforeSend: function () {
                        trigger.find('.dashicons').addClass('md-loading');
                    },
                    success: function (response) {
                        trigger.find('.dashicons').removeClass('md-loading').removeClass('dashicons-update-alt').addClass('dashicons-yes');
                        window.location.reload(true);
                    }
                });
            });
        },
        stickyPostTypes: function () {
            if (MDJS.screen == 'post') {
                if (parseInt(MDJS.is_sticky))
                    $('#post-visibility-display').text('Public, Sticky');
                $('#post-visibility-select label[for="visibility-radio-public"]').next('br').after(
                    '<span id="sticky-span">' +
                    '<input id="sticky" name="sticky" type="checkbox" value="sticky"' + MDJS.checked_attribute + ' /> ' +
                    '<label for="sticky" class="selectit">Stick to the top of stream</label>' +
                    '<br />' +
                    '</span>'
                );
            } else {
                $('span.title:contains(\'Status\')').parent().after(
                    '<label class="alignleft">' +
                    '<input type="checkbox" name="sticky" value="sticky" /> ' +
                    '<span class="checkbox-title">Make this post sticky</span>' +
                    '</label>'
                );
            }
        },
        range: function () {
            $('.md-range-field').on('input change', function () {
                var parent = $(this).parent('.md-range'),
                    number = parent.find('.md-range-number');
                number.val($(this).val());
            });
            $('.md-range-number').on('input change', function () {
                var parent = $(this).parent('.md-range'),
                    range = parent.find('.md-range-field');
                range.val($(this).val());
            });
            $('.md-range-reset').on('click', function () {
                var data = $(this).data('default'),
                    parent = $(this).parent('.md-range');
                number = parent.find('.md-range-number'),
                    range = parent.find('.md-range-field');
                number.val('');
                range.val(data);
            });
        },
        codeEditor: function () {
            $('.md-code-editor textarea').keydown(function (e) {
                if (e.keyCode === 9) {
                    var start = this.selectionStart,
                        end = this.selectionEnd,
                        $this = $(this),
                        value = $this.val();
                    $this.val(value.substring(0, start) + "\t" + value.substring(end));
                    this.selectionStart = this.selectionEnd = start + 1;
                    e.preventDefault();
                }
            });
        },
        color: function () {
            $('.md-color-picker').wpColorPicker();
        },
        fileUpload: function (id, uploadAction) {
            $('#' + id).on('change', function () {
                var upload = $(this),
                    formData = new FormData(),
                    file = upload.prop('files')[0],
                    accept = upload.attr('accept'),
                    parent = upload.parents('.md-file-upload');
                formData.append('action', 'md_file');
                formData.append('upload_action', uploadAction);
                formData.append('accept', accept);
                formData.append('nonce', MDJS.nonce);
                formData.append('file', file);
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        parent.find('.md-loading').css('display', 'inline-block');
                    },
                    success: function (response) {
                        parent.find('.md-loading').hide();
                        parent.find('.md-file-upload-success').fadeIn().delay(3000).fadeOut();
                        window.location.reload(true);
                    }
                });
            });
        },
        media: function () {
            $(document).on('click', '.md-upload-add', function () {
                var parent = $(this).parents('.md-upload');
                media = wp.media.frames.file_frame = wp.media({
                    frame: 'select',
                    multiple: false,
                    library: {type: 'image'}
                });
                media.on('select', function () {
                    var selection = media.state().get('selection');
                    if (selection) {
                        selection.each(function (upload) {
                            parent.find('.md-upload-url').val(upload.attributes.sizes.full.url);
                            parent.find('.md-upload-id').val(upload.attributes.id);
                            parent.find('.md-upload-preview-image img').attr('src', upload.attributes.sizes.full.url);
                            parent.addClass('has-upload');
                        });
                    }
                });
                media.open();
            });
            $('.md-upload-remove').on('click', function () {
                var parent = $(this).parents('.md-upload');
                parent.removeClass('has-upload');
                parent.find('.md-upload-url').val('');
                parent.find('.md-upload-id').val('');
                parent.find('.md-upload-preview-image img').attr('src', '');
            });
        },
        devices: function () {
            $('.md-device').on('click', function (e) {
                var device = $(this).attr('id'),
                    wrap = $(this).parents('.md.wrap');
                $('.md-device').removeClass('active');
                $(this).addClass('active');
                wrap.removeClass('desktop tablet mobile');
                wrap.addClass(device);
            });
        },
        blocksEditor: function () {
            $('#marketers_delight_layout_sidebar_add, #marketers_delight_layout_sidebar_remove').on('change', function () {
                $('body').toggleClass('md-editor-full');
                $('#post-title-0').focus().blur();
            });
        }
    };
    MD.init();
})(window, document, jQuery);