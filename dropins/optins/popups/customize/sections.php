<?php
/**
 * Settings
 */

$wp_customize->add_section($prefix, array(
    'title' => esc_html($fields['name']),
    'panel' => 'md_popups_designer'
));

// Template

$wp_customize->add_setting("{$prefix}[template]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'customize_select')
));

$wp_customize->add_control("{$prefix}[template]", array(
    'type' => 'select',
    'label' => __('Template', 'md'),
    'section' => $prefix,
    'choices' => md_filter_popups_templates()
));

/**
 * Text
 */

$wp_customize->add_setting("{$prefix}[divider1]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider1]", array(
    'type' => 'md_divider',
    'label' => __('Text', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'content'
    ),
    'section' => $prefix
)));

// Subtitle

$wp_customize->add_setting("{$prefix}[content][subtitle]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'text')
));

$wp_customize->add_control("{$prefix}[content][subtitle]", array(
    'type' => 'text',
    'label' => __('Subtitle', 'md'),
    'section' => $prefix
));

// Headline

$wp_customize->add_setting("{$prefix}[content][headline]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'text')
));

$wp_customize->add_control("{$prefix}[content][headline]", array(
    'type' => 'text',
    'label' => __('Headline', 'md'),
    'section' => $prefix
));

// Description

$wp_customize->add_setting("{$prefix}[content][description]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'text')
));

$wp_customize->add_control("{$prefix}[content][description]", array(
    'type' => 'textarea',
    'label' => __('Description', 'md'),
    'section' => $prefix
));

/**
 * Image
 */

$wp_customize->add_setting("{$prefix}[divider2]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider2]", array(
    'type' => 'md_divider',
    'label' => __('Image', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'featured_image'
    ),
    'section' => $prefix
)));

// Featured Image

$wp_customize->add_setting("{$prefix}[featured_image][id]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'absint'
));

$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "{$prefix}[featured_image][id]", array(
    'label' => __('Image', 'md'),
    'section' => $prefix
)));

// Alignment

$wp_customize->add_setting("{$prefix}[featured_image][alignment]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'customize_select')
));

$wp_customize->add_control("{$prefix}[featured_image][alignment]", array(
    'type' => 'select',
    'label' => __('Alignment', 'md'),
    'section' => $prefix,
    'choices' => array(
        '' => __('Select alignment...', 'md'),
        'alignleft' => __('Left', 'md'),
        'alignright' => __('Right', 'md'),
        'aligncenter' => __('Center', 'md')
    )
));

// Width

$wp_customize->add_setting("{$prefix}[featured_image][width]", array(
    'capability' => 'edit_theme_options',
    'type' => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'number')
));

$wp_customize->add_control(new MD_Customize_Control_Range($wp_customize, "{$prefix}[featured_image][width]", array(
    'label' => __('Width', 'md'),
    'type' => 'md_range',
    'unit' => '%',
    'section' => $prefix,
    'input_attrs' => array(
        'min' => 15,
        'max' => 100
    ),
)));

// CSS Classes

$wp_customize->add_setting("{$prefix}[featured_image][classes]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options'
));

$wp_customize->add_control("{$prefix}[featured_image][classes]", array(
    'type' => 'text',
    'label' => __('Image CSS Classes', 'md'),
    'description' => $helper_classes,
    'section' => $prefix
));

/**
 * Buttons
 */

$wp_customize->add_setting("{$prefix}[divider3]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider3]", array(
    'type' => 'md_divider',
    'label' => __('Buttons', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'button'
    ),
    'section' => $prefix
)));

// Fields

$c = 0;
$popups = array_merge(array('' => __('Select a popup...', 'md')), md_get_popups('options'));

foreach (array('button' => 'Main Button', 'button_sec' => 'Secondary Button') as $button => $label) {

    $wp_customize->add_setting("{$prefix}[{$button}][divider3{$c}]", array('capability' => 'edit_theme_options'));

    $wp_customize->add_control(new MD_Customize_Control_Subdivider($wp_customize, "{$prefix}[{$button}][divider3{$c}]", array(
        'label' => $label,
        'section' => $prefix
    )));

    // Enable

    $wp_customize->add_setting("{$prefix}[{$button}][enable]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => array($sanitize, 'checkbox')
    ));

    $wp_customize->add_control("{$prefix}[{$button}][enable]", array(
        'type' => 'checkbox',
        'label' => sprintf(__('Enable %s', 'md'), $label),
        'section' => $prefix
    ));

    // Text

    $wp_customize->add_setting("{$prefix}[{$button}][text]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_html'
    ));

    $wp_customize->add_control("{$prefix}[{$button}][text]", array(
        'type' => 'text',
        'label' => __('Text', 'md'),
        'section' => $prefix
    ));

    // Subtext

    $wp_customize->add_setting("{$prefix}[{$button}][subtext]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_html'
    ));

    $wp_customize->add_control("{$prefix}[{$button}][subtext]", array(
        'type' => 'text',
        'label' => __('Subtext', 'md'),
        'section' => $prefix
    ));

    // Action

    $wp_customize->add_setting("{$prefix}[{$button}][action]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => array($sanitize, 'customize_select')
    ));

    $wp_customize->add_control("{$prefix}[{$button}][action]", array(
        'type' => 'select',
        'label' => __('Action', 'md'),
        'section' => $prefix,
        'choices' => array(
            '' => __('Select button action...', 'md'),
            'link' => __('Link to page', 'md'),
            'popup' => __('Open popup', 'md'),
            'close' => __('Close popup', 'md')
        )
    ));

    // Link

    $wp_customize->add_setting("{$prefix}[{$button}][link]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'esc_url'
    ));

    $wp_customize->add_control("{$prefix}[{$button}][link]", array(
        'type' => 'text',
        'label' => __('Link', 'md'),
        'description' => __('Set Action to "Link to page" to use this field.', 'md'),
        'section' => $prefix
    ));

    // Popup

    $wp_customize->add_setting("{$prefix}[{$button}][popup]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => array($sanitize, 'customize_select')
    ));

    $wp_customize->add_control("{$prefix}[{$button}][popup]", array(
        'type' => 'select',
        'label' => __('Popup', 'md'),
        'description' => __('Set Action to "Open popup" to use this field.', 'md'),
        'section' => $prefix,
        'choices' => $popups
    ));

    // Counter

    $c++;

}

// Footer Text

$wp_customize->add_setting("{$prefix}[button][footer_text]", array(
    'type' => 'option',
    'transport' => 'postMessage',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'text')
));

$wp_customize->add_control("{$prefix}[button][footer_text]", array(
    'type' => 'text',
    'label' => __('Buttons Footer Text', 'md'),
    'section' => $prefix
));

/**
 * Email Form
 */

$wp_customize->add_setting("{$prefix}[divider4]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider4]", array(
    'type' => 'md_divider',
    'label' => __('Email Form', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'email'
    ),
    'section' => $prefix
)));

$email_lists = md_email_data(array('show' => 'names', 'label' => true, 'empty_label' => true));

// Select List

$wp_customize->add_setting("{$prefix}[email][email_list]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'customize_select')
));

$wp_customize->add_control("{$prefix}[email][email_list]", array(
    'type' => 'select',
    'label' => __('Email List', 'md'),
    'section' => $prefix,
    'choices' => $email_lists
));

// Show Name Field

$wp_customize->add_setting("{$prefix}[email][email_show_name]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'checkbox')
));

$wp_customize->add_control("{$prefix}[email][email_show_name]", array(
    'type' => 'checkbox',
    'label' => __('Ask for subscribers name in signup form', 'md'),
    'section' => $prefix
));

// Form Style

$wp_customize->add_setting("{$prefix}[email][email_form_attached]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'checkbox')
));

$wp_customize->add_control("{$prefix}[email][email_form_attached]", array(
    'type' => 'checkbox',
    'label' => __('Attach input fields to each other', 'md'),
    'section' => $prefix
));

// Name Label

$wp_customize->add_setting("{$prefix}[email][email_name_label]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => 'esc_attr'
));

$wp_customize->add_control("{$prefix}[email][email_name_label]", array(
    'type' => 'text',
    'label' => __('Name Field Label', 'md'),
    'section' => $prefix,
    'input_attrs' => array(
        'placeholder' => __('Enter your name&hellip;', 'md')
    )
));

// Email Label

$wp_customize->add_setting("{$prefix}[email][email_email_label]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => 'esc_attr'
));

$wp_customize->add_control("{$prefix}[email][email_email_label]", array(
    'type' => 'text',
    'label' => __('Email Field Label', 'md'),
    'section' => $prefix,
    'input_attrs' => array(
        'placeholder' => __('Enter your email&hellip;', 'md')
    )
));

// Submit Label

$wp_customize->add_setting("{$prefix}[email][email_submit_label]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => 'esc_attr'
));

$wp_customize->add_control("{$prefix}[email][email_submit_label]", array(
    'type' => 'text',
    'label' => __('Submit Button Label', 'md'),
    'section' => $prefix,
    'input_attrs' => array(
        'placeholder' => __('Join Now!', 'md')
    )
));

// Email Footer

$wp_customize->add_setting("{$prefix}[email][email_footer]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'transport' => 'postMessage',
    'sanitize_callback' => array($sanitize, 'text')
));

$wp_customize->add_control("{$prefix}[email][email_footer]", array(
    'type' => 'text',
    'label' => __('Email Footer', 'md'),
    'section' => $prefix
));

// CSS Classes

$wp_customize->add_setting("{$prefix}[email][classes]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options'
));

$wp_customize->add_control("{$prefix}[email][classes]", array(
    'type' => 'text',
    'label' => __('Email CSS Classes', 'md'),
    'description' => $helper_classes,
    'section' => $prefix
));

/**
 * Design
 */

$wp_customize->add_setting("{$prefix}[divider5]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider5]", array(
    'type' => 'md_divider',
    'label' => __('Design', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'design'
    ),
    'section' => $prefix
)));

// Colors

$colors = array(
    'bg_color' => __('Background Color', 'md'),
    'secondary_color' => __('Secondary Background Color', 'md'),
    'border_color' => __('Border Color', 'md'),
    'headline_color' => __('Headline Color', 'md'),
    'text_color' => __('Text Color', 'md'),
    'button_color' => __('Button Color', 'md'),
    'button_text_color' => __('Button Text Color', 'md'),
    'button_sec_color' => __('Button Secondary Color', 'md'),
    'button_sec_text_color' => __('Button Secondary Text Color', 'md'),
    'close_color' => __('Close (&times;) color', 'md'),
);

foreach ($colors as $color => $label) {
    $wp_customize->add_setting("{$prefix}[design][{$color}]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "{$prefix}[design][{$color}]", array(
        'label' => $label,
        'section' => $prefix
    )));
}

// BG Image

$wp_customize->add_setting("{$prefix}[design][bg_image]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options'
));

$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "{$prefix}[design][bg_image]", array(
    'section' => $prefix,
    'label' => __('Background Image', 'md'),
    'width' => 750,
    'height' => 750
)));

// BG Image Repeat

$wp_customize->add_setting("{$prefix}[design][bg_repeat]", array(
    'type' => 'option',
    'capability' => 'edit_theme_options',
    'sanitize_callback' => array($sanitize, 'checkbox')
));

$wp_customize->add_control("{$prefix}[design][bg_repeat]", array(
    'type' => 'checkbox',
    'label' => __('Enable background repeat', 'md'),
    'section' => $prefix,
));

/**
 * Custom Template
 */

$wp_customize->add_setting("{$prefix}[divider6]", array('capability' => 'edit_theme_options'));

$wp_customize->add_control(new MD_Customize_Control_Divider($wp_customize, "{$prefix}[divider6]", array(
    'type' => 'md_divider',
    'label' => __('Advanced', 'md'),
    'toggle' => array(
        'group' => "marketers_delight-popups_data-{$popup_id}",
        'field' => 'custom_template'
    ),
    'section' => $prefix
)));

if (class_exists('WP_Customize_Code_Editor_Control')) {

    // Enable Custom Template

    $wp_customize->add_setting("{$prefix}[custom_template][show_custom_template]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => array($sanitize, 'checkbox')
    ));

    $wp_customize->add_control("{$prefix}[custom_template][show_custom_template]", array(
        'type' => 'checkbox',
        'label' => __('Enable custom template', 'md'),
        'section' => $prefix,
    ));

    // Template HTML

    $wp_customize->add_setting("{$prefix}[custom_template][custom_template]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, "{$prefix}[custom_template][custom_template]", array(
        'code_type' => 'html',
        'label' => __('Template HTML', 'md'),
        'section' => $prefix
    )));

    // Add Paragraphs

    $wp_customize->add_setting("{$prefix}[custom_template][custom_template_filter]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => array($sanitize, 'checkbox')
    ));

    $wp_customize->add_control("{$prefix}[custom_template][custom_template_filter]", array(
        'type' => 'checkbox',
        'label' => __('Enable WordPress formatting', 'md'),
        'section' => $prefix,
    ));

    // Template CSS

    $wp_customize->add_setting("{$prefix}[custom_template][custom_css]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => array($sanitize, 'text')
    ));

    $wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, "{$prefix}[custom_template][custom_css]", array(
        'label' => __('Template CSS', 'md'),
        'code_type' => 'css',
        'description' => sprintf(__('Popup ID: <code>#md_popup_%s</code>', 'md'), $popup_id),
        'section' => $prefix
    )));

    // CSS Classes

    $wp_customize->add_setting("{$prefix}[custom_template][classes]", array(
        'type' => 'option',
        'capability' => 'edit_theme_options'
    ));

    $wp_customize->add_control("{$prefix}[custom_template][classes]", array(
        'type' => 'text',
        'label' => __('CSS Classes', 'md'),
        'description' => $helper_classes,
        'section' => $prefix
    ));

}