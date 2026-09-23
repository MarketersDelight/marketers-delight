<style type="text/css">

:root {
	--md-font-size: <?php echo $this->fluid( $font_size['desktop'], $font_size['mobile'] ); ?>;
	--md-font-size-sm: <?php echo $font_size['mobile']; ?>px;
	--md-line-height: <?php echo $this->fluid( $line_height['desktop'], $line_height['mobile'] ); ?>;
	--md-line-height-sm: <?php echo $line_height['mobile']; ?>px;
	--md-bold: <?php echo $bold; ?>;

	--md-huge: <?php echo $this->fluid( $typography['huge']['font_size']['desktop'], $typography['huge']['font_size']['mobile'] ); ?>;
	--md-huge-line-height: <?php echo $this->fluid( $typography['huge']['line_height']['desktop'], $typography['huge']['line_height']['mobile'] ); ?>;
	--md-h1: <?php echo $this->fluid( $typography['h1']['font_size']['desktop'], $typography['h1']['font_size']['mobile'] ); ?>;
	--md-h1-line-height: <?php echo $this->fluid( $typography['h1']['line_height']['desktop'], $typography['h1']['line_height']['mobile'] ); ?>;
	--md-h2: <?php echo $this->fluid( $typography['h2']['font_size']['desktop'], $typography['h2']['font_size']['mobile'] ); ?>;
	--md-h2-line-height: <?php echo $this->fluid( $typography['h2']['line_height']['desktop'], $typography['h2']['line_height']['mobile'] ); ?>;
	--md-h3: <?php echo $this->fluid( $typography['h3']['font_size']['desktop'], $typography['h3']['font_size']['mobile'] ); ?>;
	--md-h3-line-height: <?php echo $this->fluid( $typography['h3']['line_height']['desktop'], $typography['h3']['line_height']['mobile'] ); ?>;
	--md-h4: <?php echo $this->fluid( $typography['h4']['font_size']['desktop'], $typography['h4']['font_size']['mobile'] ); ?>;
	--md-h4-line-height: <?php echo $this->fluid( $typography['h4']['line_height']['desktop'], $typography['h4']['line_height']['mobile'] ); ?>;
	--md-h5: <?php echo $this->fluid( $typography['h5']['font_size']['desktop'], $typography['h5']['font_size']['mobile'] ); ?>;
	--md-h5-line-height: <?php echo $this->fluid( $typography['h5']['line_height']['desktop'], $typography['h5']['line_height']['mobile'] ); ?>;
	--md-h6: <?php echo $this->fluid( $typography['h6']['font_size']['desktop'], $typography['h6']['font_size']['mobile'] ); ?>;
	--md-h6-line-height: <?php echo $this->fluid( $typography['h6']['line_height']['desktop'], $typography['h6']['line_height']['mobile'] ); ?>;

	--md-small: <?php echo $spacers['small']['desktop']; ?>px;
	--md-third: <?php echo $spacers['third']['desktop']; ?>px;
	--md-half: <?php echo $spacers['half']['desktop']; ?>px;
	--md-single: <?php echo $spacers['single']['desktop']; ?>px;
	--md-mid: <?php echo $spacers['mid']['desktop']; ?>px;
	--md-double: <?php echo $spacers['double']['desktop']; ?>px;
	--md-triple: <?php echo $spacers['triple']['desktop']; ?>px;
	--md-quad: <?php echo $spacers['quad']['desktop']; ?>px;

	--md-single-x: <?php echo $this->fluid( $spacers['single']['desktop'], $spacers['half']['desktop'], 600 ); ?>;
	--md-mid-x: <?php echo $this->fluid( $spacers['mid']['desktop'], $spacers['half']['desktop'], 600 ); ?>;
	--md-double-x: <?php echo $this->fluid( $spacers['double']['desktop'], $spacers['half']['desktop'], 600 ); ?>;
	--md-triple-x: <?php echo $this->fluid( $spacers['triple']['desktop'], $spacers['half']['desktop'], 600 ); ?>;
	--md-quad-x: <?php echo $this->fluid( $spacers['quad']['desktop'], $spacers['half']['desktop'], 600 ); ?>;

	--md-width-site: <?php echo $site_width; ?>px;
	--md-width-content: <?php echo $content_width; ?>px;
	--md-width-alignwide: <?php echo $alignwide_width; ?>px;
	--md-width-post: <?php echo $post_width; ?>px;
	--md-width-sidebar: <?php echo $sidebar_width; ?>px;
	--md-width-panel: <?php echo $panel_width; ?>px;
	--md-width-site-wide: <?php echo $site_width_wide; ?>px;

	--md-border-radius: <?php echo $effects['border_radius']; ?>;
	--md-box-shadow: <?php echo $effects['box_shadow']['default']; ?>;
	--md-box-shadow-small: <?php echo $effects['box_shadow']['small']; ?>;
	--md-box-shadow-medium: <?php echo $effects['box_shadow']['medium']; ?>;
	--md-box-shadow-large: <?php echo $effects['box_shadow']['large']; ?>;
	--md-box-shadow-huge: <?php echo $effects['box_shadow']['huge']; ?>;
	--md-transition: 0.3s;
	--md-transition-slow: 0.5s;

	--md-color-background: <?php echo $colors['palette']['background']; ?>;
	--md-color-surface: <?php echo $colors['palette']['surface']; ?>;
	--md-color-primary: <?php echo $colors['palette']['primary']; ?>;
	--md-color-secondary: <?php echo $colors['palette']['secondary']; ?>;
	--md-color-tertiary: <?php echo $colors['palette']['tertiary']; ?>;
	--md-color-border: <?php echo $colors['palette']['border']; ?>;
	--md-color-highlight: <?php echo $colors['palette']['highlight']; ?>;
	--md-color-text: <?php echo $colors['palette']['text-main']; ?>;
	--md-color-text-secondary: <?php echo $colors['palette']['text-secondary']; ?>;
	--md-color-warning: <?php echo $colors['actions']['status']['warning_color']; ?>;
	--md-color-danger: <?php echo $colors['actions']['status']['danger_color']; ?>;
	--md-color-white: <?php echo $colors['palette']['white']; ?>;

	--md-site-background: <?php echo $colors['site']['bg_color']; ?>;
	--md-site-text: <?php echo $colors['site']['text_color']; ?>;
	--md-site-text-muted: <?php echo $colors['site']['muted_text_color']; ?>;
	--md-site-text-contrast: <?php echo $colors['site']['contrast_text_color']; ?>;
	--md-site-links: <?php echo $colors['site']['link_color']; ?>;
	--md-site-links-muted: <?php echo $colors['site']['muted_link_color']; ?>;
	--md-site-headlines: <?php echo $colors['site']['headline_color']; ?>;
	--md-site-headline-links: <?php echo $colors['site']['headline_link_color']; ?>;

	--md-text: var(--md-site-text);
	--md-text-muted: var(--md-site-text-muted);
	--md-links: var(--md-site-links);
	--md-links-muted: var(--md-site-links-muted);
	--md-headlines: var(--md-site-headlines);
	--md-headline-links: var(--md-site-headline-links);
	--md-border: var(--md-content-border);

	--md-action-primary: <?php echo $colors['actions']['primary']['bg_color']; ?>;
	--md-action-primary-text: <?php echo $colors['actions']['primary']['text_color']; ?>;
	--md-action-secondary: <?php echo $colors['actions']['secondary']['bg_color']; ?>;
	--md-action-secondary-text: <?php echo $colors['actions']['secondary']['text_color']; ?>;

	--md-header-background: <?php echo $colors['header']['bg_color']; ?>;
	--md-header-text: <?php echo $colors['header']['text_color']; ?>;
	--md-header-border: <?php echo $colors['header']['border_color']; ?>;
	--md-header-menu-links: <?php echo $colors['header']['menu']['link_color']; ?>;
	--md-header-menu-hover: <?php echo $colors['header']['menu']['link_hover_color']; ?>;
	--md-header-menu-active: <?php echo $colors['header']['menu']['link_active_color']; ?>;
	--md-header-submenu-background: <?php echo $colors['header']['submenu']['bg_color']; ?>;
	--md-header-submenu-links: <?php echo $colors['header']['submenu']['link_color']; ?>;
	--md-header-submenu-hover: <?php echo $colors['header']['submenu']['link_hover_color']; ?>;
	--md-header-submenu-border: <?php echo $colors['header']['submenu']['border_color']; ?>;

	--md-content-main-background: <?php echo $colors['content']['main_bg_color']; ?>;
	--md-content-box-background: <?php echo $colors['content']['box_bg_color']; ?>;
	--md-content-box-border: <?php echo $colors['content']['box_border_color']; ?>;
	--md-content-box-text: <?php echo $colors['content']['box_text_color']; ?>;
	--md-content-box-text-muted: <?php echo $colors['content']['box_muted_text_color']; ?>;
	--md-content-box-links: <?php echo $colors['content']['box_link_color']; ?>;
	--md-content-border: <?php echo $colors['content']['border_color']; ?>;
	--md-page-cover-overlay: <?php echo $colors['content']['page_cover_overlay_color']; ?>;
	--md-form-background: #fff;
	--md-tag-background: var(--md-color-tertiary);

	--md-sidebar-background: <?php echo ! empty( $colors['sidebar']['bg_color'] ) ? $colors['sidebar']['bg_color'] : 'transparent'; ?>;
	--md-sidebar-text: <?php echo $colors['sidebar']['text_color']; ?>;
	--md-sidebar-links: <?php echo $colors['sidebar']['link_color']; ?>;
	--md-sidebar-title: <?php echo $colors['sidebar']['title_color']; ?>;
	--md-sidebar-title-links: <?php echo $colors['sidebar']['title_link_color']; ?>;

	--md-panel-background: <?php echo $colors['panel']['bg_color']; ?>;
	--md-panel-text: <?php echo $colors['panel']['text_color']; ?>;
	--md-panel-links: <?php echo $colors['panel']['link_color']; ?>;
	--md-panel-border: <?php echo $colors['panel']['border_color']; ?>;

	--md-footer-background: <?php echo $colors['footer']['bg_color']; ?>;
	--md-footer-text: <?php echo $colors['footer']['text_color']; ?>;
	--md-footer-border: <?php echo $colors['footer']['border_color']; ?>;
	--md-footer-links: <?php echo $colors['footer']['link_color']; ?>;
	--md-footer-title: <?php echo $colors['footer']['title_color']; ?>;
	--md-footer-title-links: <?php echo $colors['footer']['title_link_color']; ?>;
}

/* Portable fallbacks for presets registered in theme.json. */
:where(:root) {
	--wp--preset--spacing--small: <?php echo $spacers['small']['desktop']; ?>px;
	--wp--preset--spacing--third: <?php echo $spacers['third']['desktop']; ?>px;
	--wp--preset--spacing--half: <?php echo $spacers['half']['desktop']; ?>px;
	--wp--preset--spacing--single: <?php echo $spacers['single']['desktop']; ?>px;
	--wp--preset--spacing--mid: <?php echo $spacers['mid']['desktop']; ?>px;
	--wp--preset--spacing--double: <?php echo $spacers['double']['desktop']; ?>px;
	--wp--preset--spacing--triple: <?php echo $spacers['triple']['desktop']; ?>px;
	--wp--preset--spacing--quad: <?php echo $spacers['quad']['desktop']; ?>px;

	--wp--preset--border-radius--rounded: <?php echo $effects['border_radius']; ?>;
	--wp--preset--shadow--small: <?php echo $effects['box_shadow']['small']; ?>;
	--wp--preset--shadow--medium: <?php echo $effects['box_shadow']['medium']; ?>;
	--wp--preset--shadow--large: <?php echo $effects['box_shadow']['large']; ?>;
	--wp--preset--shadow--huge: <?php echo $effects['box_shadow']['huge']; ?>;

	--wp--preset--color--background: <?php echo $colors['palette']['background']; ?>;
	--wp--preset--color--surface: <?php echo $colors['palette']['surface']; ?>;
	--wp--preset--color--primary: <?php echo $colors['palette']['primary']; ?>;
	--wp--preset--color--secondary: <?php echo $colors['palette']['secondary']; ?>;
	--wp--preset--color--tertiary: <?php echo $colors['palette']['tertiary']; ?>;
	--wp--preset--color--border: <?php echo $colors['palette']['border']; ?>;
	--wp--preset--color--highlight: <?php echo $colors['palette']['highlight']; ?>;
	--wp--preset--color--text-main: <?php echo $colors['palette']['text-main']; ?>;
	--wp--preset--color--text-secondary: <?php echo $colors['palette']['text-secondary']; ?>;
	--wp--preset--color--button: <?php echo $colors['palette']['button']; ?>;
	--wp--preset--color--white: <?php echo $colors['palette']['white']; ?>;
}
