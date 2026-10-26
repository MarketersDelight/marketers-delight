<?php

/**
 * Row fields and their save rules.
 *
 * @since 6.0.4
 */

function md_title_section_fields() {
	return array(
		'section_title' => array( 'type' => 'text' ),
		'section_text' => array( 'type' => 'textarea' ),
		'section_heading' => array(
			'type' => 'select',
			'options' => array( 'h2', 'h3' )
		),
		'section_display' => array(
			'type' => 'checkbox',
			'options' => array( 'archive_meta' )
		),
		'section_media' => md_featured_media_fields(),
		'section_cover' => md_page_cover_fields(),
		'section_cta' => md_page_cta_fields()
	);
}

/**
 * Turn a row's settings into the data md_title_template() takes. A row is
 * literal: nothing inherits from the page it's shown on.
 *
 * @since 6.0.4
 */

function md_title_section_data( $row ) {
	$media = $row['section_media'] ?? array();
	$cover = $row['section_cover'] ?? array();

	if ( ! empty( $media['image']['id'] ) ) {
		$media['media_type'] = 'image';
		$media['position'] = ! empty( $media['position'] ) ? $media['position'] : 'title_left';
	}
	else $media = array();

	if ( ! empty( $cover['bg_color'] ) || ! empty( $cover['photo']['id'] ) )
		$cover['position'] = 'headline_cover';
	else
		$cover = array();

	return array(
		'title' => $row['section_title'] ?? '',
		'description' => $row['section_text'] ?? '',
		'media' => $media,
		'cover' => $cover,
		'cta' => $row['section_cta'] ?? array(),
		'identity' => array(),
		'archive_meta' => ! empty( $row['section_display']['archive_meta'] ) ? md_get_archive_meta() : array(),
		'header_cover' => false,
		'has_sidebar' => false,
		'loop' => array()
	);
}

/**
 * Render a Title Section row.
 *
 * @since 6.0.4
 */

function md_title_section( $row, $post_type, $id, $main_query ) {
	$data = md_title_section_data( $row );

	if ( ! $data['title'] && ! $data['description'] && ! $data['media'] )
		return;

	$data['has_sidebar'] = md_has_sidebar();

	md_title_template( 'section', $data, array(
		'tag' => 'section',
		'heading' => ! empty( $row['section_heading'] ) ? $row['section_heading'] : 'h2'
	) );
}

/**
 * Render a Title Section row's controls.
 *
 * @since 6.0.4
 */

function md_title_section_admin_fields( $group, $type, $fields ) {
	$prefix = 'md_title_section_' . sanitize_key( $group );
	$media_fields = $fields->within( array( 'builder', $group, 'section_media' ) );
	$media_prefix = "{$prefix}_media";
	$cta_fields = $fields->within( array( 'builder', $group, 'section_cta' ) );
	$cta_prefix = "{$prefix}_cta";

	include md_template( 'features', 'archive/admin/title-section', true );
}
