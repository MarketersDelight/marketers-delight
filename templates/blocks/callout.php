<?php
$title = md_block_field($attributes, 'title');
$text = md_block_field($attributes, 'text');
$list = md_block_field($attributes, 'list');
$icon = md_block_field($attributes, 'icon');
$iconImage = md_block_field($attributes, 'iconImage');
$buttonText = md_block_field($attributes, 'buttonText');
$popup = md_block_field($attributes, 'popup');
$url = md_block_field($attributes, 'url');
$href = !empty($url) ? $url : '#';
$align = md_block_field($attributes, 'align');
$alignment = md_block_field($attributes, 'alignment');
$marginBottom = md_block_field($attributes, 'marginBottom');
$padding = md_block_field($attributes, 'padding');
$shadow = md_block_field($attributes, 'shadow');
$className = md_block_field($attributes, 'className');
$borderColor = md_block_field($attributes, 'borderColor');
$bgColor = md_block_field($attributes, 'bgColor');
$bgColorClass = md_block_field($attributes, 'bgColorClass');
$bgImage = md_block_field($attributes, 'bgImage');
$iconColor = md_block_field($attributes, 'iconColor');
$textColor = md_block_field($attributes, 'textColor');
$textColorClass = md_block_field($attributes, 'textColorClass');
$buttonColor = md_block_field($attributes, 'buttonColor');
$buttonColorClass = md_block_field($attributes, 'buttonColorClass');
$buttonTextColor = md_block_field($attributes, 'buttonTextColor');
$buttonTextColorClass = md_block_field($attributes, 'buttonTextColorClass');
$has_popup = !empty($popup) && empty($url) ? true : false;
$popup_data = $has_popup ? ' data-popup="md_popup_' . esc_attr($popup) . '"' : '';
$bg_color = empty($bgColorClass) && !empty($bgColor) ? 'background-color: ' . esc_attr($bgColor) . '; ' : '';
$border_color = !empty($borderColor) ? 'border-color: ' . esc_attr($borderColor) . ';' : '';
$bg_image = !empty($bgImage) ? ' background-image: url(\'' . esc_url($bgImage) . '\');' : '';
$has_textColor = !empty($textColor) && empty($textColorClass) ? true : false;
$has_buttonColor = !empty($buttonColor) && empty($buttonColorClass) ? true : false;
$has_buttonTextColor = !empty($buttonTextColor) && empty($buttonTextColorClass) ? true : false;
$style = $has_textColor || $bgColor || $borderColor || $bgImage ? ' style="' . $bg_color . $bg_image . $border_color . ($has_textColor ? ' color: ' . esc_attr($textColor) . ';' : '') . '"' : '';
$classes = $button_classes = array();
$classes[] = 'callout';
$classes[] = !empty($align) ? "align{$align}" : '';
$classes[] = !empty($bgColorClass) ? $bgColorClass : 'has-accent-background-color';
$classes[] = !empty($textColorClass) ? $textColorClass : '';
$classes[] = !empty($marginBottom) ? $marginBottom : 'mb-double';
$classes[] = !empty($padding) ? $padding : 'block-mid';
if (!empty($className))
    $classes[] = $className;
if (!empty($icon) || !empty($iconImage))
    $classes[] = 'has-icon';
if (!empty($alignment))
    $classes[] = 'text-' . esc_attr($alignment);
if (!empty($shadow))
    $classes[] = 'shadow-small';
if (!empty($bgImage))
    $classes[] = 'image-overlay';
$classes[] = 'mt-double';
$classes = join(' ', $classes);
if (!empty($buttonTextColorClass))
    $button_classes[] = $buttonTextColorClass;
if ($has_popup)
    $button_classes[] = 'md-popup-trigger';
$button_classes[] = !empty($buttonColorClass) ? $buttonColorClass : '';
$button_classes = join(' ', $button_classes);
if (md_has('popups') && $has_popup)
    md_popup(array('id' => $popup));
?>
<div class="<?php echo esc_attr($classes); ?>"<?php echo $style; ?>>
    <?php if (!empty($icon)) : ?>
        <div class="callout-icon icon mb-single"<?php echo !empty($iconColor) ? ' style="background-color: ' . esc_attr($iconColor) . ';"' : ''; ?>>
            <i class="<?php echo esc_attr($icon); ?>"></i>
        </div>
    <?php elseif (!empty($iconImage)) : ?>
        <div class="callout-icon image mb-single">
            <img src="<?php echo esc_url($iconImage); ?>" height="100" width="100"
                 alt="<?php echo esc_attr($title); ?>"/>
        </div>
    <?php endif; ?>
    <?php if ($title) : ?>
        <p class="callout-title med-title mb-single"><?php echo md_text_field($title); ?></p>
    <?php endif; ?>
    <?php if ($text) : ?>
        <div class="callout-text mb-single">
            <?php echo wpautop($text); ?>
        </div>
    <?php endif; ?>
    <?php if ($list) : ?>
        <ul class="callout-list mb-single">
            <?php echo wpautop($list); ?>
        </ul>
    <?php endif; ?>
    <?php if (!empty($buttonText)) :
        $button_bg = $has_buttonColor ? 'background-color: ' . esc_attr($buttonColor) . ';' : '';
        $button_color = $has_buttonTextColor ? ' color: ' . esc_attr($buttonTextColor) . ';' : '';
        $button_style = $has_buttonColor ? ' style="' . $button_bg . $button_color . '"' : '';
        ?>
        <p class="callout-action">
            <a href="<?php echo esc_url($href); ?>"
               class="callout-button button button-arrow <?php echo esc_attr($button_classes); ?>"<?php echo $button_style; ?><?php echo $popup_data; ?>><?php echo esc_html($buttonText); ?></a>
        </p>
    <?php endif; ?>
</div>