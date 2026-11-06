<?php
/**
 * This class generates MD JS files and other actions.
 *
 * @since 5.5
 */

class md_js {

	public $files;
	private $compiled;

	/**
	 * Set properties.
	 *
	 * @since 5.5
	 */

	public function __construct() {
		$this->compiled = new md_compiled_assets;
		$this->files = $this->files();
	}

	/**
	 * A list of JS Templates to generate from all known files.
	 *
	 * @since 5.5
	 */

	public function files() {
		$files = array_merge( array(
			'script' => array(
				'templates' => $this->script_js(),
				'output' => 'scripts.js'
			)
		), apply_filters( 'md_js_files', array() ) );

		// Preserve the existing filtered file contract while constraining every
		// generated asset to a flat filename in the MD uploads directory.

		foreach ( $files as $file => $fields ) {
			if ( empty( $fields['output'] ) && ! empty( $fields['path'] ) )
				$files[$file]['output'] = basename( $fields['path'] );
		}

		return $files;
	}

	/**
	 * Load a list of JS template files to load to scripts.js file.
	 *
	 * @since 5.5
	 */

	public function script_js() {
		$child_js = locate_template( 'scripts.php' );
		$templates = array(
			'scripts' => ! empty( $child_js ) ? $child_js :  MD_DIR . 'scripts.php'
		);
		$templates = apply_filters( 'md_js_templates', $templates );

		return $templates;
	}

	/**
	 * Compile JS in the user designated manner on call.
	 *
	 * @since 5.5
	 */

	public function compile( $delete = null ) {
		foreach ( $this->files as $file => $fields ) {
			$js = $this->generate( $file );
			$result = $this->compiled->write( $fields['output'], $js );

			if ( is_wp_error( $result ) )
				return $result;
		}

		return true;
	}

	/**
	 * Render JS Templates to static JavaScript.
	 *
	 * @since 5.5
	 */

	public function generate( $file ) {
		ob_start();
		$this->templates( $file );

		$js = "window.MD = {\n";
		$js .= ob_get_clean();
		$js .= "\n}";

		return $this->clean( $js );
	}

	/**
	 * Clean up CSS before saving.
	 *
	 * @since 5.5
	 */

	public function clean( $js ) {
		$js = str_replace( array( '<script type="text/javascript">', '<script type=\'text/javascript\'>', '<script>', '</script>' ), '', $js );
		$js = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $js );

		return trim( $js );
	}

	/**
	 * Get JS template.
	 *
	 * @since 5.5
	 */

	public function templates( $file ) {
		foreach ( $this->files[$file]['templates'] as $template => $path ) {
			if ( ! file_exists( $path ) ) continue;
			include $path;
			echo "\n\n";
		}
	}

}
