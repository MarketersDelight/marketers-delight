(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType,
        el = wp.element.createElement,
        __ = wp.i18n.__;
    registerBlockType('marketers-delight/arrow', {
        title: __('Arrow'),
        description: __('Show a down arrow to encourage scrolling.'),
        category: 'marketers-delight',
        icon: 'arrow-down-alt2',
        edit: function (props) {
            return el(
                'p', {className: props.attributes.className + ' arrow-down text-center'},
                el('span', {className: 'dashicons dashicons-arrow-down-alt2 large-title'})
            );
        },
        save: function (props) {
            return el(
                'p', {className: props.attributes.className + ' arrow-down text-center'},
                el('span', {className: 'md-icon-angle-down large-title'})
            );
        }
    });
})(window.wp);