<?php if ( is_active_sidebar( 'footer-copy' ) ) : ?>
	<div class="footer-copy">
		<div class="inner">
			<?php md_hook_before_footer_copy(); ?>
			<?php dynamic_sidebar( 'footer-copy' ); ?>
			<?php md_hook_after_footer_copy(); ?>
		</div>
	</div>
<?php endif; ?>