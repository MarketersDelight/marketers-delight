<?php md_hook_before_headline_area(); ?>

<div class="<?php echo md_headline_classes(); ?>"<?php echo md_cover_style(); ?>>

	<?php md_hook_before_headline(); ?>

	<<?php echo $h; ?> class="<?php echo esc_attr( $classes ); ?>">

		<?php echo md_title( $title, $permalink ); ?>

	</<?php echo $h; ?>>

	<?php md_hook_after_headline(); ?>

</div>

<?php md_hook_after_headline_area(); ?>