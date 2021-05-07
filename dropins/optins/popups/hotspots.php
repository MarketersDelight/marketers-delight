<?php
/**
 * Load Popup Hotspots around website.
 *
 * @since 5.0
 */

class md_hotspots extends md_api
{

    /**
     * Run site actions and filters.
     *
     * @since 5.1
     */

    public function actions()
    {
        if (md_setting(array('popups', 'byline')))
            add_filter('md_filter_byline_items', array($this, 'byline_setting'));
    }

    /**
     * Load Popup Hotspots and other template hooks.
     *
     * @since 5.0
     */

    public function template()
    {
        // Header Menu
        $header_menu = md_setting(array('popups', 'header_menu'));
        if (md_has_menu() && $header_menu) {
            add_filter('wp_nav_menu_items', array($this, 'header_menu'), 10, 2);
            md_popup(array('id' => $header_menu));
        }
        // Main Menu
        $main_menu = md_setting(array('popups', 'main_menu'));
        if (md_has_main_menu() && $main_menu) {
            add_action('md_main_menu_side_triggers', array($this, 'main_menu_desktop'));
            add_action('md_main_menu_triggers_bottom', array($this, 'main_menu_mobile'));
            add_filter('md_filter_main_menu_items', array($this, 'main_menu_mobile_columns'));
            md_popup(array('id' => $main_menu));
        }
        // Byline
        $byline = md_setting(array('popups', 'byline'));
        if (md_has_byline() && $byline && !in_array('hotspot', md_get_byline())) {
            add_action('md_hook_byline_after_date', array($this, 'byline'));
            md_popup(array('id' => $byline));
        }
    }

    public function byline_setting($byline)
    {
        $byline['hotspot'] = __('<b>Remove</b> Popup', 'md');
        return $byline;
    }

    /**
     * Add byline HTML to trigger popup.
     *
     * @since 4.5
     */

    public function byline()
    {
        $text = md_setting(array('popups', 'byline_text'));
        $text = !empty($text) ? $text : __('Get updates', 'md');
        ?>
        <span class="byline-popup byline-item">
			<?php echo md_icon('mail-alt', array('classes' => 'byline-item-icon')); ?> <a href="#"
                                                                                          class="md-popup-trigger"
                                                                                          data-popup="md_popup_<?php echo md_setting(array('popups', 'byline')); ?>"><?php echo esc_html($text); ?></a>
		</span>
    <?php }

    /**
     * Add menu button HTML to header menu to trigger popup.
     *
     * @since 4.5.3
     */

    public function header_menu($items, $args)
    {
        if ($args->theme_location == 'header') {
            $button = md_setting(array('popups', 'header_menu_button', 'enable'));
            $text = md_setting(array('popups', 'header_menu_text'));
            $text = !empty($text) ? $text : __('Get updates', 'md');
            $button_classes = !empty($button) ? apply_filters('md_header_menu_popup_button_color', ' button button-sec') : '';
            $items .=
                '<li class="menu-item menu-popup' . $button_classes . '">' .
                '<a href="#" class="md-popup-trigger" data-popup="md_popup_' . esc_attr(md_setting(array('popups', 'header_menu'))) . '"><i class="' . md_icon('mail-alt', true) . ' mr-small"></i> ' . $text . '</a>' .
                '</li>';
        }
        return $items;
    }

    /**
     * Add desktop popup trigger to Main Menu.
     *
     * @since 4.5
     */

    public function main_menu_desktop()
    { ?>
        <span class="md-popup-trigger menu-popup close-on-max"
              data-popup="md_popup_<?php echo md_setting(array('popups', 'main_menu')); ?>">
			<?php echo md_icon('mail-alt'); ?>
		</span>
    <?php }

    /**
     * Add mobile popup trigger to Main Menu.
     *
     * @since 4.5
     */

    public function main_menu_mobile()
    { ?>
        <span class="md-popup-trigger menu-popup col"
              data-popup="md_popup_<?php echo md_setting(array('popups', 'main_menu')); ?>">
			<?php echo md_icon('mail-alt'); ?>
			<span class="menu-trigger-text close-on-mobile"><?php echo md_get_menu_name('main'); ?></span>
		</span>
    <?php }

    /**
     * Let the Main Menu know the status of the popup
     * for layout adjustments.
     *
     * @since 4.5
     */

    public function main_menu_mobile_columns()
    {
        return md_setting(array('popups', 'main_menu')) ? true : false;
    }

}

new md_hotspots;