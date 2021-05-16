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
		mdIcons = MDBlocks.icons,
		mdPopups = MDBlocks.popups,
		registerBlockType('marketers-delight/content-upgrade', {
			title: __('Content Upgrade'),
			description: __('Display an offer box in your content with a link or popup offer.'),
			icon: 'awards',
			category: 'marketers-delight',
			supports: {
				align: true
			},
			attributes: {
				title: {type: 'string'},
				text: {type: 'string'},
				url: {type: 'string'},
				icon: {type: 'string'},
				buttonText: {type: 'string'},
				bgColor: {type: 'string'},
				bgColorClass: {type: 'string'},
				textColor: {type: 'string'},
				textColorClass: {type: 'string'},
				buttonColor: {type: 'string'},
				buttonColorClass: {type: 'string'},
				buttonTextColor: {type: 'string'},
				buttonTextColorClass: {type: 'string'},
				bgImage: {type: 'string'},
				align: {type: 'string'},
				alignment: {type: 'string'},
				popup: {type: 'string'},
				marginBottom: {type: 'string'},
				padding: {type: 'string'},
				shadow: {type: 'boolean'},
				boxLeftRight: {type: 'boolean'}
			},
			edit: function (props) {
				var attributes = props.attributes,
					title = attributes.title,
					text = attributes.text,
					url = attributes.url,
					icon = attributes.icon,
					buttonText = attributes.buttonText ? attributes.buttonText : __('Download now'),
					popup = attributes.popup,
					bgColor = attributes.bgColor,
					bgColorClass = attributes.bgColorClass,
					textColor = attributes.textColor,
					textColorClass = attributes.textColorClass,
					buttonColor = attributes.buttonColor,
					buttonColorClass = attributes.buttonColorClass,
					buttonTextColor = attributes.buttonTextColor,
					buttonTextColorClass = attributes.buttonTextColorClass,
					bgImage = attributes.bgImage,
					alignment = attributes.alignment,
					shadow = attributes.shadow,
					boxLeftRight = attributes.boxLeftRight,
					marginBottom = attributes.marginBottom ? attributes.marginBottom : 'mb-mid';
				padding = attributes.padding ? attributes.padding : 'block-single',
					popups = mdPopups ? mdPopups : [];

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
								title: __('Settings')
							},
							el(SelectControl, {
								label: __('Icon'),
								options: [{label: __('Select an icon...'), value: ''}].concat(mdIcons),
								value: icon,
								onChange: function (val) {
									props.setAttributes({icon: val});
								},
							}),
							el(TextControl, {
								type: 'text',
								label: __('Button Text'),
								value: buttonText,
								onChange: function (val) {
									props.setAttributes({buttonText: val});
								}
							}),
							el(TextControl, {
								type: 'text',
								label: __('Button URL'),
								value: url,
								onChange: function (val) {
									props.setAttributes({url: val});
								}
							}),
							el(SelectControl, {
								label: __('Popup'),
								options: [{label: __('Select a popup...'), value: ''}].concat(popups),
								value: popup,
								onChange: function (val) {
									props.setAttributes({popup: val});
								},
							})
						),
						el(PanelColorSettings, {
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
								value: bgImage,
								onSelect: function (val) {
									props.setAttributes({
										bgImage: val.url
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
										(bgImage ?
											el(
												wpButton, {
													className: 'components-icon-button image-block-btn is-button is-default is-large mt-half mb-half',
													onClick: function (val) {
														props.setAttributes({bgImage: ''});
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
								label: __('Align text left/right'),
								checked: boxLeftRight,
								onChange: function (val) {
									props.setAttributes({boxLeftRight: val});
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
							className: props.className + ' md-content-upgrade' + ' ' + marginBottom + ' ' + padding + (boxLeftRight ? ' box-lr' : '') + (shadow ? ' has-shadow' : '') + (bgColorClass ? ' ' + bgColorClass : ' has-secondary-background-color') + (textColorClass ? ' ' + textColorClass : ' has-white-color') + (bgImage ? ' image-overlay' : ''),
							style: {
								backgroundImage: (bgImage ? 'url(' + bgImage + ')' : ''),
								backgroundColor: (!bgColorClass ? bgColor : ''),
								color: (!textColorClass ? textColor : ''),
								textAlign: alignment
							}
						},
						(icon ?
							el(
								'div', {
									className: 'md-content-upgrade-icon large-title mr-single'
								},
								el('i', {className: icon})
							)
							: ''),
						el(
							'div', {
								className: 'md-content-upgrade-text mb-single'
							},
							el(RichText, {
								key: 'editable',
								tagName: 'p',
								className: 'small-title mb-small',
								value: title,
								placeholder: __('Enter Title Here'),
								onChange: function (val) {
									props.setAttributes({title: val});
								}
							}),
							el(RichText, {
								key: 'editable',
								tagName: 'p',
								value: text,
								placeholder: __('Enter description text (optional)'),
								onChange: function (val) {
									props.setAttributes({text: val});
								}
							})
						),
						el(
							'div', {
								className: 'md-content-upgrade-action'
							},
							el(
								'button', {
									className: 'md-button md-button-arrow ' + (buttonColorClass ? buttonColorClass : 'has-button-sec-background-color') + ' ' + buttonTextColorClass,
									disabled: true,
									style: {
										backgroundColor: (!buttonColorClass ? buttonColor : ''),
										color: (!buttonTextColorClass ? buttonTextColor : '')
									}
								}, buttonText
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
