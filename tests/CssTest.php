<?php
/**
 * Tests CSS template registration and destination routing.
 *
 * @since 6.0
 */

class CssTest extends MD_TestCase {

	private $css;

	protected function setUp(): void {
		parent::setUp();
		$this->css = ( new ReflectionClass( 'md_css' ) )->newInstanceWithoutConstructor();
	}

	private function source( $path ) {
		return file_get_contents( dirname( __DIR__ ) . "/{$path}" );
	}

	private function render_variables() {
		$file = dirname( __DIR__ ) . '/css/--vars.php';
		$render = function() use ( $file ) {
			$design = new md_design;
			$values = $design->values();
			$colors = $values['colors'];
			$typography = $values['typography'];
			$effects = $design->effects();
			$spacers = $design->spacers( $values );
			$widths = $design->widths( $values );
			$site_width = $widths['site_width'];
			$this->site_width = $site_width;
			$site_width_wide = $widths['site_width_wide'];
			$content_width = $widths['content_width'];
			$post_width = $widths['post_width'];
			$sidebar_width = $widths['sidebar_width'];
			$panel_width = $widths['panel_width'];
			$font_size = $typography['body']['font_size'];
			$line_height = $typography['body']['line_height'];
			$fonts = $design->fonts( $values );
			$bold = $fonts['body']['bold'];

			ob_start();
			include $file;

			return ob_get_clean();
		};

		return $render->call( $this->css );
	}

	public function test_canonical_templates_include_registered_dropins_and_child_styles_last() {
		md_test_set_child_theme( true );
		md_test_set_settings( array(
			'settings' => array(
				'css' => array( 'child' => true )
			)
		) );
		md_test_set_filter( 'md_dropins_css_templates', array(
			'component' => array(
				'path' => '/tmp/component.php',
				'data' => array( 'color' => 'blue' )
			)
		) );

		$templates = $this->call( $this->css, 'css_templates' );

		$this->assertSame(
			array(
				'path' => '/tmp/component.php',
				'data' => array( 'color' => 'blue' )
			),
			$templates['component']
		);
		$this->assertSame(
			array( 'component', 'child', 'child_dynamic' ),
			array_slice( array_keys( $templates ), -3 )
		);
	}

	public function test_block_editor_uses_the_complete_canonical_list_then_its_adapter() {
		$templates = array(
			'style' => '/tmp/style.php',
			'component' => '/tmp/component.php',
			'child' => '/tmp/child.css'
		);

		$block = $this->call( $this->css, 'block_editor_css', array( $templates ) );

		$this->assertSame(
			array( 'style', 'component', 'child', 'block-editor' ),
			array_keys( $block )
		);
		$this->assertStringEndsWith( '/css/block-editor.php', $block['block-editor'] );
	}

	public function test_editor_stylesheets_compile_minified() {
		$css = $this->source( 'api/css.php' );

		foreach ( array( 'classic-editor', 'block-editor' ) as $file ) {
			$this->assertMatchesRegularExpression(
				"/'{$file}' => array\\(\\s*'path' => MD_DIR \\. 'compile\\/{$file}\\.css',\\s*'minify' => true,/",
				$css
			);
		}
	}

	public function test_helpers_expose_the_canonical_layout_contract() {
		$helpers = $this->source( 'css/helpers.php' );

		foreach ( array(
			'.wrap',
			'.justify-start',
			'.justify-center',
			'.justify-end',
			'.justify-between',
			'.items-start',
			'.items-center',
			'.items-end',
			'.items-stretch',
			'.self-center',
			'.column-mobile',
			'.reverse-mobile',
			'.wrap-mobile',
			'.justify-center-mobile',
			'.items-center-mobile',
			'.items-stretch-mobile',
			'.width-full-mobile',
			'.text-center-mobile'
		) as $class )
			$this->assertStringContainsString( $class, $helpers );

		$this->assertStringNotContainsString( '.fl-center', $helpers );
		$this->assertStringNotContainsString( '.center-mobile', $helpers );
		$this->assertStringContainsString( '.columns,', $helpers );
		$this->assertStringContainsString( '[class*="columns-"]', $helpers );
		$this->assertStringContainsString( '--md-columns: <?php echo $g; ?>;', $helpers );
		$this->assertStringContainsString( '--md-columns-mobile: 2;', $helpers );
		$this->assertStringContainsString( '--md-columns-template', $helpers );
		$this->assertStringContainsString( 'minmax(0, 1fr)', $helpers );
		$this->assertStringContainsString( '.columns-fluid-$g', $helpers );
		$this->assertStringContainsString( '$g = 2; $g <= 6', $helpers );
		$this->assertStringContainsString( 'var(--md-$size)', $helpers );
	}

	public function test_helper_effects_use_shared_tokens_and_respect_reduced_motion() {
		$helpers = $this->source( 'css/helpers.php' );

		$this->assertStringContainsString( '.shadow, .wp-block-image.shadow img { box-shadow: var(--md-box-shadow); }', $helpers );
		$this->assertStringContainsString( '.shadow-grow:hover { box-shadow: var(--md-box-shadow-medium); }', $helpers );
		$this->assertStringContainsString( '.wp-block-image.radius img { border-radius: var(--md-border-radius); }', $helpers );
		$this->assertStringContainsString( '@media (prefers-reduced-motion: reduce)', $helpers );
	}

	public function test_theme_json_presets_have_matching_portable_css_fallbacks() {
		$json = ( new md_theme_json )->build();
		$css = $this->render_variables();

		$this->assertStringContainsString( ':where(:root)', $css );

		foreach ( $json['settings']['spacing']['spacingSizes'] as $preset )
			$this->assertStringContainsString(
				'--wp--preset--spacing--' . $preset['slug'] . ': ' . $preset['size'] . ';',
				$css
			);

		foreach ( $json['settings']['border']['radiusSizes'] as $preset )
			$this->assertStringContainsString(
				'--wp--preset--border-radius--' . $preset['slug'] . ': ' . $preset['size'] . ';',
				$css
			);

		foreach ( $json['settings']['shadow']['presets'] as $preset )
			$this->assertStringContainsString(
				'--wp--preset--shadow--' . $preset['slug'] . ': ' . $preset['shadow'] . ';',
				$css
			);

		foreach ( $json['settings']['color']['palette'] as $preset )
			$this->assertStringContainsString(
				'--wp--preset--color--' . $preset['slug'] . ': ' . $preset['color'] . ';',
				$css
			);
	}

	public function test_shared_presets_keep_md_variables_as_the_internal_theme_api() {
		$variables = $this->source( 'css/--vars.php' );
		$shared = array(
			'small', 'third', 'half', 'single', 'mid', 'double', 'triple', 'quad',
			'border-radius', 'box-shadow-small', 'box-shadow-medium', 'box-shadow-large', 'box-shadow-huge',
			'color-background', 'color-surface', 'color-primary', 'color-secondary', 'color-tertiary',
			'color-border', 'color-highlight', 'color-text', 'color-text-secondary', 'color-white'
		);

		foreach ( $shared as $token )
			$this->assertStringContainsString( '--md-' . $token . ':', $variables );

		foreach ( array( 'single', 'mid', 'double', 'triple', 'quad' ) as $token )
			$this->assertStringContainsString( '--md-' . $token . '-x:', $variables );

		$this->assertStringContainsString( '--md-box-shadow:', $variables );
		foreach ( array(
			'site-background', 'site-text', 'site-text-muted', 'site-text-contrast', 'site-links',
			'text', 'text-muted', 'links', 'links-muted', 'headlines', 'headline-links', 'border',
			'action-primary', 'action-primary-text', 'action-secondary', 'action-secondary-text',
			'color-danger', 'color-warning',
			'header-background', 'header-submenu-background',
			'content-main-background', 'content-border', 'content-box-background', 'content-box-border',
			'content-box-text', 'content-box-text-muted', 'content-box-links',
			'sidebar-background', 'panel-background', 'footer-background'
		) as $token )
			$this->assertStringContainsString( '--md-' . $token . ':', $variables );
	}

	public function test_status_action_colors_compile_into_shared_state_tokens() {
		md_test_set_settings( array(
			'colors' => array(
				'actions' => array(
					'status' => array(
						'danger_color' => '#B91C1C',
						'warning_color' => '#D97706'
					)
				)
			)
		) );

		$variables = $this->render_variables();

		$this->assertStringContainsString( '--md-color-danger: #B91C1C;', $variables );
		$this->assertStringContainsString( '--md-color-warning: #D97706;', $variables );
	}

	public function test_muted_links_use_the_active_context_colors() {
		$helpers = $this->source( 'css/helpers.php' );

		$this->assertStringContainsString( '.has-muted-color, .foot { color: var(--md-text-muted); }', $helpers );
		$this->assertStringContainsString( 'color: var(--md-links-muted);', $helpers );
		$this->assertStringNotContainsString( '.text-sec', $helpers );
	}

	public function test_byline_uses_muted_links_and_inherited_icon_color() {
		$title = $this->source( 'css/title.php' );

		$this->assertMatchesRegularExpression( '/\.byline a \{[^}]*color: var\(--md-links-muted\);/s', $title );
		$this->assertMatchesRegularExpression( '/\.byline \.circle-icon \{[^}]*color: inherit;/s', $title );
	}

	public function test_content_box_surfaces_establish_their_own_color_context() {
		$variables = $this->source( 'css/--vars.php' );
		$loop = $this->source( 'css/loop.php' );
		$blockquote = $this->source( 'css/format.php' );

		foreach ( array( 'text', 'text-muted', 'links', 'links-muted', 'headlines', 'headline-links' ) as $token )
			$this->assertStringContainsString( "--md-{$token}: var(--md-site-{$token});", $variables );
		$this->assertStringContainsString( '--md-border: var(--md-content-border);', $variables );

		foreach ( array( $loop, $blockquote ) as $surface ) {
			$this->assertStringContainsString( '--md-text: var(--md-content-box-text);', $surface );
			$this->assertStringContainsString( '--md-text-muted: var(--md-content-box-text-muted);', $surface );
			$this->assertStringContainsString( '--md-links: var(--md-content-box-links);', $surface );
			$this->assertStringContainsString( '--md-links-muted: var(--md-content-box-text-muted);', $surface );
			$this->assertStringContainsString( '--md-headlines: var(--md-content-box-text);', $surface );
			$this->assertStringContainsString( '--md-headline-links: var(--md-content-box-text);', $surface );
			$this->assertStringContainsString( '--md-border: var(--md-content-box-border);', $surface );
		}

		$this->assertStringContainsString( 'color: var(--md-text);', $loop );
		$this->assertStringContainsString( 'color: var(--md-text-muted);', $blockquote );
	}

	public function test_classic_editor_stays_curated_and_auto_detects_dropin_opt_ins() {
		md_test_set_dropins( array( 'example' ) );
		md_test_set_filter( 'md_dropins_css_templates', array(
			'component' => '/tmp/component.php'
		) );

		$classic = $this->call( $this->css, 'classic_editor_css' );

		$this->assertSame(
			array( 'classic-editor', 'example-classic-editor' ),
			array_keys( $classic )
		);
		$this->assertArrayNotHasKey( 'component', $classic );
	}

	public function test_critical_pruning_only_changes_the_frontend_stylesheet() {
		md_test_set_settings( array(
			'settings' => array(
				'css' => array( 'critical' => true )
			)
		) );
		$templates = array(
			'style' => '/tmp/style.php',
			'format' => '/tmp/format.php',
			'component' => '/tmp/component.php'
		);

		$frontend = $this->css->style_css( $templates );
		$block = $this->call( $this->css, 'block_editor_css', array( $templates ) );

		$this->assertArrayNotHasKey( 'style', $frontend );
		$this->assertArrayHasKey( 'format', $frontend );
		$this->assertArrayHasKey( 'component', $frontend );
		$this->assertArrayHasKey( 'style', $block );
		$this->assertArrayHasKey( 'component', $block );
	}

	public function test_render_applies_file_replacements_before_cleanup() {
		$css = new class extends md_css {
			public function __construct() {}

			public function templates( $file ) {
				echo "<style type=\"text/css\">\n.format { color: red; }\n</style>";
			}
		};
		$css->files = array(
			'classic-editor' => array(
				'replace' => array( '.format' => '.mce-content-body' )
			)
		);

		$rendered = $this->call( $css, 'render', array( 'classic-editor' ) );

		$this->assertStringContainsString( '.mce-content-body { color: red; }', $rendered );
		$this->assertStringNotContainsString( '.format', $rendered );
		$this->assertSame( '.mce-content-body { color: red; }', $css->clean( $rendered ) );
		$this->assertSame( '.mce-content-body{color:red;}', $css->minify( 'classic-editor' ) );
	}

	public function test_block_styles_use_a_cacheable_settings_import() {
		$theme = $this->source( 'marketers-delight.php' );

		$this->assertStringContainsString(
			"add_filter( 'block_editor_settings_all', array( \$this, 'block_editor_styles' ) );",
			$theme
		);
		$this->assertStringContainsString( '@import url("', $theme );
		$this->assertStringContainsString( 'set_url_scheme( MD_URL . $file )', $theme );
		$this->assertStringContainsString( 'esc_url_raw( $url )', $theme );
		$this->assertStringNotContainsString( 'file_get_contents( $path )', $theme );
		$this->assertStringNotContainsString( '__unstableResolvedAssets', $theme );
	}

	public function test_block_layout_classes_are_available_before_iframe_renders() {
		$admin = $this->source( 'admin/admin.php' );
		$block = $this->source( 'css/block-editor.php' );
		$script = $this->source( 'admin/js/editors.js' );
		$admin_script = $this->source( 'admin/js/admin.js' );

		$this->assertStringContainsString(
			"add_action( 'enqueue_block_assets', array( \$this, 'enqueue_block_editor_layout' ) );",
			$admin
		);
		$this->assertStringContainsString(
			"'document.documentElement.classList.add(' . wp_json_encode( \$layout )",
			$admin
		);
		$this->assertStringContainsString(
			"blockEditorClasses = [ 'md-builder', 'expanded', 'compact' ]",
			$script
		);
		$this->assertStringContainsString( "wp_enqueue_script( 'md-editors'", $admin );
		$this->assertStringContainsString( "'isBlockEditor' => (bool) \$screen->is_block_editor()", $admin );
		$this->assertStringNotContainsString( 'enqueue_block_editor_assets', $admin );
		$this->assertStringContainsString( "'is-' . \$this->editor_content_style( \$post_id ) . '-style'", $admin );
		$this->assertStringContainsString( "add_filter( 'tiny_mce_before_init', array( \$this, 'classic_editor_layout' ) );", $admin );
		$this->assertStringContainsString( "'marketers_delight_layout_content_style'", $script );
		$this->assertStringContainsString( "'is-' + style + '-style'", $script );
		$this->assertStringContainsString( 'editorContext.inheritedContentStyle', $script );
		$this->assertStringContainsString( 'iframe.contentDocument.documentElement', $script );
		$this->assertStringContainsString( "'tinymce-editor-setup.mdEditorClasses'", $script );
		$this->assertStringContainsString( 'function getContentStyle( contentStyle, builderCheckbox )', $script );
		$this->assertStringContainsString( "return 'plain';", $script );
		$this->assertStringContainsString( "! \$exclude_single && md_post_meta( array( 'layout', 'content', 'builder' ), \$post_id )", $admin );
		$this->assertStringNotContainsString( 'classicEditorStyle', $admin_script );
		$this->assertStringContainsString( '.expanded .editor-styles-wrapper .edit-post-visual-editor__post-title-wrapper', $block );
		$this->assertStringContainsString( ':is(.expanded, .md-builder) .editor-styles-wrapper .wp-block-post-title', $block );
		$this->assertStringNotContainsString( 'block_editor_layout_styles', $admin );
		$this->assertStringNotContainsString( 'syncLayoutVariables', $script );
		$this->assertStringNotContainsString( '--md-editor-', $block );
	}

	public function test_nested_clone_groups_do_not_use_css_id_selectors() {
		$script = $this->source( 'admin/js/admin.js' );

		$this->assertStringContainsString(
			"groupID = \$( document.getElementById( 'md_group_' + group ) )",
			$script
		);
		$this->assertStringContainsString(
			"tags = e.find( '[for], [name], [id], [data-clone-group]' )",
			$script
		);
		$this->assertStringNotContainsString( "\$( '#md_group_' + group )", $script );
		$this->assertStringNotContainsString( "e.find( 'label, input, textarea, select' )", $script );
	}

	public function test_editor_surfaces_follow_existing_content_style_classes() {
		$theme = $this->source( 'marketers-delight.php' );
		$layout = $this->source( 'css/layout.php' );
		$loop = $this->source( 'css/loop.php' );
		$block = $this->source( 'css/block-editor.php' );
		$classic = $this->source( 'css/classic-editor.php' );

		$this->assertStringContainsString( "'is-' . \$loop['style'] . '-style'", $theme );
		$this->assertStringContainsString( 'background-color: var(--md-content-main-background);', $layout );
		$this->assertStringNotContainsString( '.is-box-style .main', $layout );
		$this->assertStringNotContainsString( '.main:has(.box-style.loop)', $loop );
		$this->assertStringContainsString( 'background-color: var(--md-content-main-background);', $block );
		$this->assertStringContainsString( 'background-color: var(--md-content-main-background);', $classic );
		$this->assertStringContainsString( '.is-box-style .editor-styles-wrapper {', $block );
		$this->assertStringContainsString( '.mce-content-body.is-box-style {', $classic );

		foreach ( array( $block, $classic ) as $editor ) {
			$this->assertStringContainsString( '--md-text: var(--md-content-box-text);', $editor );
			$this->assertStringContainsString( '--md-border: var(--md-content-box-border);', $editor );
			$this->assertStringContainsString( 'background-color: var(--md-content-box-background);', $editor );
			$this->assertStringContainsString( 'color: var(--md-text);', $editor );
		}
		$this->assertStringNotContainsString( "\$colors['content']['body_color']", $block );
		$this->assertStringNotContainsString( "\$colors['content']['body_color']", $classic );
	}

	public function test_alignment_breakouts_keep_theme_selectors_and_leave_editor_widths_to_theme_json() {
		$alignments = $this->source( 'css/alignments.php' );
		$block = $this->source( 'css/block-editor.php' );

		$this->assertStringContainsString( '.expanded .alignfull', $alignments );
		$this->assertStringContainsString( '.expanded .alignwide', $alignments );
		$this->assertStringContainsString( '.compact :is(.alignwide, .alignfull)', $alignments );
		$this->assertStringNotContainsString( '.expanded.editor-styles-wrapper .wp-block-group .alignwide', $block );
		$this->assertStringNotContainsString( '--md-editor-wide-width', $block );
		$this->assertStringNotContainsString( '--md-editor-full-width', $block );
	}

	public function test_heading_tokens_are_fluid_and_heading_rules_consume_them() {
		$variables = $this->source( 'css/--vars.php' );
		$headings = $this->source( 'css/headings.php' );

		foreach ( array( 'huge', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $heading ) {
			$this->assertStringContainsString(
				'--md-' . $heading . ': <?php echo $this->fluid(',
				$variables
			);
			$this->assertStringContainsString(
				'--md-' . $heading . '-line-height: <?php echo $this->fluid(',
				$variables
			);
		}

		$this->assertStringContainsString( 'font-size: var(--md-{$attribute});', $headings );
		$this->assertStringContainsString( 'line-height: var(--md-{$attribute}-line-height);', $headings );
		$this->assertStringNotContainsString( '$this->fluid(', $headings );
		$this->assertStringNotContainsString( '$file', $headings );
	}

	public function test_classic_resolves_desktop_typography_above_the_admin_breakpoint() {
		$classic = $this->source( 'css/classic-editor.php' );
		$block = $this->source( 'css/block-editor.php' );

		$this->assertStringContainsString( 'font-size: var(--md-font-size);', $classic );
		$this->assertStringContainsString( 'line-height: var(--md-line-height);', $classic );
		$this->assertStringContainsString( '@media (min-width: 783px)', $classic );
		$this->assertStringContainsString(
			"--md-h2: <?php echo \$typography['h2']['font_size']['desktop']; ?>px;",
			$classic
		);
		$this->assertStringNotContainsString( '--md-single-x:', $classic );

		$this->assertStringContainsString( 'font-size: var(--md-font-size);', $block );
		$this->assertStringContainsString( 'line-height: var(--md-line-height);', $block );
		$this->assertStringNotContainsString( '--md-h2:', $block );
	}

}
