<div class="header-logo">

	<?php if ( md_has_logo() ) : ?>
		<<?php md_logo_html(); ?> class="logo">
			<?php if ( md_has_custom_logo() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo"  title="<?php echo get_bloginfo( 'name' ); ?>" rel="home"><?php md_the_logo(); ?></a>
			<?php endif; ?>
			<?php if ( md_has_site_title() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title"  title="<?php echo get_bloginfo( 'name' ); ?>" rel="home"><?php echo get_bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</<?php echo md_logo_html(); ?>>
	<?php endif; ?>

	<?php if ( md_has_tagline() ) : ?>
		<p class="tagline" itemprop="description"><?php bloginfo( 'description' ); ?></p>
	<?php endif; ?>

	<?php md_hook_header_logo_bottom(); ?>

</div>