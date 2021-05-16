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
		RichText = wp.blockEditor.RichText,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		CheckboxControl = wp.components.CheckboxControl,
		wpButton = wp.components.Button,
		mdColors = MDBlocks.colors,
		mdIcons = MDBlocks.icons,
		mdPopups = MDBlocks.popups;
	registerBlockType('marketers-delight/callout', {
		title: __('Callout'),
		description: __('Create a helpful callout with icon, bullet list, and text.'),
		icon: 'megaphone',
		category: 'marketers-delight',
		supports: {
			align: true
		},
		attributes: {
			title: {type: 'string'},
			text: {type: 'string'},
			list: {type: 'string'},
			icon: {type: 'string'},
			iconImage: {type: 'string'},
			url: {type: 'string'},
			buttonText: {type: 'string'},
			bgColor: {type: 'string'},
			bgColorClass: {type: 'string'},
			borderColor: {type: 'string'},
			iconColor: {type: 'string'},
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
			shadow: {type: 'boolean'}
		},
		edit: function (props) {
			var attributes = props.attributes,
				title = attributes.title,
				text = attributes.text,
				list = attributes.list,
				url = attributes.url,
				buttonText = attributes.buttonText,
				icon = attributes.icon,
				iconImage = attributes.iconImage,
				popup = attributes.popup,
				bgColor = attributes.bgColor,
				bgColorClass = attributes.bgColorClass,
				borderColor = attributes.borderColor,
				iconColor = attributes.iconColor ? attributes.iconColor : mdColors.slug.text,
				textColor = attributes.textColor,
				textColorClass = attributes.textColorClass,
				buttonColor = attributes.buttonColor,
				buttonColorClass = !bgColor ? attributes.buttonColorClass : '',
				buttonTextColor = attributes.buttonTextColor,
				buttonTextColorClass = attributes.buttonTextColorClass,
				bgImage = attributes.bgImage,
				alignment = attributes.alignment,
				shadow = attributes.shadow,
				marginBottom = attributes.marginBottom ? attributes.marginBottom : 'mb-double';
			padding = attributes.padding ? attributes.padding : 'block-mid',
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
						(!icon ?
							el(MediaUpload, {
								type: 'image',
								value: iconImage,
								onSelect: function (val) {
									props.setAttributes({
										iconImage: val.url
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
											el('span', null, __('Select icon image'))
										),
										(iconImage ?
											[
												el(
													wpButton, {
														className: 'components-icon-button image-block-btn is-button is-default is-large mt-half mb-half',
														onClick: function (val) {
															props.setAttributes({iconImage: ''});
														}
													},
													el('i', {
														className: 'dashicons dashicons-no-alt',
														style: {marginRight: '4px'}
													}),
													el('span', null, __('Remove image')),
												),
												el('p', {fontStyle: 'italic'}, __('Recommended size: 100x100'))
											]
											: '')
									];
								}
							})
							: ''),
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
								value: borderColor,
								label: __('Border Color'),
								onChange: function (val) {
									props.setAttributes({
										borderColor: val ? val : 'rgba(0, 0, 0, 0.1)'
									});
								}
							},
							{
								value: iconColor,
								label: __('Icon Color'),
								onChange: function (val) {
									props.setAttributes({
										iconColor: val
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
								{label: __('Mid'), value: 'mb-mid'},
								{label: __('Double (default)'), value: 'mb-double'},
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
						className: props.className + ' md-callout' + ' ' + marginBottom + ' ' + padding + (shadow ? ' has-shadow' : '') + ' ' + (bgColorClass ? bgColorClass : 'has-accent-background-color') + (' ' + textColorClass) + (bgImage ? ' image-overlay' : '') + (icon || iconImage ? ' has-icon' : '') + ' mt-double',
						style: {
							backgroundImage: (bgImage ? 'url(' + bgImage + ')' : ''),
							border: '4px solid ' + borderColor,
							backgroundColor: (!bgColorClass ? bgColor : ''),
							color: (!textColorClass ? textColor : ''),
							textAlign: alignment
						}
					},
					(icon || iconImage ?
						el(
							'div', {
								className: 'md-callout-icon mb-single' + (icon ? ' icon' : '') + (iconImage && !icon ? ' image' : ''),
								style: {
									backgroundColor: iconColor,
								}
							},
							(icon ? el('i', {className: icon}) : (iconImage ? el('img', {src: iconImage}) : ''))
						)
						: ''),
					el(
						'div', {
							className: 'md-callout-title mb-single'
						},
						el(RichText, {
							key: 'editable',
							tagName: 'p',
							className: 'med-title',
							value: title,
							placeholder: __('Enter Title Here...'),
							onChange: function (val) {
								props.setAttributes({title: val});
							},
						}),
					),
					el(
						'div', {
							className: 'md-callout-text mb-single'
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
					),
					el(
						'div', {
							className: 'md-callout-list'
						},
						el(RichText, {
							key: 'editable',
							tagName: 'ul',
							className: 'md-list',
							multiline: 'li',
							value: list,
							placeholder: __('Enter list item here...'),
							onChange: function (val) {
								props.setAttributes({list: val});
							}
						})
					),
					buttonText ?
						el(
							'div', {
								className: 'md-callout-action'
							},
							el(
								'button', {
									className: 'md-button md-button-arrow ' + (buttonColorClass ? buttonColorClass : '') + ' ' + buttonTextColorClass,
									disabled: true,
									style: {
										backgroundColor: (!buttonColorClass ? buttonColor : ''),
										color: (!buttonTextColorClass ? buttonTextColor : '')
									}
								}, buttonText
							)
						)
						: ''
				)
			]
		},
		save: function (props) {
			return null;
		}
	});
})(wp);
