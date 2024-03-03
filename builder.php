<?php
/**
 * Template Name: Builder Template
 * Template Post Type: post, page
 */

get_header();

if ( have_posts() ) while ( have_posts() ) { the_post(); ?>

<div id="content" class="<?php echo md_content_box_classes(); ?>">

	<?php md_hook_content_box_top(); md_hook_content_top(); ?>

	<main id="content" class="content">

	<?php
		md_hook_before_content();
		do_action( 'builder_template_' . get_the_ID() );
		the_content();
		md_hook_after_content();
	?>

	</main>

	<?php
		get_sidebar();
		md_hook_content_bottom();
		md_hook_content_box_bottom();
	?>

</div>

<?php }

get_footer();