<div class="columns wide">

<?php foreach ( md_filter_footer_columns() as $col ) : ?>

<?php if ( is_active_sidebar( "md-footer-col-$col" ) ) : ?>

	<div class="<?php echo esc_attr( $classes ); ?>">

		<?php dynamic_sidebar( "md-footer-col-$col" ); ?>

	</div>

<?php endif; ?>

<?php endforeach; ?>

</div>