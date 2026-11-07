<?php
/**
 * Saves MD forms. Every field is named md[scope][group][...], so each group
 * goes to md_save::apply() for the scope it names.
 *
 * It also draws what forms need to save: the hidden fields, the sticky save
 * bar and the fingerprint live saving compares.
 *
 * @since 6.2
 */

class md_save_settings {

	private $save;

	/**
	 * Whether this screen's save bar has printed.
	 *
	 * @since 6.2
	 */

	private static $bar = false;

	public function __construct( $save ) {
		$this->save = $save;
	}

	/**
	 * Save a submitted screen on its load-{$page_hook} action.
	 *
	 * @since 6.2
	 */

	public function load() {
		if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' || empty( $_POST['md_save_settings'] ) )
			return;

		check_admin_referer( 'md_save_settings', 'md_save_nonce' );

		foreach ( $this->save_screen() as $result )
			if ( is_wp_error( $result ) )
				add_settings_error( 'md', $result->get_error_code(), $result->get_error_message(), 'error' );

		$this->save->after_save();

		if ( ! count( get_settings_errors() ) )
			add_settings_error( 'general', 'settings_updated', __( 'Settings saved.', 'md' ), 'success' );

		set_transient( 'settings_errors', get_settings_errors(), 30 );
		wp_safe_redirect( add_query_arg( 'settings-updated', 'true', self::url() ) );
		exit;
	}

	/**
	 * Save what a settings screen posted, each group to the scope it names.
	 * Groups without registered fields, ex: Integrations, are skipped.
	 *
	 * @since 6.2
	 */

	private function save_screen() {
		$admin_pages = md_register( 'admin_pages' );
		$posted = isset( $_POST['md'] ) && is_array( $_POST['md'] ) ? wp_unslash( $_POST['md'] ) : array();
		$results = array();

		foreach ( $posted as $scope => $groups ) {
			$parsed = md_scope::parse( $scope );

			if ( is_wp_error( $parsed ) || ! in_array( $parsed->type, array( 'site', 'post_type', 'taxonomy' ), true ) || ! is_array( $groups ) )
				continue;

			// Each option saves once, with every group posted for it

			$options = array();

			foreach ( $groups as $group => $values )
				if ( ! empty( $admin_pages[$group]['fields'] ) )
					$options[isset( $admin_pages[$group]['_option'] ) ? $admin_pages[$group]['_option'] : 'marketers_delight'][$group] = $values;

			foreach ( $options as $values )
				$results[] = $this->save->apply( $scope, array_keys( $values ), $values, array( 'compile' => false ) );
		}

		return $results;
	}

	/**
	 * Save custom post meta fields.
	 *
	 * @since 4.0
	 */

	public function meta_save( $post_id, $post ) {
		$option = 'marketers_delight';

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! isset( $_POST["{$option}_nonce"] ) || ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) )
			return $post_id;

		if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_post, $post_id ) )
			return $post_id;

		if ( ! isset( $_POST['md']['post'] ) )
			return $post_id;

		// The form belongs to the post being edited, not to other posts saved during its request (like Stream activity)

		if ( isset( $_POST['post_ID'] ) && absint( $_POST['post_ID'] ) !== absint( $post_id ) )
			return $post_id;

		$groups = $this->form_groups( 'meta_boxes', 'post' );

		$this->save->apply( "post:$post_id", array_keys( $groups ), $groups );
	}

	/**
	 * Saves term meta fields.
	 *
	 * @since 4.3.5
	 */

	public function term_save( $term_id ) {
		$option = 'marketers_delight';

		if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_term', $term_id ) )
			return;

		if ( ! isset( $_POST['md']['term'] ) || ! isset( $_POST["{$option}_nonce"] ) || ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) )
			return;

		$groups = $this->form_groups( 'terms', 'term' );

		$this->save->apply( "term:$term_id", array_keys( $groups ), $groups );
	}

	/**
	 * Saves user meta fields.
	 *
	 * @since 5.3.1
	 */

	public function user_meta_save( $user_id, $old_meta ) {
		$option = 'marketers_delight';

		if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_user', $user_id ) )
			return;

		if ( ! isset( $_POST['md']['user'] ) || empty( $_POST["{$option}_nonce"] ) || ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) )
			return;

		$groups = $this->form_groups( 'user_meta', 'user' );

		$this->save->apply( "user:$user_id", array_keys( $groups ), $groups );
	}

	/**
	 * The registered groups a post, term or user form submitted under
	 * md[$scope], ex: the meta boxes on a post. Returns the unslashed
	 * values keyed by group.
	 *
	 * @since 6.2
	 */

	private function form_groups( $settings, $scope ) {
		$registered = md_register( $settings );
		$posted = isset( $_POST['md'][$scope] ) && is_array( $_POST['md'][$scope] ) ? wp_unslash( $_POST['md'][$scope] ) : array();
		$groups = array();

		foreach ( $posted as $group => $values )
			if ( ! empty( $registered[$group]['fields'] ) )
				$groups[$group] = $values;

		return $groups;
	}

	/**
	 * Hidden fields every MD settings form submits.
	 *
	 * @since 6.2
	 */

	public static function fields() {
		echo '<input type="hidden" name="md_save_settings" value="1" />';
		wp_nonce_field( 'md_save_settings', 'md_save_nonce', false );
	}

	/**
	 * The screen's URL without the notice from the last save.
	 *
	 * @since 6.2
	 */

	public static function url() {
		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

		return remove_query_arg( 'settings-updated', $request );
	}

	/**
	 * The sticky save bar: a screen's title or sub-tabs on the left, status and
	 * Save on the right. Headers print it in place of their tabs row; other
	 * screens get it from close_form().
	 *
	 * @since 6.2
	 */

	public static function bar( $tabs = '', $title = '' ) {
		$actions = self::actions();
		$left = $title ? '<h1 class="md-save-bar-title">' . esc_html( $title ) . '</h1>' : $tabs;

		return $left || $actions ? '<div class="md-save-bar md-content-wrap-med">' . $left . $actions . '</div>' : '';
	}

	/**
	 * The screen's registered name, ex: Stream Settings.
	 *
	 * @since 6.2
	 */

	private static function name() {
		$admin_pages = md_register( 'admin_pages' );
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
		$screen = md_clean_id( $tab ? $tab : $page );

		return isset( $admin_pages[$screen]['name'] ) ? $admin_pages[$screen]['name'] : '';
	}

	/**
	 * The save bar's Save button and status, on screens with settings to
	 * save. Drawn in the clean state the screen loads in, so nothing
	 * changes when the live save script starts.
	 *
	 * @since 6.2
	 */

	public static function actions() {
		$admin_pages = md_register( 'admin_pages' );
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
		$screen = md_clean_id( $tab ? $tab : $page );

		self::$bar = true;

		if ( empty( $admin_pages[$screen]['fields'] ) )
			return '';

		return
			'<div class="md-save-actions">'.
			'<span class="md-save-status is-clean" role="status" aria-live="polite" title="' . esc_attr__( 'All changes saved', 'md' ) . '"><span class="md-save-icon"></span><span class="md-save-text">' . esc_html__( 'All changes saved', 'md' ) . '</span></span>'.
			'<input type="submit" name="submit" class="button button-primary md-save-button" value="' . esc_attr__( 'Save', 'md' ) . '" title="' . esc_attr__( 'Save (⌘S)', 'md' ) . '" disabled />'.
			'</div>';
	}

	/**
	 * Finish a drawn screen's form: add the fingerprint live saving
	 * compares, and the save bar if no header printed it. Both go at the
	 * top of the form, so the screen's last box stays its last child.
	 *
	 * @since 6.2
	 */

	public static function close_form( $html ) {
		if ( ! preg_match( '/<form id="md-form"[^>]*>/', $html, $form, PREG_OFFSET_CAPTURE ) )
			return $html;

		$top = '<input type="hidden" name="md_fingerprint" value="' . esc_attr( self::fingerprint( $html ) ) . '" />';

		if ( ! self::$bar )
			$top .= self::bar( '', self::name() );

		return substr_replace( $html, $top, $form[0][1] + strlen( $form[0][0] ), 0 );
	}

	/**
	 * Fingerprint a screen without its values: fields, choices, links and the
	 * admin menu. A live save reloads when the screen drawn after saving differs.
	 *
	 * @since 6.2
	 */

	public static function fingerprint( $html ) {
		global $menu, $submenu;

		$tags = new WP_HTML_Tag_Processor( $html );
		$parts = array();

		while ( $tags->next_tag() ) {
			$tag = $tags->get_tag();
			$name = (string) $tags->get_attribute( 'name' );

			if ( $tag === 'INPUT' ) {
				$type = strtolower( (string) $tags->get_attribute( 'type' ) );
				$parts[] = "input $type $name" . ( in_array( $type, array( 'checkbox', 'radio' ), true ) ? '=' . $tags->get_attribute( 'value' ) : '' );
			}
			elseif ( in_array( $tag, array( 'SELECT', 'TEXTAREA', 'BUTTON' ), true ) )
				$parts[] = strtolower( $tag ) . " $name";
			elseif ( $tag === 'OPTION' )
				$parts[] = 'option ' . $tags->get_attribute( 'value' );
			elseif ( $tag === 'A' )
				$parts[] = 'a ' . self::fingerprint_url( (string) $tags->get_attribute( 'href' ) );
		}

		$menu_items = array();

		foreach ( (array) $menu as $item )
			$menu_items[] = 'menu ' . self::fingerprint_url( isset( $item[2] ) ? $item[2] : '' );

		foreach ( (array) $submenu as $parent => $items )
			foreach ( (array) $items as $item )
				$menu_items[] = "submenu $parent " . self::fingerprint_url( isset( $item[2] ) ? $item[2] : '' );

		// Menu items count, their order follows Drop-in load order

		sort( $menu_items );

		return md5( implode( "\n", array_merge( $parts, $menu_items ) ) );
	}

	/**
	 * Links without what changes on every load: nonces and the
	 * current page passed along to return to.
	 *
	 * @since 6.2
	 */

	private static function fingerprint_url( $url ) {
		$url = remove_query_arg( array( 'return', 'settings-updated' ), html_entity_decode( $url ) );

		return preg_replace( '/([?&][^=&#]*nonce[^=&#]*)=[^&#]*/i', '$1', $url );
	}

}
