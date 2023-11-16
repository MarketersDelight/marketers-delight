<?php
/**
 * This class generates MD JS files and other actions.
 *
 * @since 5.5
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_js {

	public $files;

	/**
	 * Set properties.
	 *
	 * @since 5.5
	 */

	public function __construct() {
		$this->files = $this->files();
	}

	/**
	 * A list of JS Templates to generate from all known files.
	 *
	 * @since 5.5
	 */

	public function files() {
		return array_merge( array(
			'script' => array(
				'templates' => $this->script_js(),
				'path' => MD_DIR . 'scripts.js'
			)
		), apply_filters( 'md_js_files', array() ) );
	}

	/**
	 * Load a list of JS template files to load to scripts.js file.
	 *
	 * @since 5.5
	 */

	public function script_js() {
		$child_js = locate_template( 'scripts.php' );
		$templates = array(
			'scripts' => ! empty( $child_js ) ? $child_js :  MD_DIR . 'lib/js.php'
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
		$inline = md_setting( array( 'settings', 'js', 'inline' ) );
		foreach ( $this->files as $file => $fields ) {
			if ( empty( $inline ) ) {
				if ( isset( $delete ) )
					delete_option( "marketers_delight_{$file}_js" );
				$this->generate( $file );
			}
			else
				$this->save( $file );
		}
		wp_cache_flush();
	}

	/**
	 * Render JS Templates to static JavaScript.
	 *
	 * @since 5.5
	 */

	public function generate( $file ) {
		$path = $this->files[$file]['path'];
		if ( file_exists( $path ) ) {
			ob_start();
			$js = '';
			$this->templates( $file );
			$js .= "window.MD = {\n";
			$js .= ob_get_clean();
			$js .= "\n}";
			$js = $this->clean( $js );
			file_put_contents( $path, $js );
		}
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
			include( $path );
			echo "\n\n";
		}
	}

}
