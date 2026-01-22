<?php

$admin_pages = md_register( 'admin_pages' );
$admin_tabs = array();

foreach ( $admin_pages as $admin_page => $fields )
	$admin_tabs[] = "md_$admin_page";

if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->_id || in_array( $this->_id, $admin_tabs ) ) ) {
	if ( isset( $_GET['settings-updated'] ) ) {
		md_compile( true ); // heh
		flush_rewrite_rules();
	}
	if ( ! $label && ! empty( $admin_pages[$this->_clean_id]['name'] ) )
		$label = sprintf( __( 'Save %s', 'md' ), esc_html( $admin_pages[$this->_clean_id]['name'] ) );
}
?>

<input type="submit" name="submit" id="submit" class="button button-primary md-button" value="<?php echo $label; ?>" />