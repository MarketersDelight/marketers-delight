<style type="text/css">

:root {
	--md-small: <?php echo $small; ?>px;
	--md-third: <?php echo $third; ?>px;
	--md-half: <?php echo $half; ?>px;
	--md-single: <?php echo $single; ?>px;
	--md-mid: <?php echo $mid; ?>px;
	--md-double: <?php echo $double; ?>px;
	--md-triple: <?php echo $triple; ?>px;
	--md-quad: <?php echo $quad; ?>px;

	--md-width-site: <?php echo $site_width; ?>px;
	--md-width-content: <?php echo $content_width; ?>px;
	--md-width-post: <?php echo $post_width; ?>px;
	--md-width-sidebar: <?php echo $sidebar_width; ?>px;
	--md-width-panel: <?php echo $panel_width; ?>px;
	--md-width-site-wide: <?php echo $site_width_wide; ?>px;

	--md-border-radius: 8px;
	--md-box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
	--md-box-shadow-small: 0 1px 3px rgba(0, 0, 0, 0.15);
	--md-box-shadow-medium: 0 4px 16px rgba(0, 0, 0, 0.12);
	--md-box-shadow-large: 0 8px 32px rgba(0, 0, 0, 0.15);
	--md-box-shadow-huge: 0 16px 48px rgba(0, 0, 0, 0.20);

	--md-transition: 0.3s;
	--md-transition-slow: 0.5s;

	--md-font-size: <?php echo $this->fluid( $font_size['desktop'], $font_size['mobile'] ); ?>;
	--md-font-size-sm: <?php echo $font_size['mobile']; ?>px;
	--md-line-height: <?php echo $this->fluid( $line_height['desktop'], $line_height['mobile'] ); ?>;
	--md-line-height-sm: <?php echo $line_height['mobile']; ?>px;
	--md-bold: <?php echo $bold; ?>;

	--md-huge: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
	--md-huge-line-height: <?php echo $typography['huge']['line_height']['desktop']; ?>px;
	--md-h1: <?php echo $h1['font_size']['desktop']; ?>px;
	--md-h1-line-height: <?php echo $h1['line_height']['desktop']; ?>px;
	--md-h2: <?php echo $h2['font_size']['desktop']; ?>px;
	--md-h2-line-height: <?php echo $h2['line_height']['desktop']; ?>px;
	--md-h3: <?php echo $h3['font_size']['desktop']; ?>px;
	--md-h3-line-height: <?php echo $h3['line_height']['desktop']; ?>px;
	--md-h4: <?php echo $h4['font_size']['desktop']; ?>px;
	--md-h4-line-height: <?php echo $h4['line_height']['desktop']; ?>px;
	--md-h5: <?php echo $h5['font_size']['desktop']; ?>px;
	--md-h5-line-height: <?php echo $h5['line_height']['desktop']; ?>px;
	--md-h6: <?php echo $h6['font_size']['desktop']; ?>px;
	--md-h6-line-height: <?php echo $h6['line_height']['desktop']; ?>px;

	--md-color-background: <?php echo $colors['palette']['background']; ?>;
	--md-color-surface: <?php echo $colors['palette']['surface']; ?>;
	--md-color-primary: <?php echo $colors['palette']['primary']; ?>;
	--md-color-secondary: <?php echo $colors['palette']['secondary']; ?>;
	--md-color-tertiary: <?php echo $colors['palette']['tertiary']; ?>;
	--md-color-border: <?php echo $colors['palette']['border']; ?>;
	--md-color-highlight: <?php echo $colors['palette']['highlight']; ?>;
	--md-color-text: <?php echo $colors['palette']['text-main']; ?>;
	--md-color-text-secondary: <?php echo $colors['palette']['text-secondary']; ?>;
	--md-color-warning: #f58f2a;
	--md-color-danger: #ae2525;
	--md-color-white: <?php echo $colors['palette']['white']; ?>;

	--md-links: <?php echo $colors['site']['links']; ?>;
	--md-button: <?php echo $colors['palette']['button']; ?>;
	--md-button-text: <?php echo $colors['site']['button-text']; ?>;
	--md-button-secondary: <?php echo $colors['site']['button-secondary']; ?>;
	--md-button-secondary-text: <?php echo $colors['site']['button-secondary-text']; ?>;

	--md-headline: <?php echo $colors['site']['headline']; ?>;
	--md-headline-links: <?php echo $colors['site']['headline-links']; ?>;

	--md-header: <?php echo $colors['header']['bg_color']; ?>;
	--md-header-border: <?php echo $colors['header']['border_color']; ?>;
	--md-header-color: <?php echo ! empty( $colors['header']['color'] ) ? $colors['header']['color'] : 'inherit'; ?>;

	--md-menu-links: <?php echo $colors['menu']['links']; ?>;
	--md-menu-active: <?php echo $colors['menu']['active']; ?>;
	--md-menu-hover: <?php echo $colors['menu']['hover']; ?>;
	--md-submenu-links: <?php echo $colors['submenu']['links']; ?>;
	--md-submenu: <?php echo $colors['submenu']['bg_color']; ?>;
	--md-submenu-hover: <?php echo ! empty( $colors['submenu']['hover'] ) ? $colors['submenu']['hover'] : 'inherit'; ?>;

	--md-content-body: <?php echo $colors['content']['body_color']; ?>;
	--md-content: <?php echo $colors['content']['bg_color']; ?>;
	--md-content-border: <?php echo $colors['content']['border_color']; ?>;
	--md-page-cover: <?php echo $colors['content']['page_cover']; ?>;

	--md-sidebar: <?php echo ! empty( $colors['sidebar']['bg_color'] ) ? $colors['sidebar']['bg_color'] : 'transparent'; ?>;
	--md-sidebar-text: <?php echo $colors['sidebar']['text']; ?>;
	--md-sidebar-links: <?php echo $colors['sidebar']['links']; ?>;
	--md-sidebar-title: <?php echo $colors['sidebar']['title']; ?>;
	--md-sidebar-title-links: <?php echo $colors['sidebar']['title_link']; ?>;

	--md-footer: <?php echo $colors['footer']['bg_color']; ?>;
	--md-footer-border: <?php echo $colors['footer']['border_color']; ?>;
	--md-footer-text: <?php echo $colors['footer']['text']; ?>;
	--md-footer-links: <?php echo $colors['footer']['links']; ?>;
	--md-footer-title: <?php echo $colors['footer']['title']; ?>;
	--md-footer-title-links: <?php echo $colors['footer']['title_link']; ?>;
}