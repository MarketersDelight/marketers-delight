<div class="header-logo">

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo get_bloginfo( 'name' ); ?>" rel="home">
		<?php if ( md_has_custom_logo() ) : ?>

			<<?php echo md_logo_html(); ?> class="logo">
				<?php md_the_logo(); ?>
			</<?php echo md_logo_html(); ?>>

		<?php endif; ?>

		<?php if ( md_has_site_title() || md_has_tagline() ) : ?>

			<div class="header-details">

				<?php if ( md_has_site_title() ) : ?>
					<p class="site-title"><?php echo md_site_title(); ?></p>
				<?php endif; ?>

				<?php if ( md_has_tagline() ) : ?>
					<p class="tagline"><?php echo md_site_tagline(); ?></p>
				<?php endif; ?>

			</div>

		<?php endif; ?>

	</a>

	<?php md_hook_header_logo_bottom(); ?>

</div>