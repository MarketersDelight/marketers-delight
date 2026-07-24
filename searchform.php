<?php
	$id = isset( $args['id'] ) ? $args['id'] : 's';
	$location = isset( $args['location'] ) ? $args['location'] : '';
	$parent = isset( $args['parent'] ) ? $args['parent'] : 'header';
	$title = isset( $args['title'] ) ? $args['title'] : __( 'Search', 'md' );
	$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : __( 'Type to search...', 'md' );
	$submit_text = isset( $args['submit_text'] ) ? $args['submit_text'] : __( 'Search', 'md' );

	$args['classes'][] = 'search-form';
	$args['classes'][] = 'form-icons';

	if ( ! empty( $args['toggle']['search'] ) )
		$args['classes'][] = 'form-toggle';
	else
		$args['classes'][] = 'inline';

	if ( ! empty( $args['toggle']['hide_label'] ) )
		$args['classes'][] = 'hide-label';

	if ( ! empty( $args['toggle']['hide_label_mobile'] ) )
		$args['classes'][] = 'hide-label-mobile';

	$classes = join( ' ', $args['classes'] );
?>

<form action="<?php echo home_url( '/' ); ?>" method="GET" class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! empty( $args['toggle']['search'] ) ) : ?>

	<div class="triggers">
		<span class="trigger trigger-search" title="<?php echo esc_attr( $title ); ?>" data-md-trigger="search" data-md-parent="<?php echo esc_attr( $parent ); ?>" data-md-location="<?php echo esc_attr( $location ); ?>">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo wp_kses_data( $title ); ?></span>
		</span>
	</div>

	<?php endif; ?>

	<div class="inputs">
		<div class="input-field">
			<label for="<?php echo esc_attr( $id ); ?>" class="input-icon"><?php echo md_icon( 'search' ); ?></label>
			<input type="search" name="s" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" class="input" placeholder="<?php echo wp_kses_data( $placeholder ); ?>" required />
		</div>
	</div>

	<button type="submit" class="submit">
		<span class="button-text"><?php echo wp_kses_data( $submit_text ); ?></span>
	</button>

</form>
