<?php
/**
 * Tests the author box's profile links: X from the profile, X usernames
 * turned into links, and the links filter. The website has its own line.
 *
 * @since 6.2.4
 */

class AuthorBoxTest extends MD_InheritanceTestCase {

	public function test_x_link_is_kept_as_given_and_the_website_is_left_to_its_own_line() {
		$GLOBALS['__test_user_keys'][3] = array( 'user_url' => 'https://example.com', 'twitter' => 'https://x.com/someone' );

		$this->assertSame( array( 'twitter' => array( 'label' => 'X', 'url' => 'https://x.com/someone', 'icon' => 'x' ) ), md_author_box_links( 3 ) );
	}

	public function test_an_x_username_becomes_a_link() {
		$GLOBALS['__test_user_keys'][3] = array( 'twitter' => '@someone' );

		$this->assertSame( 'https://x.com/someone', md_author_box_links( 3 )['twitter']['url'] );
	}

	public function test_an_author_with_no_profiles_has_no_links() {
		$GLOBALS['__test_user_keys'][3] = array();

		$this->assertSame( array(), md_author_box_links( 3 ) );
	}

	public function test_the_filter_can_add_links() {
		$GLOBALS['__test_user_keys'][3] = array( 'twitter' => 'someone' );
		md_test_set_filter( 'md_author_box_links', function( $links, $author_id ) {
			$links['youtube'] = array( 'label' => 'YouTube', 'url' => "https://youtube.com/@author$author_id", 'icon' => 'youtube' );

			return $links;
		} );

		$links = md_author_box_links( 3 );

		$this->assertSame( array( 'twitter', 'youtube' ), array_keys( $links ) );
		$this->assertSame( 'https://youtube.com/@author3', $links['youtube']['url'] );
	}

	public function test_profile_groups_need_their_flag_to_let_non_admins_in() {
		$this->assertSame( 'manage_options', md_meta_group_capability( 'user', array() ) );
		$this->assertSame( 'edit_user', md_meta_group_capability( 'user', array( 'edit_user' => true ) ) );
		$this->assertSame( 'edit_post', md_meta_group_capability( 'post', array( 'edit_post' => true ) ) );
		$this->assertSame( 'manage_options', md_meta_group_capability( 'post', array( 'edit_user' => true ) ) );
	}

}
