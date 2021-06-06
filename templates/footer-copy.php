<?php if ( is_active_sidebar( 'footer-copy' ) ) : ?>
	<div class="footer-copy">
		<div class="inner">
			<?php md_hook_before_foote_copy(); ?>
			<?php dynamic_sidebar( 'footer-copy' ); ?>
			<?php md_hook_after_foote_copy(); ?>
		</div>
	</div>
<?php endif; ?>