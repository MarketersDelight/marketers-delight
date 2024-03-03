<?php
	$query_args = array( 'query' => $loop );
	$query_classes = array( 'query' );
	$has_sidebar = ! empty( $loop['sidebar']['enable'] ) ? true : false;

	if ( $has_sidebar ) {
		$query_classes[] = 'content-sidebar';

		if ( ! empty( $loop['content_layout'] ) && $loop['content_layout'] == 'sidebar_content' )
			$query_classes[] = 'left';
	}

	if ( ! isset( $loop['is_inline'] ) )
		$query_classes[] = 'format';

	$query_classes = join( ' ', $query_classes );
?>

<div class="<?php echo esc_attr( $query_classes ); ?>">

	<?php echo ! isset( $loop['is_inline'] ) ? '<div class="inner">' : ''; ?>

		<?php if ( ! empty( $loop['title'] ) || ! empty( $loop['description'] ) ) : ?>

		<div class="section-header">

			<?php if ( ! empty( $loop['title'] ) ) : ?>
			<h3 class="title"><?php echo md_text_field( $loop['title'] ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $loop['description'] ) ) : ?>
			<div class="description">
				<?php echo wpautop( $loop['description'] ); ?>
			</div>
			<?php endif; ?>

		</div>

		<?php endif; ?>

		<?php if ( $has_sidebar ) {
			$query_args['has_sidebar'] = true;
			$index = 'sidebar-main';
			$sidebar_class = isset( $loop['sidebar']['sticky'] ) ? ' sticky' : '';
		?>

			<div class="content">
				<?php md_loop( $query_args ); ?>
			</div>

			<div class="sidebar<?php echo esc_attr( $sidebar_class ); ?>">
				<?php dynamic_sidebar( $index ); ?>
			</div>

		<?php }
			else md_loop( $query_args ); ?>

	<?php echo ! isset( $loop['is_inline'] ) ? '</div>' : ''; ?>

</div>
