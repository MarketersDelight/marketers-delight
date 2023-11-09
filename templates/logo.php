<div class="header-logo">

	<?php if ( md_has_custom_logo() ) : ?>

		<<?php echo md_logo_html(); ?> class="logo">
			<a href="/"><?php md_the_logo(); ?></a>
		</<?php echo md_logo_html(); ?>>

	<?php endif; ?>

	<?php if ( md_has_site_title() || md_has_tagline() ) : ?>

		<div class="header-details">

			<?php if ( md_has_site_title() ) : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo get_bloginfo( 'name' ); ?>" rel="home"><?php echo md_site_title(); ?></a></p>
				<?php md_hook_after_site_title(); ?>
			<?php endif; ?>

			<?php if ( md_has_tagline() ) : ?>
				<p class="tagline"><?php echo md_site_tagline(); ?></p>
			<?php endif; ?>

			<?php md_hook_header_details(); ?>

		</div>

	<?php endif; ?>

	<?php md_hook_header_logo_bottom(); ?>

</div>