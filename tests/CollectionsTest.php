<?php

class CollectionsTest extends MD_TestCase {
	protected $collection;

	protected function setUp(): void {
		parent::setUp();

		md_test_set_filter( 'md_filter_collections', array(
			'book_quotes' => array(
				'parent' => 'bookshelf',
				'post_type' => 'book_quote',
				'fields' => array(
					'content' => array(
						'type' => 'textarea',
						'source' => 'post_content',
						'required' => true
					),
					'note' => array( 'type' => 'textarea' ),
					'location' => array( 'type' => 'text' ),
					'topics' => array(
						'type' => 'terms',
						'source' => 'taxonomy',
						'taxonomy' => 'bookshelf_topic'
					)
				)
			)
		) );
		$this->collection = new md_collection( 'book_quotes' );
		$this->collection->register();

		md_test_set_post( array(
			'ID' => 25,
			'post_type' => 'book_quote',
			'post_parent' => 10,
			'post_status' => 'publish',
			'post_content' => '<p>A useful quote.</p>',
			'post_date' => '2026-08-18 12:34:56'
		) );
	}

	public function test_registers_predictable_standalone_meta_keys() {
		$fields = $this->collection->fields;
		$registered = $GLOBALS['__test_registered_meta']['book_quote']['book_quote_note'];

		$this->assertSame( 'book_quote_note', $fields['note']['meta_key'] );
		$this->assertSame( 'book_quote_location', $fields['location']['meta_key'] );
		$this->assertSame( 'post_status', $fields['status']['source'] );
		$this->assertArrayNotHasKey( 'future', $fields['status']['options'] );
		$this->assertSame( 'post_date', $fields['date']['source'] );
		$this->assertArrayHasKey( 'book_quote_note', $GLOBALS['__test_registered_meta']['book_quote'] );
		$this->assertArrayNotHasKey( 'show_in_rest', $registered );
		$this->assertSame( array( $this->collection, 'sanitize_meta' ), $registered['sanitize_callback'] );
		$this->assertArrayNotHasKey( 'auth_callback', $registered );
		$this->assertSame( 'Clean note', call_user_func( $registered['sanitize_callback'], ' Clean note ', 'book_quote_note', 'post', 'book_quote' ) );
	}

	public function test_constructor_accepts_the_definition_without_reentering_the_registry() {
		$collection = new md_collection( 'stream_threads', array(
			'parent' => 'stream',
			'post_type' => 'stream_thread',
			'per_page' => 20,
			'fields' => array(
				'text' => array( 'type' => 'textarea', 'source' => 'post_content' )
			)
		) );

		$this->assertSame( 'stream', $collection->parent );
		$this->assertSame( 'stream_thread', $collection->post_type );
		$this->assertSame( 20, $collection->per_page );
		$this->assertArrayHasKey( 'text', $collection->fields );
	}

	public function test_supports_the_stream_thread_storage_contract() {
		md_test_set_filter( 'md_filter_collections', array(
			'stream_threads' => array(
				'parent' => 'stream',
				'post_type' => 'stream_thread',
				'fields' => array(
					'title' => array( 'type' => 'text', 'source' => 'post_title' ),
					'text' => array( 'type' => 'textarea', 'source' => 'post_content', 'required' => true ),
					'post_id' => array( 'type' => 'number' ),
					'image' => array( 'type' => 'upload' ),
					'image_width' => array( 'type' => 'range' ),
					'image_position' => array( 'type' => 'select' ),
					'user_id' => array( 'type' => 'number', 'source' => 'post_author' )
				)
			)
		) );
		$threads = new md_collection( 'stream_threads' );
		$threads->register();

		md_test_set_post( array(
			'ID' => 40,
			'post_type' => 'stream_thread',
			'post_parent' => 12,
			'post_title' => '',
			'post_date' => '2026-08-18 12:34:56',
			'post_author' => 1
		) );

		$saved = $threads->save( 40, array(
			'title' => 'A titled update',
			'text' => '<p>A second thought.</p>',
			'post_id' => 25,
			'image' => 80,
			'image_width' => 420,
			'image_position' => 'right',
			'status' => 'draft',
			'date' => '2026-08-17',
			'user_id' => 7
		) );

		$this->assertFalse( is_wp_error( $saved ) );
		$this->assertSame( 'A titled update', $GLOBALS['__test_posts'][40]->post_title );
		$this->assertSame( '<p>A second thought.</p>', $GLOBALS['__test_posts'][40]->post_content );
		$this->assertSame( 'draft', $GLOBALS['__test_posts'][40]->post_status );
		$this->assertSame( '2026-08-17 12:34:56', $GLOBALS['__test_posts'][40]->post_date );
		$this->assertSame( 7, $GLOBALS['__test_posts'][40]->post_author );
		$this->assertSame( 25, $GLOBALS['__test_object_meta'][40]['stream_thread_post_id'] );
		$this->assertSame( 80, $GLOBALS['__test_object_meta'][40]['stream_thread_image'] );
		$this->assertSame( 420, $GLOBALS['__test_object_meta'][40]['stream_thread_image_width'] );
		$this->assertSame( 'right', $GLOBALS['__test_object_meta'][40]['stream_thread_image_position'] );

		$threads->save( 40, array( 'text' => '<p>A later edit.</p>' ) );

		$this->assertSame( 'A titled update', $GLOBALS['__test_posts'][40]->post_title );
		$this->assertSame( '', $threads->item_title( array( 'text' => 'Untitled content.' ) ) );
	}

	public function test_author_reassignment_requires_edit_others_posts() {
		md_test_set_filter( 'md_filter_collections', array(
			'stream_threads' => array(
				'parent' => 'stream',
				'post_type' => 'stream_thread',
				'fields' => array(
					'text' => array( 'type' => 'textarea', 'source' => 'post_content' ),
					'user_id' => array( 'type' => 'number', 'source' => 'post_author' )
				)
			)
		) );
		$threads = new md_collection( 'stream_threads' );

		md_test_set_post( array(
			'ID' => 40,
			'post_type' => 'stream_thread',
			'post_author' => 1
		) );
		md_test_set_capability( 'edit_others_stream_threads', false );

		$error = $threads->save( 40, array( 'user_id' => 7 ) );

		$this->assertSame( 'md_collection_author', $error->get_error_code() );
		$this->assertSame( 1, $GLOBALS['__test_posts'][40]->post_author );

		$saved = $threads->save( 40, array( 'user_id' => get_current_user_id() ) );

		$this->assertFalse( is_wp_error( $saved ) );

		md_test_set_capability( 'edit_others_stream_threads', true );

		$saved = $threads->save( 40, array( 'user_id' => 7 ) );

		$this->assertFalse( is_wp_error( $saved ) );
		$this->assertSame( 7, $GLOBALS['__test_posts'][40]->post_author );
	}

	public function test_normalizes_media_upload_values_from_standard_forms() {
		md_test_set_filter( 'md_filter_collections', array(
			'stream_threads' => array(
				'parent' => 'stream',
				'post_type' => 'stream_thread',
				'fields' => array(
					'image' => array( 'type' => 'upload' )
				)
			)
		) );
		$threads = new md_collection( 'stream_threads' );
		$threads->register();

		md_test_set_post( array(
			'ID' => 40,
			'post_type' => 'stream_thread'
		) );
		$threads->save( 40, array(
			'image' => array( 'id' => '80' )
		) );

		$this->assertSame( 80, $GLOBALS['__test_object_meta'][40]['stream_thread_image'] );
	}

	public function test_unset_numeric_fields_remain_empty_and_delete_cleared_meta() {
		md_test_set_filter( 'md_filter_collections', array(
			'stream_threads' => array(
				'parent' => 'stream',
				'post_type' => 'stream_thread',
				'fields' => array(
					'post_id' => array( 'type' => 'number' ),
					'image_width' => array( 'type' => 'range' )
				)
			)
		) );
		$threads = new md_collection( 'stream_threads' );
		$threads->register();

		md_test_set_post( array(
			'ID' => 40,
			'post_type' => 'stream_thread'
		) );

		$this->assertSame( '', $threads->get( 'post_id', 40, '' ) );
		$this->assertArrayNotHasKey( 'default', $GLOBALS['__test_registered_meta']['stream_thread']['stream_thread_post_id'] );

		$GLOBALS['__test_object_meta'][40]['stream_thread_post_id'] = 25;
		$threads->save( 40, array(
			'post_id' => '',
			'image_width' => ''
		) );

		$this->assertArrayNotHasKey( 'stream_thread_post_id', $GLOBALS['__test_object_meta'][40] );
		$this->assertArrayNotHasKey( 'stream_thread_image_width', $GLOBALS['__test_object_meta'][40] );
	}

	public function test_reads_a_normalized_item_across_sources() {
		$GLOBALS['__test_object_meta'][25] = array(
			'book_quote_note' => 'Remember this.',
			'book_quote_location' => 'Page 42'
		);
		$GLOBALS['__test_terms'][25]['bookshelf_topic'] = array( 'Writing', 'Marketing' );

		$this->assertSame( array(
			'content' => '<p>A useful quote.</p>',
			'note' => 'Remember this.',
			'location' => 'Page 42',
			'topics' => array( 'Writing', 'Marketing' ),
			'status' => 'publish',
			'date' => '2026-08-18'
		), $this->collection->get( null, 25 ) );
	}

	public function test_reads_one_field_without_building_the_full_item() {
		$GLOBALS['__test_object_meta'][25]['book_quote_location'] = 'Location 810';

		$this->assertSame( 'Location 810', $this->collection->get( 'location', 25 ) );
	}

	public function test_saves_values_to_their_registered_sources() {
		$saved = $this->collection->save( 25, array(
			'content' => '<strong>Updated quote.</strong>',
			'note' => ' Updated note ',
			'location' => ' Page 90 ',
			'topics' => array( 'Writing', 'Writing', ' Ideas ' ),
			'status' => 'draft',
			'date' => '2026-07-04'
		) );

		$this->assertFalse( is_wp_error( $saved ) );
		$this->assertSame( '<strong>Updated quote.</strong>', $GLOBALS['__test_posts'][25]->post_content );
		$this->assertSame( 'draft', $GLOBALS['__test_posts'][25]->post_status );
		$this->assertSame( '2026-07-04 12:34:56', $GLOBALS['__test_posts'][25]->post_date );
		$this->assertSame( 'Updated note', $GLOBALS['__test_object_meta'][25]['book_quote_note'] );
		$this->assertSame( 'Page 90', $GLOBALS['__test_object_meta'][25]['book_quote_location'] );
		$this->assertSame( array( 'Writing', 'Ideas' ), $GLOBALS['__test_terms'][25]['bookshelf_topic'] );
	}

	public function test_partial_save_reads_only_the_existing_required_value() {
		$saved = $this->collection->save( 25, array(
			'note' => 'A later observation.'
		) );

		$this->assertSame( array( 'note' => 'A later observation.' ), $saved );
		$this->assertSame( '<p>A useful quote.</p>', $GLOBALS['__test_posts'][25]->post_content );
		$this->assertSame( 'A later observation.', $GLOBALS['__test_object_meta'][25]['book_quote_note'] );
	}

	public function test_required_fields_are_validated_before_save_completes() {
		$error = $this->collection->save( 25, array(
			'content' => '',
			'note' => 'Should not save'
		) );

		$this->assertSame( 'md_collection_required', $error->get_error_code() );
		$this->assertArrayNotHasKey( 'book_quote_note', $GLOBALS['__test_object_meta'][25] ?? array() );
	}

	public function test_required_content_rejects_empty_markup() {
		$error = $this->collection->save( 25, array(
			'content' => '<p> </p>'
		) );

		$this->assertSame( 'md_collection_required', $error->get_error_code() );
	}

	public function test_rejects_an_invalid_date_before_writing_other_fields() {
		$error = $this->collection->save( 25, array(
			'content' => 'Still valid.',
			'note' => 'Should not save',
			'date' => 'August 18'
		) );

		$this->assertSame( 'md_collection_date', $error->get_error_code() );
		$this->assertArrayNotHasKey( 'book_quote_note', $GLOBALS['__test_object_meta'][25] ?? array() );
	}

	public function test_rejects_a_calendar_date_that_does_not_exist() {
		$error = $this->collection->save( 25, array(
			'content' => 'Still valid.',
			'date' => '2026-02-30'
		) );

		$this->assertSame( 'md_collection_date', $error->get_error_code() );
	}

	public function test_rejects_an_unknown_publication_status() {
		$error = $this->collection->save( 25, array(
			'status' => 'trash'
		) );

		$this->assertSame( 'md_collection_status', $error->get_error_code() );
		$this->assertSame( 'publish', $GLOBALS['__test_posts'][25]->post_status );
	}

	public function test_returns_default_for_the_wrong_item_post_type() {
		md_test_set_post( array(
			'ID' => 30,
			'post_type' => 'post',
			'post_content' => 'Not a quote.'
		) );

		$this->assertSame( 'fallback', $this->collection->get( 'content', 30, 'fallback' ) );
	}

}
