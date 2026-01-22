<form action="<?php echo home_url( '/' ); ?>" method="GET" class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! empty( $fields['toggle']['search'] ) ) : ?>

	<div class="triggers">
		<span class="trigger trigger-search" title="<?php echo esc_attr( $title ); ?>" data-md-trigger="search" data-md-parent="<?php echo esc_attr( $parent ); ?>" data-md-location="<?php echo esc_attr( $location ); ?>">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>
	</div>

	<?php endif; ?>

	<div class="inputs">
		<div class="input-field">
			<label for="<?php echo esc_attr( $id ); ?>" class="input-icon"><?php echo md_icon( 'search' ); ?></label>
			<input type="search" name="s" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" class="input" placeholder="<?php echo md_text_field( $placeholder ); ?>" required />
		</div>
	</div>

	<button type="submit" class="submit">
		<span class="button-text"><?php echo md_text_field( $submit_text ); ?></span>
	</button>

</form>