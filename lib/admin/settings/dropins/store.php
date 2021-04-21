<?php
/**
 * Create admin page to show off MD store and available
 * downloads. Will eventually connect to MD.com.
 *
 * @since 5.3
 */

class md_store extends md_api {

	/**
	 * List of official MD Drop-ins for download and install.
	 */

	public $dropins = array(
		'docs' => array(
			'name' => 'Documentation',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.1',
			'url' => 'https://marketersdelight.com/dropins/docs/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2020/12/docs-library-wordpress.jpg',
			'callback' => 'md_docs'
		),
		'glossary' => array(
			'name' => 'Glossary',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.3',
			'url' => 'https://marketersdelight.com/dropins/glossary/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/11/glossary-dropin-wordpress-marketers-delight.png',
			'callback' => 'md_glossary'
		),
		'beacon' => array(
			'name' => 'Beacon',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.2',
			'url' => 'https://marketersdelight.com/dropins/beacon/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/11/beacon-wordpress.jpg',
			'callback' => 'md_beacon'
		),
		'testimonials' => array(
			'name' => 'Testimonials',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.3',
			'url' => 'https://marketersdelight.com/dropins/testimonials/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/04/wordpress-testimonials.jpg',
			'callback' => 'md_testimonials'
		),
		'related-posts' => array(
			'name' => 'Related Posts',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.1',
			'url' => 'https://marketersdelight.com/dropins/related-posts/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/related-posts-wordpress.jpg',
			'callback' => 'md_related_posts'
		),
		'page-blocks' => array(
			'name' => 'Page Blocks',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.2',
			'url' => 'https://marketersdelight.com/dropins/page-blocks/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/page-blocks-content-editor.jpg',
			'callback' => 'md_page_blocks'
		),
		'gallery-blocks' => array(
			'name' => 'Gallery Blocks',
			'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features.',
			'author' => 'Alex',
			'version' => '1.2',
			'url' => 'https://marketersdelight.com/dropins/gallery-blocks/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/gallery-blocks-dropin.jpg',
			'callback' => 'md_gallery_blocks'
		)
	);

	/**
	 * Register admin page.
	 *
	 * @since 5.3
	 */

	public function register() {
		$count = count( $this->dropins );
		return array(
			'admin_page' => array(
				'name' => sprintf( __( 'All (%s)', 'md' ), $count ),
				'parent' => 'md_dropins'
			)
		);
	}
	
	/**
	 * Build Store admin page.
	 *
	 * @since 5.3
	 */
	
	public function admin_page() { ?>
		<div class="md-store">
			<h2 class="md-title"><?php echo __( 'Get new Drop-ins', 'md' ); ?></h2>
			<p class="md-sep-small"><?php echo __( 'Find and download new features to your website from the official <a href="%s" target="_blank">MD Drop-ins library</a>.', 'md' ); ?></p>
			<div class="columns-3 columns-single columns-flex">
				<?php foreach ( $this->dropins as $dropin => $fields ) :
					$is_installed = function_exists( $fields['callback'] ) || class_exists( $fields['callback'] ) ? true : false;
				?>
					<div class="col<?php echo $is_installed ? ' md-store-item-installed' : ''; ?>">
						<a href="<?php echo $fields['url']; ?>" target="_blank" class="md-store-item-image"><img src="<?php echo $fields['image']; ?>" /></a>
						<div class="col-inner">
							<h3><a href="<?php echo $fields['url']; ?>" target="_blank"><?php echo $fields['name']; ?> <small><?php echo $fields['version']; ?></small></a></h3>
							<?php if ( isset( $fields['description'] ) ) : ?>
								<p><?php echo $fields['description']; ?></p>
							<?php endif; ?>
							<div class="md-store-item-byline md-clear">
								<a href="<?php echo $fields['url']; ?>" class="button" target="_blank"><?php echo __( 'Get now', 'md' ); ?></a>
								<?php if ( $is_installed ) : ?>
									<span class="md-store-item-status"><i class="dashicons dashicons-yes-alt"></i> <?php echo __( 'Installed', 'md' ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php }

}

new md_store;