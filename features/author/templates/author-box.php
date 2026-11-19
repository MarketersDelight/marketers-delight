<footer class="author-box post-footer item">

	<div class="wrap">

		<div class="author-meta">

			<?php if ( $has_avatar ) : ?>

			<div class="author-avatar">
				<?php echo get_avatar( $author_id, 80 ); ?>
			</div>

			<?php endif; ?>

			<div class="author-heading">
				<p class="author-label lede"><?php echo esc_html( $label ); ?></p>
				<h3 class="author-title"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></h3>

				<?php if ( $subtitle ) : ?>
				<p class="author-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>

		</div>

		<?php if ( ! empty( $description ) ) : ?>

		<div class="author-description">
			<?php echo wpautop( $description ); ?>
		</div>

		<?php endif; ?>

		<?php if ( $website_name || $sites || $profiles ) : ?>

		<div class="author-links">

			<?php if ( $website_name || $sites ) : ?>

			<ul class="author-sites">

				<?php if ( $website_name ) : ?>

				<li class="author-website"><a href="<?php echo esc_url( $website ); ?>" class="author-link website" rel="me noopener" target="_blank"><?php echo md_icon( 'url' ) . esc_html( $website_name ); ?></a></li>

				<?php endif; ?>

				<?php foreach ( $sites as $key => $link ) : ?>

				<li><a href="<?php echo esc_url( $link['url'] ); ?>" class="author-link <?php echo esc_attr( $key ); ?>" rel="me noopener" target="_blank"><?php echo md_icon( $link['icon'] ) . esc_html( $link['label'] ); ?></a></li>

				<?php endforeach; ?>

			</ul>

			<?php endif; ?>

			<?php if ( $profiles ) : ?>

			<ul class="author-profiles">

				<?php foreach ( $profiles as $key => $link ) : ?>

				<li><a href="<?php echo esc_url( $link['url'] ); ?>" class="author-link <?php echo esc_attr( $key ); ?>" rel="me noopener" target="_blank"><?php echo md_icon( $link['icon'] ) . esc_html( $link['label'] ); ?></a></li>

				<?php endforeach; ?>

			</ul>

			<?php endif; ?>

		</div>

		<?php endif; ?>

	</div>

</footer>
