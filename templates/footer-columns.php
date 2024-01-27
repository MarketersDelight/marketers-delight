<div class="columns wide">

<?php foreach ( $columns as $col ) : ?>

<?php if ( is_active_sidebar( "md-footer-col-$col" ) ) : ?>

	<div class="entry f<?php echo esc_attr( count( $columns ) ); ?>">

		<?php dynamic_sidebar( "md-footer-col-$col" ); ?>

	</div>

<?php endif; ?>

<?php endforeach; ?>

</div>
