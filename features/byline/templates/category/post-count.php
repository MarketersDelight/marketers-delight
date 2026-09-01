<?php

$category = $args['category'] ?? null;
$count = $category->count ?? 0;

foreach ( get_term_children( $category->term_id, $category->taxonomy ) as $child_id )
	$count += get_term( $child_id, $category->taxonomy )->count ?? 0;

if ( ! empty( $fields['settings']['hide'] ) && $count === 0 )
	return;

$singular = ! empty( $fields['title'] ) ? $fields['title']  : __( 'post', 'md' );
$plural = ! empty( $fields['label'] ) ? $fields['label'] : __( 'posts', 'md' );
$label = sprintf( _n( "%s $singular", "%s $plural", $count, 'md' ), number_format_i18n( $count ) );

?>

<span class="byline-post-count">

	<?php if ( ! empty( $fields['settings']['label'] ) ) : ?>
	<span class="byline-label"><?php echo esc_html( ! empty( $fields['name'] ) ? $fields['name'] : __( 'Posts:', 'md' ) ); ?></span>
	<?php endif; ?>

	<?php echo esc_html( $label ); ?>

</span>
