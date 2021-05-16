(function (wp) {
	var registerBlockType = wp.blocks.registerBlockType,
		el = wp.element.createElement,
		__ = wp.i18n.__;
	PanelBody = wp.components.PanelBody,
		InspectorControls = wp.blockEditor.InspectorControls,
		BlockControls = wp.blockEditor.BlockControls,
		getColorClassName = wp.editor.getColorClassName,
		MediaUpload = wp.editor.MediaUpload,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		wpButton = wp.components.Button,
		mdColors = MDBlocks.colors,
		registerBlockType('marketers-delight/share-notice', {
			title: __('Share Notice'),
			description: __('Display quoteable text with social media share buttons.'),
			icon: 'networking',
			category: 'marketers-delight',
			supports: {
				align: true
			},
			attributes: {
				text: {type: 'string'},
				socialMedia: {type: 'string'},
				style: {type: 'string'},
				boxStyle: {type: 'string'},
				buttonText: {type: 'string'},
				buttonStyle: {type: 'string'},
				align: {type: 'string'},
				alignment: {type: 'string'},
				marginBottom: {type: 'string'},
				padding: {type: 'string'},
			},
			edit: function (props) {
				var attributes = props.attributes,
					text = attributes.text,
					socialMedia = attributes.socialMedia ? attributes.socialMedia : 'twitter',
					boxStyle = attributes.boxStyle ? attributes.boxStyle : 'outline',
					buttonStyle = attributes.buttonStyle ? attributes.buttonStyle : 'outline',
					alignment = attributes.alignment,
					marginBottom = attributes.marginBottom ? attributes.marginBottom : 'mb-mid',
					padding = attributes.padding ? attributes.padding : 'block-mid';

				if (socialMedia == 'twitter')
					buttonText = __('Tweet This');
				else if (socialMedia == 'facebook')
					buttonText = __('Post to Facebook');
				else if (socialMedia == 'pinterest')
					buttonText = __('Pin This');
				else if (socialMedia == 'linkedin')
					buttonText = __('Share on LinkedIn');
				else
					buttonText = __('Share This');

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
								label: __('Social Media'),
								options: [
									{value: '', label: __('Select network to share to...')},
									{value: 'twitter', label: __('Twitter (default)')},
									{value: 'facebook', label: __('Facebook')},
									{value: 'linkedin', label: __('LinkedIn')},
									{value: 'pinterest', label: __('Pinterest')}
								],
								value: socialMedia,
								onChange: function (val) {
									props.setAttributes({socialMedia: val});
								},
							}),
							el(SelectControl, {
								label: __('Box Style'),
								options: [
									{value: '', label: __('Select box style...')},
									{value: 'outline', label: __('Outline (default)')},
									{value: 'full', label: __('Full Color')}
								],
								value: boxStyle,
								onChange: function (val) {
									props.setAttributes({boxStyle: val});
								}
							}),
							el(SelectControl, {
								label: __('Button Style'),
								options: [
									{value: '', label: __('Select button style...')},
									{value: 'outline', label: __('Outline (default)')},
									{value: 'full', label: __('Full Color')},
								],
								value: buttonStyle,
								onChange: function (val) {
									props.setAttributes({buttonStyle: val});
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
									{label: __('Single'), value: 'block-single'},
									{label: __('Mid (default)'), value: 'block-mid'},
									{label: __('Double'), value: 'block-double'},
									{label: __('Triple'), value: 'block-triple'},
								],
								value: padding,
								onChange: function (val) {
									props.setAttributes({padding: val});
								}
							})
						)
					),
					el(
						'div', {
							className: props.className + ' share-notice share-notice-' + socialMedia + ' share-notice-' + boxStyle + ' ' + marginBottom + ' ' + padding,
							style: {textAlign: alignment}
						},
						el(RichText, {
							key: 'editable',
							tagName: 'p',
							className: 'share-notice-text small-text mb-single',
							value: text,
							placeholder: __('Enter share text here...'),
							onChange: function (val) {
								props.setAttributes({text: val});
							}
						}),
						el('i', {
							className: 'share-notice-icon md-icon-' + socialMedia,
						}),
						el(RichText, {
							key: 'editable',
							tagName: 'button',
							className: 'share-notice-button md-submit md-submit-' + buttonStyle,
							value: buttonText,
							onChange: function (val) {
								props.setAttributes({buttonText: val});
							}
						})
					)
				]
			},
			save: function () {
				return null;
			}
		});
})(wp);
