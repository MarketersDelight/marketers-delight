<div class="columns columns-<?php echo esc_attr( $count ); ?>">

<?php foreach ( $columns as $col ) : ?>

	<?php if ( is_active_sidebar( "md-footer-col-$col" ) ) : ?>

	<div class="entry">

		<?php dynamic_sidebar( "md-footer-col-$col" ); ?>

	</div>

	<?php endif; ?>

<?php endforeach; ?>

</div>
