(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType,
        el = wp.element.createElement,
        __ = wp.i18n.__;
    PanelBody = wp.components.PanelBody,
        PanelColorSettings = wp.editor.PanelColorSettings,
        InspectorControls = wp.blockEditor.InspectorControls,
        BlockControls = wp.blockEditor.BlockControls,
        AlignmentToolbar = wp.editor.AlignmentToolbar,
        getColorClassName = wp.editor.getColorClassName,
        MediaUpload = wp.editor.MediaUpload,
        TextControl = wp.components.TextControl,
        SelectControl = wp.components.SelectControl,
        CheckboxControl = wp.components.CheckboxControl,
        wpButton = wp.components.Button,
        mdColors = MDBlocks.colors,
        mdEmail = MDBlocks.email;
    registerBlockType('marketers-delight/email', {
        title: __('Email Form'),
        description: __('Display an inline email signup form.'),
        icon: 'email-alt',
        category: 'marketers-delight',
        supports: {
            align: true
        },
        attributes: {
            title: {type: 'string'},
            text: {type: 'string'},
            footerText: {type: 'string'},
            namePlaceholder: {type: 'string'},
            emailPlaceholder: {type: 'string'},
            submitText: {type: 'string'},
            bgColor: {type: 'string'},
            bgColorClass: {type: 'string'},
            textColor: {type: 'string'},
            textColorClass: {type: 'string'},
            buttonColor: {type: 'string'},
            buttonColorClass: {type: 'string'},
            buttonTextColor: {type: 'string'},
            buttonTextColorClass: {type: 'string'},
            mediaURL: {type: 'string'},
            alignment: {type: 'string'},
            emailList: {type: 'string'},
            thankYou: {type: 'string'},
            name: {type: 'boolean'},
            marginBottom: {type: 'string'},
            padding: {type: 'string'},
            attached: {type: 'boolean'},
            shadow: {type: 'boolean'}
        },
        edit: function (props) {
            var attributes = props.attributes,
                bgColor = attributes.bgColor,
                bgColorClass = attributes.bgColorClass,
                textColor = attributes.textColor,
                textColorClass = attributes.textColorClass,
                buttonColor = attributes.buttonColor,
                buttonColorClass = attributes.buttonColorClass,
                buttonTextColor = attributes.buttonTextColor,
                buttonTextColorClass = attributes.buttonTextColorClass,
                alignment = attributes.alignment,
                shadow = attributes.shadow,
                marginBottom = attributes.marginBottom,
                padding = attributes.padding,
                title = attributes.title,
                text = attributes.text,
                namePlaceholder = attributes.namePlaceholder ? attributes.namePlaceholder : __('Enter your name..'),
                emailPlaceholder = attributes.emailPlaceholder ? attributes.emailPlaceholder : __('Enter your email..'),
                footerText = attributes.footerText,
                submitText = attributes.submitText ? attributes.submitText : __('Join now!'),
                attached = attributes.attached,
                mediaURL = attributes.mediaURL,
                name = attributes.name,
                email = mdEmail ? mdEmail : [];

            function mdGetColor(property, val) {
                var className = getColorClassName(property, mdColors.hex[val]),
                    saved = className ? className : '';
                return className ? className : saved;
            }

            function mdShowColor(property, val) {
                return ' ' + mdGetColor(property, val);
            }

            return [
                el(BlockControls, null,
                    el(AlignmentToolbar, {
                        value: alignment,
                        onChange: function (val) {
                            props.setAttributes({alignment: val});
                        }
                    })
                ),
                el(
                    InspectorControls, {
                        key: 'inspector'
                    },
                    el(
                        PanelBody, {
                            title: __('Text'),
                            initialOpen: true
                        },
                        el(SelectControl, {
                            label: __('Email List'),
                            options: [{label: __('Use default email list...'), value: ''}].concat(email),
                            value: attributes.emailList,
                            onChange: function (val) {
                                props.setAttributes({emailList: val});
                            },
                        }),
                        el(TextControl, {
                            label: __('Thank You Page URL'),
                            value: attributes.thankYou,
                            onChange: function (val) {
                                props.setAttributes({thankYou: val});
                            },
                        }),
                        el(CheckboxControl, {
                            label: __('Ask for name'),
                            checked: name,
                            onChange: function (val) {
                                props.setAttributes({name: val});
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Title'),
                            value: title,
                            onChange: function (val) {
                                props.setAttributes({title: val});
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Text'),
                            value: attributes.text,
                            onChange: function (val) {
                                props.setAttributes({text: val});
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Name Field'),
                            value: namePlaceholder,
                            onChange: function (val) {
                                props.setAttributes({
                                    namePlaceholder: val
                                });
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Email Field'),
                            value: emailPlaceholder,
                            onChange: function (val) {
                                props.setAttributes({emailPlaceholder: val});
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Submit Text'),
                            value: submitText,
                            onChange: function (val) {
                                props.setAttributes({submitText: val});
                            }
                        }),
                        el(TextControl, {
                            type: 'text',
                            label: __('Footer Text'),
                            value: footerText,
                            onChange: function (val) {
                                props.setAttributes({footerText: val});
                            }
                        })
                    ),
                    el(PanelColorSettings, {
                        initialOpen: true,
                        title: __('Colors'),
                        colorSettings: [
                            {
                                value: bgColor,
                                label: __('Background Color'),
                                onChange: function (val) {
                                    props.setAttributes({
                                        bgColor: val,
                                        bgColorClass: val ? mdGetColor('background-color', val) : ''
                                    });
                                }
                            },
                            {
                                value: textColor,
                                label: __('Text Color'),
                                onChange: function (val) {
                                    props.setAttributes({
                                        textColor: val,
                                        textColorClass: val ? mdGetColor('color', val) : ''
                                    });
                                }
                            },
                            {
                                value: buttonColor,
                                label: __('Button Color'),
                                onChange: function (val) {
                                    props.setAttributes({
                                        buttonColor: val,
                                        buttonColorClass: val ? mdGetColor('background-color', val) : ''
                                    });
                                }
                            },
                            {
                                value: buttonTextColor,
                                label: __('Button Text Color'),
                                onChange: function (val) {
                                    props.setAttributes({
                                        buttonTextColor: val,
                                        buttonTextColorClass: val ? mdGetColor('color', val) : ''
                                    });
                                }
                            },
                        ]
                    }),
                    el(
                        PanelBody, {
                            title: __('Design'),
                        },
                        el(MediaUpload, {
                            type: 'image',
                            value: mediaURL,
                            onSelect: function (val) {
                                props.setAttributes({
                                    mediaURL: val.url
                                });
                            },
                            render: function (obj) {
                                return [
                                    el(
                                        wpButton, {
                                            className: 'components-icon-button image-block-btn is-button is-default mb-half is-large',
                                            onClick: obj.open
                                        },
                                        el('i', {
                                            className: 'dashicons dashicons-format-image',
                                            style: {marginRight: '4px'}
                                        }),
                                        el('span', null, __('Select background image'))
                                    ),
                                    (mediaURL ?
                                        el(
                                            wpButton, {
                                                className: 'components-icon-button image-block-btn is-button is-default is-large mt-half mb-half',
                                                onClick: function (val) {
                                                    props.setAttributes({mediaURL: ''});
                                                }
                                            },
                                            el('i', {
                                                className: 'dashicons dashicons-no-alt',
                                                style: {marginRight: '4px'}
                                            }),
                                            el('span', null, __('Remove image'))
                                        )
                                        : '')
                                ];
                            }
                        }),
                        el(SelectControl, {
                            label: __('Margin Bottom'),
                            options: [
                                {label: __('Select margin...'), value: ''},
                                {label: __('None'), value: 'mb-none'},
                                {label: __('Half'), value: 'mb-half'},
                                {label: __('Single'), value: 'mb-single'},
                                {label: __('Mid (default)'), value: 'mb-mid'},
                                {label: __('Double'), value: 'mb-double'},
                                {label: __('Triple'), value: 'mb-triple'},
                            ],
                            value: marginBottom,
                            onChange: function (val) {
                                props.setAttributes({marginBottom: val});
                            }
                        }),
                        el(SelectControl, {
                            label: __('Padding'),
                            options: [
                                {label: __('Select padding...'), value: ''},
                                {label: __('None'), value: 'mb-none'},
                                {label: __('Half'), value: 'block-half'},
                                {label: __('Single (default)'), value: 'block-single'},
                                {label: __('Mid'), value: 'block-mid'},
                                {label: __('Double'), value: 'block-double'},
                                {label: __('Triple'), value: 'block-triple'},
                            ],
                            value: padding,
                            onChange: function (val) {
                                props.setAttributes({padding: val});
                            }
                        }),
                        el(CheckboxControl, {
                            label: __('Attach input fields'),
                            checked: attached,
                            onChange: function (val) {
                                props.setAttributes({attached: val});
                            }
                        }),
                        el(CheckboxControl, {
                            label: __('Add shadow'),
                            checked: shadow,
                            onChange: function (val) {
                                props.setAttributes({shadow: val});
                            }
                        })
                    )
                ),
                el(
                    'div', {
                        className: props.className + (shadow ? ' has-shadow' : '') + (' ' + bgColorClass) + (' ' + textColorClass) + (mediaURL ? ' image-overlay' : ''),
                        style: {
                            backgroundImage: (mediaURL ? 'url(' + mediaURL + ')' : ''),
                            backgroundColor: (!bgColorClass ? bgColor : ''),
                            color: (!textColorClass ? textColor : '')
                        }
                    },
                    el(
                        'div', {
                            className: 'md-email md-clear ' + marginBottom,
                            style: {
                                textAlign: alignment
                            }
                        },
                        el(
                            'div', {
                                className: 'md-email-content md-clear ' + (bgColor || shadow ? ' ' + (padding ? padding : 'block-single') : '')
                            },
                            title ?
                                el('div', {
                                        className: 'mb-half'
                                    },
                                    el(RichText, {
                                        key: 'editable',
                                        tagName: 'p',
                                        className: 'med-title',
                                        value: title,
                                        placeholder: __('Enter title here...'),
                                        onChange: function (val) {
                                            props.setAttributes({title: val});
                                        }
                                    })
                                )
                                : '',
                            text ?
                                el('div', {
                                        className: 'mb-single'
                                    },
                                    el(RichText, {
                                        key: 'editable',
                                        tagName: 'p',
                                        value: text,
                                        placeholder: __('Enter text here...'),
                                        onChange: function (val) {
                                            props.setAttributes({text: val});
                                        }
                                    })
                                )
                                : '',
                            el(
                                'form', {
                                    className: 'md-email-form ' + (attached ? (name ? 'md-email-attached-2' : 'md-email-attached') : 'md-email-full')
                                },
                                el('input', {
                                    className: 'md-input md-input-name',
                                    placeholder: namePlaceholder,
                                    disabled: true,
                                    style: {
                                        display: (name ? 'block' : 'none')
                                    }
                                }),
                                el('input', {
                                    className: 'md-input md-input-email',
                                    disabled: true,
                                    placeholder: emailPlaceholder
                                }),
                                el(
                                    'button', {
                                        className: 'md-submit mb-half' + mdShowColor('background-color', buttonColor) + mdShowColor('color', buttonTextColor),
                                        disabled: true,
                                        style: {
                                            backgroundColor: (!buttonColorClass ? buttonColor : ''),
                                            color: (!buttonTextColorClass ? buttonTextColor : '')
                                        }
                                    }, submitText
                                ),
                                (footerText ? el('p', {className: 'md-email-footer'}, footerText) : '')
                            )
                        )
                    )
                )
            ]
        },
        save: function () {
            return null;
        }
    });

})(wp);