<?php

/**
 * A slightly modified version of the Range control
 * that shows the number scrolled to and reset function.
 *
 * @since 4.8
 */

class MD_Customize_Control_Range extends WP_Customize_Control
{

    public $type = 'md_range';
    public $classes = '';
    public $unit = 'px';

    public function enqueue()
    {
        wp_enqueue_script('md-range', MD_URL . 'dropins/optins/popups/customize/controls/range.js', array('jquery'), false, true);
    }

    public function to_json()
    {
        parent::to_json();
        $this->json['value'] = $this->value();
        $this->json['link'] = $this->get_link();
        $this->json['classes'] = $this->classes;
        $this->json['default'] = $this->setting->default;
        $this->json['unit'] = $this->unit;
        $this->json['inputAttrs'] = '';
        foreach ($this->input_attrs as $attr => $value)
            $this->json['inputAttrs'] .= $attr . '="' . esc_attr($value) . '" ';
    }

    public function content_template()
    { ?>
        <label class="md-range-label {{ data.classes }}">
            <# if ( data.label ) { #>
            <span class="customize-control-title">{{{ data.label }}}</span>
            <# } #>
            <input type="range" class="md-range-field" value="{{ data.value }}" {{{ data.inputAttrs }}} {{{ data.link
                   }}}/>
            <div class="md-range-value">
                <input type="number" class="md-range-value-val" value="{{ data.value }}"/> {{{ data.unit }}}
            </div>
            <span class="md-range-reset" data-default-value="{{ data.default }}"><i
                        class="dashicons dashicons-image-rotate"></i></span>
            <# if ( data.description ) { #>
            <p class="description customize-control-description" style="clear: both;">{{{ data.description }}}</p>
            <# } #>
        </label>
    <?php }

}

/**
 * Use this control to add a heading and line divider
 * between large sets of controls.
 *
 * @since 4.8
 */
class MD_Customize_Control_Divider extends WP_Customize_Control
{

    public $type = 'md_divider';
    public $toggle = '';
    public $description = '';

    public function render_content()
    { ?>
        <?php if (!empty($this->label)) : ?>
        <label class="md-customize-label-<?php echo !empty($this->toggle) ? 'toggle md-customize-toggle' : 'divider'; ?>"<?php echo !empty($this->toggle) ? ' data-md-customize-toggle-group="' . esc_attr($this->toggle['group']) . '" data-md-customize-toggle-field="' . esc_attr($this->toggle['field']) . '"' : ''; ?>>
            <span class="customize-control-title"
                  style="font-size: 17px; line-height: 34px;"><?php echo esc_html($this->label); ?></span>
        </label>
    <?php endif; ?>
        <?php if (!empty($this->description)) : ?>
        <p class="description"><?php echo esc_html($this->description); ?></p>
    <?php endif; ?>
        <hr/>
    <?php }

}

/**
 * Smaller divider.
 *
 * @since 5.0
 */
class MD_Customize_Control_Subdivider extends WP_Customize_Control
{

    public $type = 'md_subdivider';
    public $toggle = '';
    public $description = '';

    public function render_content()
    { ?>
        <?php if (!empty($this->label)) : ?>
        <label class="md-customize-label-divider">
            <span class="customize-control-title"
                  style="font-size: 16px; margin-top: 10px;"><?php echo esc_html($this->label); ?></span>
        </label>
    <?php endif; ?>
        <?php if (!empty($this->description)) : ?>
        <p class="description"><?php echo esc_html($this->description); ?></p>
    <?php endif; ?>
        <hr/>
    <?php }

}