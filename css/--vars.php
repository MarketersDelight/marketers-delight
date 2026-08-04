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
