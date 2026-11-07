<?php
/**
 * Runs MD's abilities for real through the Abilities API. It changes settings
 * and content, so it only runs on the throwaway site abilities.sh builds:
 *
 * bash tests/integration/abilities.sh
 */

if ( ! defined( 'MD_ABILITIES_SCRATCH' ) ) {
	WP_CLI::error( 'This changes settings and content. Run it with tests/integration/abilities.sh.' );
}

global $md_test_fails, $md_test_passes;

$md_test_fails = array();
$md_test_passes = 0;

function md_test( $name, $condition, $info = '' ) {
	global $md_test_fails, $md_test_passes;

	if ( $condition ) {
		$md_test_passes++;
		return;
	}

	$md_test_fails[] = $name;
	echo "FAIL $name  <<< " . substr( is_scalar( $info ) ? (string) $info : (string) wp_json_encode( $info ), 0, 260 ) . "\n";
}

function md_test_call( $name, $input = null ) {
	$ability = wp_get_ability( strpos( $name, '/' ) ? $name : "marketers-delight/$name" );

	if ( ! $ability )
		return new WP_Error( 'md_test_no_ability', "No ability $name" );

	$result = $ability->execute( $input );

	return is_wp_error( $result ) ? $result : json_decode( wp_json_encode( $result ), true );
}

function md_test_as( $user_id ) {
	wp_set_current_user( $user_id );
}

function md_test_ids( $result, $key = 'id' ) {
	return array_column( isset( $result['items'] ) ? $result['items'] : array(), $key );
}

function md_test_image( $author ) {
	$png = base64_decode( 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==' );
	$file = wp_upload_bits( 'abilities-test.png', null, $png );
	$id = wp_insert_attachment( array( 'post_title' => 'Test image', 'post_mime_type' => 'image/png', 'post_status' => 'inherit', 'post_author' => $author ), $file['file'] );

	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file['file'] ) );

	return $id;
}

function md_test_widgets( $result, $sidebar ) {
	foreach ( isset( $result['sidebars'] ) ? $result['sidebars'] : array() as $row )
		if ( $row['id'] === $sidebar )
			return $row['widgets'];

	return array();
}

function md_test_report() {
	global $md_test_fails, $md_test_passes;

	echo "$md_test_passes passed, " . count( $md_test_fails ) . " failed\n";

	if ( $md_test_fails )
		exit( 1 );
}

$admin = get_user_by( 'login', 'builder' )->ID;
md_test_as( $admin );

md_test( 'abilities load under WP-CLI', (bool) wp_get_ability( 'marketers-delight/describe-site' ) );

// With the Trash turned off, trashing would delete for good

if ( ! EMPTY_TRASH_DAYS ) {
	$post = wp_insert_post( array( 'post_title' => 'Keep me', 'post_status' => 'publish', 'post_author' => $admin ) );
	$book = wp_insert_post( array( 'post_type' => 'bookshelf', 'post_title' => 'Book', 'post_status' => 'publish', 'post_author' => $admin ) );
	$item = md_test_call( 'save-item', array( 'collection' => 'book_quotes', 'parent_id' => $book, 'fields' => array( 'content' => 'A highlight' ) ) );

	$done = md_test_call( 'trash-content', array( 'id' => $post ) );
	md_test( 'trash-content refused', is_wp_error( $done ) && get_post_status( $post ) === 'publish', $done );

	$done = md_test_call( 'trash-item', array( 'collection' => 'book_quotes', 'item_id' => $item['saved_id'] ) );
	md_test( 'trash-item refused', is_wp_error( $done ) && get_post_status( $item['saved_id'] ) === 'publish', $done );

	$response = rest_do_request( new WP_REST_Request( 'DELETE', "/md/v1/collections/book_quotes/$book/{$item['saved_id']}" ) );
	md_test( 'Collections REST delete refused', $response->is_error() && get_post_status( $item['saved_id'] ) === 'publish', $response->get_data() );

	md_test_report();
	return;
}

$image = md_test_image( $admin );
$author = wp_insert_user( array( 'user_login' => 'author_' . wp_generate_password( 6, false ), 'user_pass' => wp_generate_password(), 'role' => 'author' ) );
$contributor = wp_insert_user( array( 'user_login' => 'contributor_' . wp_generate_password( 6, false ), 'user_pass' => wp_generate_password(), 'role' => 'contributor' ) );

// Describe

$site = md_test_call( 'describe-site' );
md_test( 'describe-site', ! is_wp_error( $site ) && ! empty( $site['md_version'] ) && in_array( 'settings', array_column( $site['groups'], 'group' ), true ), $site );

$describe = md_test_call( 'describe-settings', array( 'group' => 'layout' ) );
md_test( 'describe-settings', ! empty( $describe['fields'] ) && isset( $describe['values_by_type'] ), $describe );

// Content

$page = md_test_call( 'save-content', array( 'type' => 'page', 'title' => 'About Us', 'content' => 'Hello world. Second sentence.', 'status' => 'publish' ) );
$page_id = $page['id'];
md_test( 'create page', $page['status'] === 'publish', $page );

$edit = md_test_call( 'save-content', array( 'id' => $page_id, 'edits' => array( array( 'find' => 'Second', 'replace' => '2nd' ) ) ) );
md_test( 'edits', ( $edit['content'] ?? '' ) === 'Hello world. 2nd sentence.', $edit );
md_test( 'edits refuse no match', is_wp_error( md_test_call( 'save-content', array( 'id' => $page_id, 'edits' => array( array( 'find' => 'nope', 'replace' => 'x' ) ) ) ) ) );
md_test( 'edits refuse empty find', is_wp_error( md_test_call( 'save-content', array( 'id' => $page_id, 'edits' => array( array( 'find' => '', 'replace' => 'x' ) ) ) ) ) );

md_test( 'find-content', in_array( $page_id, md_test_ids( md_test_call( 'find-content', array( 'search' => 'About', 'type' => 'page' ) ) ), true ) );
md_test( 'find images', in_array( $image, md_test_ids( md_test_call( 'find-content', array( 'type' => 'attachment' ) ) ), true ) );
md_test( 'unknown category refused', is_wp_error( md_test_call( 'save-content', array( 'type' => 'post', 'title' => 'Blog', 'status' => 'publish', 'terms' => array( 'category' => array( 'Nope' ) ) ) ) ) );

$term = md_test_call( 'save-term', array( 'taxonomy' => 'category', 'name' => 'News' ) );
md_test( 'save-term create', ( $term['name'] ?? '' ) === 'News', $term );
$renamed = md_test_call( 'save-term', array( 'taxonomy' => 'category', 'id' => $term['id'], 'name' => 'Latest News' ) );
md_test( 'save-term rename', ( $renamed['name'] ?? '' ) === 'Latest News', $renamed );
$terms = md_test_call( 'find-terms', array( 'taxonomy' => 'category' ) );
md_test( 'find-terms', in_array( $term['id'], array_column( $terms['terms'] ?? array(), 'id' ), true ), $terms );

$blog = md_test_call( 'save-content', array(
	'type' => 'post',
	'title' => 'Blog',
	'status' => 'publish',
	'terms' => array( 'category' => array( 'Latest News' ) ),
	'add_terms' => array( 'post_tag' => array( 'pricing' ) ),
	'image_id' => $image,
	'settings' => array( 'featured_media' => array( 'position' => 'right' ) )
) );
$blog_id = $blog['id'] ?? 0;
md_test( 'create post with terms, image and settings', ( $blog['terms']['post_tag'] ?? null ) === array( 'pricing' ) && ( $blog['image_id'] ?? 0 ) === $image, $blog );

$settings = md_test_call( 'get-settings', array( 'scope' => "post:$blog_id", 'group' => 'featured_media' ) );
md_test( 'settings on post', ( $settings['values']['position'] ?? '' ) === 'right', $settings );

$more = md_test_call( 'save-content', array( 'id' => $blog_id, 'add_terms' => array( 'post_tag' => array( 'extra' ) ) ) );
$tags = $more['terms']['post_tag'] ?? array();
sort( $tags );
md_test( 'add_terms keeps others', $tags === array( 'extra', 'pricing' ), $more );

$trashed = md_test_call( 'trash-content', array( 'id' => $blog_id ) );
md_test( 'trash-content', ( $trashed['status'] ?? '' ) === 'trash', $trashed );
$found = md_test_call( 'find-content', array( 'id' => $blog_id ) );
md_test( 'find by id sees trash', ( $found['items'][0]['status'] ?? '' ) === 'trash', $found );
$restored = md_test_call( 'trash-content', array( 'id' => $blog_id, 'restore' => true ) );
md_test( 'restore keeps status', ( $restored['status'] ?? '' ) === 'publish', $restored );

// Who can read what

register_post_type( 'md_test_private', array( 'public' => false, 'capability_type' => 'md_test_private', 'map_meta_cap' => true ) );
$secret = wp_insert_post( array( 'post_type' => 'md_test_private', 'post_title' => 'Entry', 'post_content' => 'SECRET', 'post_status' => 'publish', 'post_author' => $admin ) );
$locked = wp_insert_post( array( 'post_title' => 'Locked', 'post_content' => 'SECRET', 'post_password' => 'pw', 'post_status' => 'publish', 'post_author' => $admin ) );

md_test_as( $contributor );
$found = md_test_call( 'find-content', array( 'id' => $locked ) );
md_test( 'contributor can\'t read password-protected post', empty( $found['items'] ), $found );
md_test( 'contributor can\'t read non-public type', strpos( (string) wp_json_encode( md_test_call( 'find-content', array( 'type' => 'md_test_private', 'content' => true ) ) ), 'SECRET' ) === false );
md_test( 'contributor reads public post', count( md_test_ids( md_test_call( 'find-content', array( 'id' => $page_id ) ) ) ) === 1 );
md_test( 'contributor sees images', count( md_test_ids( md_test_call( 'find-content', array( 'id' => $image ) ) ) ) === 1 );
md_test_as( $admin );
md_test( 'admin reads password-protected post', count( md_test_ids( md_test_call( 'find-content', array( 'id' => $locked ) ) ) ) === 1 );

// Settings

$update = md_test_call( 'update-settings', array( 'scope' => "post:$page_id", 'group' => 'layout', 'values' => array( 'sidebar' => array( 'remove' => true ) ) ) );
md_test( 'per page override', ! empty( $update['changed'] ), $update );
$settings = md_test_call( 'get-settings', array( 'scope' => "post:$page_id", 'group' => 'layout' ) );
md_test( 'get-settings', ! empty( $settings['values']['sidebar']['remove'] ), $settings );
md_test_call( 'update-settings', array( 'scope' => "post:$page_id", 'group' => 'layout', 'values' => array( 'sidebar' => array( 'remove' => null ) ) ) );
$settings = md_test_call( 'get-settings', array( 'scope' => "post:$page_id", 'group' => 'layout' ) );
md_test( 'null resets', empty( $settings['values'] ), $settings );

md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'settings', 'values' => array( 'sidebars' => array( 'side-a' => array( 'name' => 'A' ), 'side-b' => array( 'name' => 'B' ) ) ) ) );
md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'settings', 'values' => array( 'sidebars' => array( 'side-c' => array( 'name' => 'C' ), 'side-a' => null ) ), 'order' => array( 'sidebars' => array( 'side-c', 'side-b' ) ) ) );
$settings = md_test_call( 'get-settings', array( 'scope' => 'site', 'group' => 'settings' ) );
$sidebars = array_keys( $settings['values']['sidebars'] ?? array() );
md_test( 'items merge, remove and order', array_slice( $sidebars, 0, 2 ) === array( 'side-c', 'side-b' ) && ! in_array( 'side-a', $sidebars, true ), $settings );

$before = get_option( 'marketers_delight' );
$dry = md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'settings', 'values' => array( 'sidebars' => array( 'side-d' => array( 'name' => 'D' ) ) ), 'dry_run' => true ) );
md_test( 'dry run changes nothing', ! is_wp_error( $dry ) && get_option( 'marketers_delight' ) === $before, $dry );

// Collection items

$stream = md_test_call( 'save-content', array( 'type' => 'stream', 'title' => 'Monday', 'content' => 'Roundup', 'status' => 'publish' ) );
$stream_id = $stream['id'] ?? 0;
md_test( 'stream post', (bool) $stream_id, $stream );

$items = array();

foreach ( array( 'one', 'two', 'three', 'conclusion' ) as $word ) {
	$saved = md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'fields' => array( 'title' => $word, 'content' => "$word body" ) ) );
	$items[] = $saved['saved_id'] ?? 0;
}

md_test( 'add items', count( array_filter( $items ) ) === 4, $items );

$saved = md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'fields' => array( 'title' => 'between', 'content' => 'between body' ) ) );
$between = $saved['saved_id'];
$ordered = md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'order' => array( $items[0], $between, $items[1], $items[2], $items[3] ) ) );
$titles = array();

foreach ( $ordered['items'] ?? array() as $row )
	$titles[] = $row['fields']['title'] ?? '';

md_test( 'add between with order', $titles === array( 'one', 'between', 'two', 'three', 'conclusion' ), $titles );

$edited = md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'item_id' => $items[1], 'edits' => array( array( 'find' => 'two body', 'replace' => 'two fixed' ) ) ) );
md_test( 'item edits', in_array( 'two fixed', array_column( array_column( $edited['items'] ?? array(), 'fields' ), 'content' ), true ), $edited );

$patched = md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'item_id' => $items[2], 'fields' => array( 'title' => 'three new' ) ) );
$kept = false;

foreach ( $patched['items'] ?? array() as $row )
	if ( ( $row['fields']['title'] ?? '' ) === 'three new' && ( $row['fields']['content'] ?? '' ) === 'three body' )
		$kept = true;

md_test( 'item patch keeps other fields', $kept, $patched );

$found = md_test_call( 'find-content', array( 'id' => $stream_id ) );
md_test( 'stored item count', ( $found['items'][0]['items']['stream_threads'] ?? 0 ) === 5, $found );

$done = md_test_call( 'trash-item', array( 'collection' => 'stream_threads', 'item_id' => $items[1] ) );
md_test( 'trash-item', ( $done['published'] ?? 0 ) === 4, $done );
$found = md_test_call( 'find-content', array( 'id' => $stream_id ) );
md_test( 'item count drops after trash', ( $found['items'][0]['items']['stream_threads'] ?? 0 ) === 4, $found );

$listed = md_test_call( 'find-items', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'include' => array( 'trash' ) ) );
md_test( 'find-items includes trash', in_array( 'trash', array_column( $listed['items'] ?? array(), 'status' ), true ), $listed );
$done = md_test_call( 'trash-item', array( 'collection' => 'stream_threads', 'item_id' => $items[1], 'restore' => true ) );
md_test( 'restore item', ( $done['published'] ?? 0 ) === 5, $done );

md_test( 'Stream share check', is_wp_error( md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $stream_id, 'fields' => array( 'content' => 'x', 'post_id' => 999999 ) ) ) ) );
$draft = md_test_call( 'save-content', array( 'type' => 'stream', 'title' => 'Draft', 'status' => 'draft' ) );
md_test( 'draft parent refused', is_wp_error( md_test_call( 'save-item', array( 'collection' => 'stream_threads', 'parent_id' => $draft['id'], 'fields' => array( 'content' => 'x' ) ) ) ) );

// Other Drop-ins' content

$book = md_test_call( 'save-content', array( 'type' => 'bookshelf', 'title' => 'A Book', 'status' => 'publish', 'settings' => array( 'bookshelf' => array( 'book_author' => 'Ann', 'book_rating' => 4 ) ) ) );
md_test( 'book with settings', ! empty( $book['id'] ) && empty( $book['warnings'] ), $book );
$quote = md_test_call( 'save-item', array( 'collection' => 'book_quotes', 'parent_id' => $book['id'], 'fields' => array( 'content' => 'A highlight', 'topics' => array( 'Habits' ) ) ) );
md_test( 'highlight with topic', ( $quote['published'] ?? 0 ) === 1 && ( $quote['items'][0]['fields']['topics'] ?? null ) === array( 'Habits' ), $quote );

$log = md_test_call( 'save-content', array( 'type' => 'changelog', 'title' => '1.0', 'status' => 'publish', 'settings' => array( 'changelog' => array( 'version' => '1.0', 'list' => array( 'a' => array( 'item' => 'First', 'prefix' => 'fix' ), 'b' => array( 'item' => 'Third', 'prefix' => 'new' ) ) ) ) ) );
md_test( 'changelog', ! empty( $log['id'] ) && empty( $log['warnings'] ), $log );
md_test_call( 'update-settings', array( 'scope' => "post:{$log['id']}", 'group' => 'changelog', 'values' => array( 'list' => array( 'm' => array( 'item' => 'Second', 'prefix' => 'fix' ) ) ), 'order' => array( 'list' => array( 'a', 'm', 'b' ) ) ) );
$settings = md_test_call( 'get-settings', array( 'scope' => "post:{$log['id']}", 'group' => 'changelog' ) );
md_test( 'changelog insert between', array_keys( $settings['values']['list'] ?? array() ) === array( 'a', 'm', 'b' ), $settings );

// Popups

$update = md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'popups', 'values' => array( 'popups' => array( 'join' => array( 'name' => 'Join', 'locations' => array( 'sitewide' => true ) ) ) ) ) );
md_test( 'create popup', ! empty( $update['changed'] ), $update );
$design = md_test_call( 'md-optins/get-popup-design', array( 'popup' => 'join' ) );
md_test( 'get-popup-design', ( $design['fields']['content.headline']['type'] ?? '' ) === 'text', $design );
$update = md_test_call( 'md-optins/update-popup-design', array( 'popup' => 'join', 'values' => array(
	'content' => array( 'headline' => 'Join us <script>x</script>' ),
	'colors' => array( 'bg_color' => '#112233' ),
	'design' => array( 'bg_image' => 'javascript:alert(1)' ),
	'custom' => array( 'classes' => 'nice <b>x</b>' ),
	'bogus' => array( 'x' => 1 )
) ) );
$values = $update['values'] ?? array();
md_test( 'update-popup-design', strpos( $values['content']['headline'] ?? '', 'Join us' ) === 0 && strpos( $values['content']['headline'] ?? '', 'script' ) === false && ! empty( $update['warnings'] ), $update );
md_test( 'popup background drops bad protocol', strpos( (string) ( $values['design']['bg_image'] ?? '' ), 'javascript' ) === false, $values );
md_test( 'popup classes lose markup', ( $values['custom']['classes'] ?? '' ) === 'nice x', $values );
md_test( 'unknown popup', is_wp_error( md_test_call( 'md-optins/get-popup-design', array( 'popup' => 'none' ) ) ) );

// Drop-ins

$status = md_test_call( 'set-dropin-status', array( 'dropin' => 'footnotes', 'active' => false ) );
md_test( 'Drop-in off', ( $status['active'] ?? null ) === false, $status );
$status = md_test_call( 'set-dropin-status', array( 'dropin' => 'footnotes', 'active' => true ) );
md_test( 'Drop-in on', ( $status['active'] ?? null ) === true, $status );
md_test( 'missing Drop-in', is_wp_error( md_test_call( 'set-dropin-status', array( 'dropin' => 'nope', 'active' => true ) ) ) );
$dropins = md_test_call( 'find-dropins' );
md_test( 'find-dropins', ! empty( $dropins['installed'] ), $dropins );

// Menus

$menu = md_test_call( 'update-menu', array( 'create' => 'Main', 'location' => 'main_menu', 'items' => array(
	array( 'op' => 'add', 'title' => 'About', 'object' => 'page', 'object_id' => $page_id ),
	array( 'op' => 'add', 'title' => 'Blog', 'url' => 'https://example.test/b' ),
	array( 'op' => 'add', 'title' => 'Contact', 'url' => 'https://example.test/c' ),
	array( 'op' => 'add', 'title' => 'Help', 'url' => 'https://example.test/h' )
) ) );
$menu_id = $menu['id'] ?? 0;
$links = md_test_ids( $menu );
md_test( 'create menu', count( $links ) === 4, $menu );

$first = $links[1];
update_post_meta( $first, '_menu_item_classes', array( 'btn', 'wide' ) );
update_post_meta( $first, '_menu_item_target', '_blank' );
update_post_meta( $first, '_menu_item_xfn', 'friend' );
wp_update_post( array( 'ID' => $first, 'post_excerpt' => 'Attr', 'post_content' => 'Desc' ) );

$menu = md_test_call( 'update-menu', array( 'menu' => $menu_id, 'items' => array( array( 'op' => 'update', 'id' => $links[0], 'title' => 'Our Story' ) ) ) );
md_test( 'renaming first item keeps its place', md_test_ids( $menu ) === $links, $menu );

$menu = md_test_call( 'update-menu', array( 'menu' => $menu_id, 'items' => array( array( 'op' => 'update', 'id' => $first, 'title' => 'News' ) ), 'order' => array( $links[3], $links[1] ) ) );
md_test( 'partial order: listed first, rest follow', md_test_ids( $menu ) === array( $links[3], $links[1], $links[0], $links[2] ), $menu );
md_test( 'no colliding positions', md_test_ids( $menu, 'order' ) === array( 1, 2, 3, 4 ), $menu );

$item = wp_setup_nav_menu_item( get_post( $first ) );
md_test( 'changes keep classes', implode( ' ', array_filter( (array) $item->classes ) ) === 'btn wide', $item->classes );
md_test( 'changes keep target, relationship, title attribute and description', $item->target === '_blank' && $item->xfn === 'friend' && $item->attr_title === 'Attr' && $item->description === 'Desc' );

$other = md_test_call( 'update-menu', array( 'create' => 'Footer', 'items' => array( array( 'op' => 'add', 'title' => 'Other', 'url' => 'https://example.test/o' ) ) ) );
$foreign = $other['items'][0]['id'];
$done = md_test_call( 'update-menu', array( 'menu' => $menu_id, 'items' => array( array( 'op' => 'remove', 'id' => $foreign ) ) ) );
md_test( 'remove refuses another menu\'s item', is_wp_error( $done ) && get_post( $foreign ), $done );
$done = md_test_call( 'update-menu', array( 'menu' => $menu_id, 'items' => array( array( 'op' => 'update', 'id' => $foreign, 'title' => 'x' ) ) ) );
md_test( 'update refuses another menu\'s item', is_wp_error( $done ) && is_object_in_term( $foreign, 'nav_menu', $other['id'] ), $done );
$before = md_test_ids( md_test_call( 'get-menu', array( 'menu' => $menu_id ) ) );
$done = md_test_call( 'update-menu', array( 'menu' => $menu_id, 'order' => array( $links[2], $foreign ) ) );
md_test( 'order refuses another menu\'s item and changes nothing', is_wp_error( $done ) && md_test_ids( md_test_call( 'get-menu', array( 'menu' => $menu_id ) ) ) === $before, $done );

md_test_call( 'update-menu', array( 'menu' => $menu_id, 'items' => array( array( 'op' => 'remove', 'id' => $links[1] ) ) ) );
md_test( 'remove own item', ! get_post( $links[1] ) );

md_test_call( 'trash-content', array( 'id' => $page_id ) );
$menu = md_test_call( 'get-menu', array( 'menu' => $menu_id ) );
md_test( 'flags trashed target', in_array( true, array_map( 'boolval', array_column( $menu['items'] ?? array(), 'problem' ) ), true ), $menu );
md_test_call( 'trash-content', array( 'id' => $page_id, 'restore' => true ) );
$menus = md_test_call( 'get-menu' );
md_test( 'list menus', count( $menus['menus'] ?? array() ) === 2, $menus );

// Widgets

$widgets = md_test_call( 'get-widgets' );
md_test( 'get-widgets', ! empty( $widgets['sidebars'] ) && in_array( 'block', $widgets['types'] ?? array(), true ), $widgets );
$widgets = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array(
	array( 'op' => 'add', 'type' => 'block', 'settings' => array( 'content' => '<p>Hi</p>' ) ),
	array( 'op' => 'add', 'type' => 'text', 'settings' => array( 'title' => 'T', 'text' => 'x' ) )
) ) );
$placed = md_test_widgets( $widgets, 'side-b' );
md_test( 'add widgets', count( $placed ) === 2, $widgets );
$block = $placed[0]['id'] ?? '';
$text = $placed[1]['id'] ?? '';
$widgets = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array( array( 'op' => 'update', 'id' => $text, 'settings' => array( 'title' => 'New' ) ) ), 'order' => array( $text, $block ) ) );
$placed = md_test_widgets( $widgets, 'side-b' );
md_test( 'update and order widgets', ( $placed[0]['settings']['title'] ?? '' ) === 'New' && ( $placed[0]['settings']['text'] ?? '' ) === 'x', $placed );
$widgets = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array( array( 'op' => 'remove', 'id' => $block ) ) ) );
md_test( 'remove widget', count( md_test_widgets( $widgets, 'side-b' ) ) === 1, $widgets );

$number = (int) substr( $text, strlen( 'text-' ) );
$done = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array( array( 'op' => 'remove', 'id' => $text ), array( 'op' => 'remove', 'id' => 'missing-42' ) ) ) );
md_test( 'a failed widget batch changes nothing', is_wp_error( $done ) && isset( get_option( 'widget_text' )[$number] ) && in_array( $text, wp_get_sidebars_widgets()['side-b'], true ), $done );

$update_calls = 0;
$count_updates = function( $instance ) use ( &$update_calls ) {
	$update_calls++;
	return $instance;
};
add_filter( 'widget_update_callback', $count_updates );
$widgets = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array( array( 'op' => 'add', 'type' => 'recent-posts', 'settings' => array( 'title' => 'Latest', 'number' => '999abc' ) ) ) ) );
remove_filter( 'widget_update_callback', $count_updates );
$placed = md_test_widgets( $widgets, 'side-b' );
md_test( 'widgets save through their own update', $update_calls === 1 && ( end( $placed )['settings']['number'] ?? '' ) === 999, $placed );

add_filter( 'widget_update_callback', '__return_false' );
$before = wp_get_sidebars_widgets()['side-b'];
$done = md_test_call( 'update-widgets', array( 'sidebar' => 'side-b', 'widgets' => array( array( 'op' => 'add', 'type' => 'text', 'settings' => array( 'title' => 'No' ) ) ) ) );
remove_filter( 'widget_update_callback', '__return_false' );
md_test( 'a widget filter can refuse', is_wp_error( $done ) && wp_get_sidebars_widgets()['side-b'] === $before, $done );

// A parent's editor doesn't get other people's items

$item_author = wp_insert_user( array( 'user_login' => 'item_author', 'user_pass' => wp_generate_password(), 'user_email' => 'item_author@example.test', 'role' => 'author' ) );
$own_book = wp_insert_post( array( 'post_type' => 'bookshelf', 'post_title' => 'Own book', 'post_status' => 'publish', 'post_author' => $item_author ) );
$foreign = array();

foreach ( array( array( 'private', '2025-03-01 12:00:00' ), array( 'publish', '2025-01-01 12:00:00' ), array( 'publish', '2025-02-01 12:00:00' ) ) as $row )
	$foreign[] = wp_insert_post( array( 'post_type' => 'book_quote', 'post_parent' => $own_book, 'post_title' => 'Q', 'post_content' => 'x', 'post_status' => $row[0], 'post_date' => $row[1], 'post_author' => $admin ) );

md_test_as( $item_author );
$found = md_test_call( 'find-items', array( 'collection' => 'book_quotes', 'parent_id' => $own_book, 'include' => array( 'drafts' ) ) );
md_test( 'find-items hides another user\'s private item', ! in_array( $foreign[0], md_test_ids( $found ), true ) && in_array( $foreign[1], md_test_ids( $found ), true ), $found );
$date = get_post_field( 'post_date', $foreign[1] );
$done = md_test_call( 'save-item', array( 'collection' => 'book_quotes', 'parent_id' => $own_book, 'order' => array( $foreign[2], $foreign[1] ) ) );
clean_post_cache( $foreign[1] );
clean_post_cache( $foreign[2] );
md_test( 'reorder refuses another user\'s items', is_wp_error( $done ) && get_post_field( 'post_date', $foreign[1] ) === $date && get_post_field( 'post_date', $foreign[2] ) === '2025-02-01 12:00:00', $done );

// Global settings follow the settings screens

$delegate = wp_insert_user( array( 'user_login' => 'theme_only', 'user_pass' => wp_generate_password(), 'user_email' => 'theme_only@example.test', 'role' => 'subscriber' ) );
( new WP_User( $delegate ) )->add_cap( 'edit_theme_options' );
md_test_as( $delegate );
md_test( 'site settings need manage_options', is_wp_error( md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'settings', 'values' => array( '404_page' => 9876 ) ) ) ) );
md_test_as( $admin );

// A manager page and the next page, with nothing shown twice

$paged_book = wp_insert_post( array( 'post_type' => 'bookshelf', 'post_title' => 'Long book', 'post_status' => 'publish', 'post_author' => $admin ) );
$paged = array();

for ( $i = 0; $i < 25; $i++ )
	$paged[] = wp_insert_post( array( 'post_type' => 'book_quote', 'post_parent' => $paged_book, 'post_title' => "Q$i", 'post_content' => 'x', 'post_status' => 'publish', 'post_author' => $admin ) );

$collection = new md_collection( 'book_quotes' );
$first_page = wp_list_pluck( $collection->query( $paged_book, array( 'posts_per_page' => $collection->per_page ) )->posts, 'ID' );
$request = new WP_REST_Request( 'GET', "/md/v1/collections/book_quotes/$paged_book" );
$request->set_param( 'exclude', implode( ',', $first_page ) );
$next = rest_do_request( $request )->get_data();
md_test( 'manager shows 20, then the rest', count( $first_page ) === 20 && substr_count( $next['html'] ?? '', 'data-md-collection-item=' ) === 5 && empty( $next['has_more'] ), $next );
md_test( 'readers still get every item', count( $collection->query( $paged_book )->posts ) === 25 );

// Main queries: odd query strings, sticky posts and the admin bar's links

function md_test_main_query( $args ) {
	$query = new WP_Query;
	$GLOBALS['wp_the_query'] = $query;
	$GLOBALS['wp_query'] = $query;
	$query->query( $args );

	return $query;
}

function md_test_settings_link( $user_id ) {
	require_once ABSPATH . WPINC . '/class-wp-admin-bar.php';

	md_test_as( $user_id );
	$bar = new WP_Admin_Bar;
	do_action( 'admin_bar_menu', $bar );

	return $bar->get_node( 'md_bookshelf-settings-link' );
}

$crashed = false;

try {
	md_test_main_query( array( 'category_name' => array( 'x' ) ) );
}
catch ( TypeError $error ) {
	$crashed = $error->getMessage();
}

md_test( 'an array category_name doesn\'t crash', $crashed === false, $crashed );

$shelf = array();

foreach ( array( 'Sticky', 'Hidden', 'Plain' ) as $title )
	$shelf[$title] = wp_insert_post( array( 'post_type' => 'bookshelf', 'post_title' => $title, 'post_status' => 'publish', 'post_author' => $admin ) );

stick_post( $shelf['Sticky'] );
md_test_call( 'update-settings', array( 'scope' => 'post_type:bookshelf', 'group' => 'loop', 'values' => array( 'loop_type' => null, 'exclude_posts' => (string) $shelf['Hidden'] ) ) );
$shelf_query = md_test_main_query( array( 'post_type' => 'bookshelf' ) );
$shelf_counts = array_count_values( wp_list_pluck( $shelf_query->posts, 'ID' ) );
md_test( 'a sticky post shows once with exclusions set', ( $shelf_counts[$shelf['Sticky']] ?? 0 ) === 1 && empty( $shelf_counts[$shelf['Hidden']] ), $shelf_counts );

$bar_editor = wp_insert_user( array( 'user_login' => 'bar_editor', 'user_pass' => wp_generate_password(), 'user_email' => 'bar_editor@example.test', 'role' => 'editor' ) );
md_test( 'admins get the archive\'s settings link', (bool) md_test_settings_link( $admin ) );
md_test( 'editors don\'t get a link they can\'t open', ! md_test_settings_link( $bar_editor ) );
md_test_as( $admin );

// A Like keeps other values' backslashes

$slashed = wp_insert_post( array( 'post_title' => 'Slashed', 'post_status' => 'publish', 'post_author' => $admin ) );
update_post_meta( $slashed, 'marketers_delight', wp_slash( array( 'featured_media' => array( 'custom_html' => 'C:\\new' ) ) ) );
$like = new WP_REST_Request( 'POST', "/md/v1/share/posts/$slashed/like" );
$like->set_param( 'type', 'post' );
md_test_as( 0 );
$liked = rest_do_request( $like );
md_test_as( $admin );
wp_cache_delete( $slashed, 'post_meta' );
md_test( 'a Like keeps backslashes', $liked->get_status() === 200 && ( get_post_meta( $slashed, 'marketers_delight', true )['featured_media']['custom_html'] ?? '' ) === 'C:\\new', $liked->get_data() );

// Export and import

$export = md_test_call( 'export-settings', array( 'sections' => array( 'design' ) ) );
md_test( 'export design', array_keys( $export['sections'] ?? array() ) === array( 'design' ), $export );
$full = md_test_call( 'export-settings' );
md_test( 'export full', ( $full['format'] ?? '' ) === 'md-export' && strpos( (string) wp_json_encode( $full ), 'marketers_delight_license' ) === false );
$check = md_test_call( 'import-settings', array( 'file' => $full, 'check' => true ) );
md_test( 'import check', ! empty( $check['parts'] ), $check );
md_test_call( 'update-settings', array( 'scope' => 'site', 'group' => 'settings', 'values' => array( 'sidebars' => array( 'side-b' => null ) ) ) );
$import = md_test_call( 'import-settings', array( 'file' => $full ) );
md_test( 'import', ! empty( $import['imported'] ) && strpos( (string) wp_json_encode( $import['skipped'] ?? array() ), '`' ) === false, $import );
$settings = md_test_call( 'get-settings', array( 'scope' => 'site', 'group' => 'settings' ) );
md_test( 'import restored', isset( $settings['values']['sidebars']['side-b'] ), $settings );
md_test( 'bad file', is_wp_error( md_test_call( 'import-settings', array( 'file' => array( 'format' => 'nope', 'version' => 1 ) ) ) ) );

// Least privilege

md_test_as( $author );

foreach ( array(
	'update-settings' => array( 'scope' => 'site', 'group' => 'layout', 'values' => array( 'sidebar' => array( 'remove' => true ) ) ),
	'export-settings' => null,
	'update-menu' => array( 'menu' => 'Main' ),
	'update-widgets' => array( 'sidebar' => 'side-b' ),
	'set-dropin-status' => array( 'dropin' => 'footnotes', 'active' => false ),
	'save-term' => array( 'taxonomy' => 'category', 'name' => 'S' )
) as $name => $input )
	md_test( "author refused $name", is_wp_error( md_test_call( $name, $input ) ) );

$own = md_test_call( 'save-content', array( 'type' => 'post', 'title' => 'Own post', 'status' => 'draft' ) );
md_test( 'author creates own post', ! empty( $own['id'] ), $own );
md_test( 'author can\'t edit others', is_wp_error( md_test_call( 'save-content', array( 'id' => $blog_id, 'title' => 'hacked' ) ) ) );

md_test_report();
