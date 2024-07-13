<?php

md_template( 'html' );

md_hook_before_html();

if ( md_has_header() ) : ?>

<header id="header" class="<?php echo md_header_classes(); ?>">

	<?php md_hook_header_top(); ?>

	<div class="inner">

		<?php md_hook_before_header(); ?>

		<div class="<?php echo md_header_wrap_classes(); ?>">
			<?php md_hook_header(); ?>
		</div>

		<?php md_hook_after_header(); ?>

	</div>

	<?php md_hook_header_bottom(); ?>

</header>

<?php endif;

if ( md_filter_template() !== false )
	md_hook_before_content_box();