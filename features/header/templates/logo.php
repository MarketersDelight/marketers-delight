<div class="site-title">

	<?php echo md_custom_logo(); ?>

	<?php if ( md_has_site_title() || md_has_tagline() ) : ?>

	<div class="site-details">

		<?php if ( md_has_site_title() ) : ?>
		<p class="site-name">
			<a href="<?php echo home_url( '/' ); ?>"><span><?php echo md_site_title(); ?></span></a>
		</p>
		<?php md_hook_after_site_title(); endif; ?>

		<?php if ( md_has_tagline() ) : ?>
		<p class="tagline"><?php echo md_site_tagline(); ?></p>
		<?php endif; ?>

		<?php md_hook_header_details(); ?>

	</div>

	<?php endif; ?>

	<?php md_hook_header_logo_bottom(); ?>

</div>
