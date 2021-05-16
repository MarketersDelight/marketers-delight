wp.customize.controlConstructor['md_range'] = wp.customize.Control.extend({
	ready: function () {
		var control = this,
			range = control.container.find('.md-range-field'),
			text = control.container.find('.md-range-value-val');
		range.on('input change', function () {
			text.val(range.val());
		});
		text.on('input change', function () {
			control.setting.set(text.val());
		});
		control.container.find('.md-range-reset').on('click', function () {
			wp.customize.previewer.refresh();
			range.val(control.params.default);
			text.val(control.params.default);
			control.setting.set(text.val());
		});
	}
});
