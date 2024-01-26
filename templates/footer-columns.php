<div class="columns wide">

<?php foreach ( md_filter_footer_columns() as $col ) : ?>

<?php if ( is_active_sidebar( "md-footer-col-$col" ) ) : ?>

	<div class="entry f3">

		<?php dynamic_sidebar( "md-footer-col-$col" ); ?>

	</div>

<?php endif; ?>

<?php endforeach; ?>

</div>
