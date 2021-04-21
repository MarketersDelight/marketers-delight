<?php
/**
 * Unfortunately this class has to exist to clean up old WordPress
 * features and unwanted stylesheets and scripts in the <head>.
 *
 * @since 4.9.4
 */

class md_optimize_wp {

	/**
	 * Run class methods and actions.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		// Random WP junk
		if ( ! md_setting( array( 'settings', 'head', 'optimize' ) ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'wp_generator' );
			remove_action( 'wp_head', 'wlwmanifest_link' );
			remove_action( 'wp_head', 'rsd_link' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
			add_filter( 'emoji_svg_url', '__return_false' );
			add_filter( 'the_generator', '__return_false' );
		}

		// REST API
		if ( md_setting( array( 'settings', 'head', 'wpjson' ) ) ) {
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		}

		// oEmbed
		if ( md_setting( array( 'settings', 'head', 'oembed' ) ) ) {
			add_filter( 'embed_oembed_discover', '__return_false' );
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'rewrite_rules_array', array( $this, 'disable_embed_rewrites' ) );
		}

		 // Removes inline CSS Subtitles plugin prints to frontend
		if ( class_exists( 'Subtitles' ) &&  method_exists( 'Subtitles', 'subtitle_styling' ) )
		    remove_action( 'wp_head', array( Subtitles::getInstance(), 'subtitle_styling' ) );

		// Re-add RSS link
		add_action( 'wp_head', array( $this, 'add_rss_link' ) );
	}

	/**
	 * Remove oEmbed rewrite rules if enabled.
	 *
	 * @since 1.0
	 */

	public function disable_embed_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite )
			if ( false !== strpos( $rewrite, 'embed=true' ) )
				unset( $rules[ $rule ] );
		return $rules;
	}

	/**
	 * Manually add a formatted version of the site's main RSS
	 * feed to the <head>.
	 *
	 * @since 4.8
	 */

	public function add_rss_link() {
		echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo( 'sitename' ) . ' Feed" href="' . get_bloginfo( 'rss2_url' ) . '">';
	}

}