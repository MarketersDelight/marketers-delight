<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 * The Drop-ins list, built on core's list table with the Plugins
 * screen's markup: views, search, bulk actions, row actions and update
 * rows.
 *
 * @since 6.2
 */

class md_dropins_list_table extends WP_List_Table {

	public $search = '';
	public $visible = 0;
	public $counts = array();
	public $updates = array();
	public $status = 'all';

	/**
	 * Set up the table.
	 *
	 * @since 6.2
	 */

	public function __construct() {
		parent::__construct( array(
			'plural' => 'dropins',
			'singular' => 'dropin',
			'screen' => 'md-dropins'
		) );

		$status = isset( $_REQUEST['dropin_status'] ) ? sanitize_key( $_REQUEST['dropin_status'] ) : 'all';

		$this->status = in_array( $status, array( 'all', 'active', 'inactive', 'upgrade', 'available', 'more' ), true ) ? $status : 'all';
	}

	/**
	 * Build the rows from each Drop-in's details and saved state.
	 *
	 * @since 6.2
	 */

	public function prepare_items() {
		$state = md_dropins_setting();
		$search = isset( $_REQUEST['s'] ) ? strtolower( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ) : '';

		$this->search = $search;
		$this->updates = md_dropin_updates();

		$available = md_available_dropins();
		$more = md_available_dropins( 'upgrade' );

		$this->counts = array( 'all' => 0, 'active' => 0, 'inactive' => 0, 'upgrade' => 0, 'available' => count( $available ), 'more' => count( $more ) );

		$items = array();

		if ( $this->status === 'available' || $this->status === 'more' ) {
			foreach ( $this->status === 'available' ? $available : $more as $slug => $dropin ) {
				$item = $this->remote_item( $slug, $dropin );

				$item['hidden'] = ! $this->matches( $item, $search );
				$items[] = $item;
			}
		}

		foreach ( md_get_dropin_details() as $slug => $details ) {
			$item = $details;
			$item['remote'] = false;
			$item['active'] = ! empty( $state['installed'][$slug]['status']['enable'] );
			$item['requirements'] = md_check_dropin_requirements( $details );
			$item['update'] = isset( $this->updates[$details['file']] ) ? $this->updates[$details['file']] : array();

			$this->counts['all']++;
			$this->counts[$item['active'] ? 'active' : 'inactive']++;

			if ( $item['update'] )
				$this->counts['upgrade']++;

			if ( in_array( $this->status, array( 'available', 'more' ), true ) )
				continue;

			$item['hidden'] = ! $this->in_view( $item, $this->status ) || ! $this->matches( $item, $search );
			$items[] = $item;
		}

		$this->items = $items;

		foreach ( $items as $item )
			$this->visible += $item['hidden'] ? 0 : 1;

		$this->set_pagination_args( array(
			'total_items' => $this->visible,
			'per_page' => max( 1, count( $items ) )
		) );
	}

	/**
	 * Whether an installed Drop-in belongs in a view.
	 *
	 * @since 6.2
	 */

	private function in_view( $item, $status ) {
		if ( $status === 'active' )
			return $item['active'];

		if ( $status === 'inactive' )
			return ! $item['active'];

		if ( $status === 'upgrade' )
			return (bool) $item['update'];

		return true;
	}

	/**
	 * Whether a Drop-in matches the search, by name, description or slug.
	 *
	 * @since 6.2
	 */

	private function matches( $item, $search ) {
		return $search === '' || strpos( $this->search_text( $item ), $search ) !== false;
	}

	/**
	 * The text a search looks through.
	 *
	 * @since 6.2
	 */

	private function search_text( $item ) {
		return strtolower( $item['Name'] . ' ' . $item['Description'] . ' ' . $item['slug'] );
	}

	/**
	 * Whether a view's rows are all on the page, so switching to it
	 * filters instead of reloading.
	 *
	 * @since 6.2
	 */

	public function is_local_view( $status ) {
		return in_array( $status, array( 'all', 'active', 'inactive', 'upgrade' ), true );
	}

	/**
	 * A row for a Drop-in that isn't installed, from MD.com's details.
	 *
	 * @since 6.2
	 */

	private function remote_item( $slug, $dropin ) {
		return array(
			'remote' => true,
			'slug' => $slug,
			'status' => $dropin['status'],
			'Name' => isset( $dropin['name'] ) ? $dropin['name'] : $slug,
			'Version' => isset( $dropin['version'] ) ? $dropin['version'] : '',
			'Description' => isset( $dropin['description'] ) ? $dropin['description'] : '',
			'Icon' => isset( $dropin['icon'] ) ? $dropin['icon'] : '',
			'Colors' => isset( $dropin['colors'] ) ? $dropin['colors'] : '',
			'plan' => isset( $dropin['plan'] ) ? $dropin['plan'] : '',
			'upgrade_url' => isset( $dropin['upgrade_url'] ) ? $dropin['upgrade_url'] : MD_THEME_UPDATER_URL . '/account/',
			'active' => false,
			'update' => array(),
			'requirements' => true,
			'hidden' => false
		);
	}

	/**
	 * Columns, matched to core's Plugins table.
	 *
	 * @since 6.2
	 */

	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __( 'Drop-in', 'md' ),
			'description' => __( 'Description', 'md' )
		);

		if ( $this->status === 'more' )
			unset( $columns['cb'] );

		return $columns;
	}

	/**
	 * The table's classes. Not core's `plugins`: the rows are styled as
	 * Drop-ins, with their own tiles and colors.
	 *
	 * @since 6.2
	 */

	protected function get_table_classes() {
		return array( 'widefat', 'md-dropins-table' );
	}

	/**
	 * All / Active / Inactive / Update Available, then Available (licensed,
	 * not installed) and Pro Drop-ins (a higher plan adds them), as links
	 * in the header row. The first four filter in place; the last two load
	 * their rows from MD.com's details.
	 *
	 * @since 6.2
	 */

	public function bar_views() {
		$labels = array(
			'all' => __( 'All', 'md' ),
			'active' => __( 'Active', 'md' ),
			'inactive' => __( 'Inactive', 'md' ),
			'upgrade' => __( 'Update Available', 'md' ),
			'available' => __( 'Available', 'md' ),
			'more' => __( 'Pro Drop-ins', 'md' )
		);
		$output = '<nav class="md-dropins-views" aria-label="' . esc_attr__( 'Filter Drop-ins', 'md' ) . '">';

		foreach ( $labels as $status => $label ) {
			if ( $status !== 'all' && empty( $this->counts[$status] ) )
				continue;

			$output .= sprintf(
				'<a href="%s" class="md-submenu-item%s" data-view="%s"%s%s>%s <span class="count">(%s)</span></a>',
				esc_url( md_dropins::url( $status === 'all' ? array() : array( 'dropin_status' => $status ) ) ),
				$status === $this->status ? ' md-submenu-active' : '',
				esc_attr( $status ),
				$this->is_local_view( $status ) ? ' data-local="1"' : '',
				$status === $this->status ? ' aria-current="page"' : '',
				esc_html( $label ),
				number_format_i18n( $this->counts[$status] )
			);
		}

		return $output . '</nav>';
	}

	/**
	 * Activate, Deactivate, Update and Delete for checked rows.
	 *
	 * @since 6.2
	 */

	protected function get_bulk_actions() {
		$actions = array();

		if ( $this->status === 'available' )
			return current_user_can( 'install_plugins' ) ? array( 'install-selected' => __( 'Install', 'md' ) ) : array();

		if ( $this->status === 'more' )
			return array();

		if ( current_user_can( 'activate_plugins' ) ) {
			$actions['activate-selected'] = __( 'Activate', 'md' );
			$actions['deactivate-selected'] = __( 'Deactivate', 'md' );
		}

		if ( current_user_can( 'update_plugins' ) && $this->counts['upgrade'] )
			$actions['update-selected'] = __( 'Update', 'md' );

		if ( current_user_can( 'delete_plugins' ) )
			$actions['delete-selected'] = __( 'Delete', 'md' );

		return $actions;
	}

	/**
	 * The rows, then a no-match row the filters show when every row is
	 * hidden.
	 *
	 * @since 6.2
	 */

	public function display_rows_or_placeholder() {
		if ( ! $this->has_items() ) {
			parent::display_rows_or_placeholder();

			return;
		}

		$this->display_rows();

		echo '<tr class="no-items md-dropins-empty"' . ( $this->visible ? ' hidden' : '' ) . '><td class="colspanchange" colspan="' . (int) $this->get_column_count() . '">' . esc_html__( 'No Drop-ins found.', 'md' ) . '</td></tr>';
	}

	/**
	 * Message when no Drop-ins match.
	 *
	 * @since 6.2
	 */

	public function no_items() {
		if ( ! empty( $_REQUEST['s'] ) )
			esc_html_e( 'No Drop-ins found for your search.', 'md' );
		elseif ( $this->status === 'available' )
			esc_html_e( 'Every Drop-in your license includes is installed.', 'md' );
		else
			esc_html_e( 'No Drop-ins found.', 'md' );
	}

	/**
	 * A row, plus core's update row under it when an update is waiting
	 * and core's incompatible notice when the Drop-in can't run here.
	 *
	 * @since 6.2
	 */

	public function single_row( $item ) {
		$classes = array( 'md-dropin-row', $item['active'] ? 'is-active' : 'is-inactive' );

		if ( $item['remote'] )
			$classes = array( 'md-dropin-row', $item['status'] === 'upgrade' ? 'is-locked' : 'is-available' );

		$accent = $this->accent( $item );

		if ( $item['update'] || is_wp_error( $item['requirements'] ) )
			$classes[] = 'has-notice';

		if ( $item['update'] )
			$classes[] = 'has-update';

		echo '<tr class="' . esc_attr( implode( ' ', $classes ) ) . '" data-slug="' . esc_attr( $item['slug'] ) . '" data-search="' . esc_attr( $this->search_text( $item ) ) . '"' . $accent . ( $item['hidden'] ? ' hidden' : '' ) . '>';
		$this->single_row_columns( $item );
		echo '</tr>';

		if ( $item['update'] )
			$this->update_row( $item );
		elseif ( is_wp_error( $item['requirements'] ) )
			$this->notice_row( $item, 'notice-error', $item['requirements']->get_error_message() );
	}

	/**
	 * Core's update row: a notice with Update now.
	 *
	 * @since 6.2
	 */

	private function update_row( $item ) {
		$message = sprintf( __( 'There is a new version of %1$s available: %2$s.', 'md' ), esc_html( $item['Name'] ), esc_html( $item['update']['new_version'] ) );

		if ( current_user_can( 'update_plugins' ) ) {
			$url = md_dropins::update_url( $item['slug'] );
			$message .= ' <a href="' . esc_url( $url ) . '" class="update-link">' . esc_html__( 'Update now', 'md' ) . '</a>';
		}

		$this->notice_row( $item, 'notice-warning', $message );
	}

	/**
	 * A full-width notice row under a Drop-in, in core's markup.
	 *
	 * @since 6.2
	 */

	private function notice_row( $item, $type, $message ) {
		$columns = count( $this->get_columns() );
		$accent = $this->accent( $item );

		echo '<tr class="md-dropin-notice-row ' . ( $item['active'] ? 'is-active' : 'is-inactive' ) . '"' . $accent . ( $item['hidden'] ? ' hidden' : '' ) . '><td colspan="' . (int) $columns . '" class="colspanchange">';
		echo '<div class="update-message notice inline ' . esc_attr( $type ) . ' notice-alt"><p>' . wp_kses_post( $message ) . '</p></div>';
		echo '</td></tr>';
	}

	/**
	 * Checkbox column. The label covers the whole cell, as on core's
	 * Plugins screen, so clicking anywhere in it ticks the box.
	 *
	 * @since 6.2
	 */

	protected function column_cb( $item ) {
		if ( $item['remote'] && $item['status'] === 'upgrade' )
			return '';

		return '<label class="label-covers-full-cell" for="checkbox_' . esc_attr( $item['slug'] ) . '"><span class="screen-reader-text">' . sprintf( esc_html__( 'Select %s', 'md' ), esc_html( $item['Name'] ) ) . '</span></label>'.
			'<input type="checkbox" name="checked[]" value="' . esc_attr( $item['slug'] ) . '" id="checkbox_' . esc_attr( $item['slug'] ) . '" />';
	}

	/**
	 * Icon tile, name (linked to the Drop-in's page) and row actions.
	 *
	 * @since 6.2
	 */

	protected function column_name( $item ) {
		if ( $item['remote'] )
			return $this->remote_name( $item );

		$actions = array();

		if ( $item['active'] && $item['SettingsURL'] )
			$actions['settings'] = '<a href="' . esc_url( admin_url( $item['SettingsURL'] ) ) . '" aria-label="' . esc_attr( sprintf( __( '%s settings', 'md' ), $item['Name'] ) ) . '">' . esc_html__( 'Settings', 'md' ) . '</a>';

		if ( current_user_can( 'activate_plugins' ) ) {
			if ( $item['active'] )
				$actions['deactivate'] = $this->action_link( 'deactivate', $item, __( 'Deactivate', 'md' ) );
			elseif ( ! is_wp_error( $item['requirements'] ) )
				$actions['activate'] = $this->action_link( 'activate', $item, __( 'Activate', 'md' ) );
		}

		if ( ! $item['active'] && current_user_can( 'delete_plugins' ) )
			$actions['delete'] = $this->action_link( 'delete', $item, __( 'Delete', 'md' ) );

		$version = $item['Version'] ? ' <span class="md-badge">' . esc_html( $item['Version'] ) . '</span>' : '';
		$title = $item['DropinURI'] ? '<a href="' . esc_url( $item['DropinURI'] ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $item['Name'] ) . '</a>' : esc_html( $item['Name'] );

		return
			'<div class="md-dropin-name">'.
				md_get_dropin_icon( $item['slug'], 'large' ).
				'<div class="md-dropin-name-text">'.
					'<strong class="md-dropin-title">' . $title . $version . '</strong>'.
					$this->row_actions( $actions, true ).
				'</div>'.
			'</div>';
	}

	/**
	 * Name cell for a Drop-in that isn't installed: Install, or Upgrade for
	 * one a higher plan adds, with that plan's badge in place of a version.
	 *
	 * @since 6.2
	 */

	private function remote_name( $item ) {
		$actions = array();

		if ( $item['status'] === 'upgrade' ) {
			$badge = $item['plan'] ? ' <span class="md-badge is-plan">' . esc_html( $item['plan'] ) . '</span>' : '';
			$actions['upgrade'] = '<a href="' . esc_url( $item['upgrade_url'] ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $item['plan'] ? sprintf( __( 'Upgrade to %s', 'md' ), $item['plan'] ) : __( 'Upgrade', 'md' ) ) . '</a>';
		}
		else {
			$badge = $item['Version'] ? ' <span class="md-badge">' . esc_html( $item['Version'] ) . '</span>' : '';

			if ( current_user_can( 'install_plugins' ) )
				$actions['install'] = '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-md-dropins&dropins=' . rawurlencode( $item['slug'] ) ), 'bulk-dropins' ) ) . '" aria-label="' . esc_attr( sprintf( __( 'Install %s', 'md' ), $item['Name'] ) ) . '">' . esc_html__( 'Install', 'md' ) . '</a>';
		}

		return
			'<div class="md-dropin-name">'.
				md_dropin_icon_tile( $item['Icon'], $item['Colors'], 'large' ).
				'<div class="md-dropin-name-text">'.
					'<strong class="md-dropin-title">' . esc_html( $item['Name'] ) . $badge . '</strong>'.
					$this->row_actions( $actions, true ).
				'</div>'.
			'</div>';
	}

	/**
	 * Description, then author and Requires MD.
	 *
	 * @since 6.2
	 */

	protected function column_description( $item ) {
		$meta = array();

		if ( $item['remote'] ) {
			if ( $item['status'] === 'included' )
				$meta[] = esc_html__( 'Included in your plan', 'md' );
			elseif ( $item['status'] === 'upgrade' && $item['plan'] )
				$meta[] = esc_html( sprintf( __( 'Comes with MD %s', 'md' ), $item['plan'] ) );

			return
				( $item['Description'] ? '<p class="md-dropin-description">' . esc_html( $item['Description'] ) . '</p>' : '' ).
				( $meta ? '<p class="md-dropin-meta">' . implode( ' <span aria-hidden="true">&middot;</span> ', $meta ) . '</p>' : '' );
		}

		if ( $item['Author'] )
			$meta[] = sprintf( esc_html__( 'By %s', 'md' ), $item['AuthorURI'] ? '<a href="' . esc_url( $item['AuthorURI'] ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $item['Author'] ) . '</a>' : esc_html( $item['Author'] ) );

		if ( $item['RequiresMD'] )
			$meta[] = sprintf( esc_html__( 'Requires MD %s', 'md' ), esc_html( $item['RequiresMD'] ) );

		return
			'<p class="md-dropin-description">' . esc_html( $item['Description'] ) . '</p>'.
			( $meta ? '<p class="md-dropin-meta">' . implode( ' <span aria-hidden="true">&middot;</span> ', $meta ) . '</p>' : '' );
	}

	/**
	 * Core puts the name column's row actions under the name.
	 *
	 * @since 6.2
	 */

	protected function get_primary_column_name() {
		return 'name';
	}

	/**
	 * The row's accent color, from the first of its two colors.
	 *
	 * @since 6.2
	 */

	private function accent( $item ) {
		$colors = $item['Colors'] ? array_map( 'trim', explode( ',', $item['Colors'] ) ) : array();

		return $colors ? ' style="--md-dropin-accent: ' . esc_attr( $colors[0] ) . ';"' : '';
	}

	/**
	 * A nonce'd single action link.
	 *
	 * @since 6.2
	 */

	private function action_link( $action, $item, $label ) {
		$url = wp_nonce_url( md_dropins::url( array(
			'action' => $action,
			'dropin' => $item['slug'],
			'dropin_status' => $this->status === 'all' ? false : $this->status
		) ), "md-dropin-{$action}_{$item['slug']}" );

		return '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $action ) . '" aria-label="' . esc_attr( sprintf( '%s %s', $label, $item['Name'] ) ) . '">' . esc_html( $label ) . '</a>';
	}

}
