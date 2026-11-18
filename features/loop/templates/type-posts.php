<?php

$type_queries = md_loop_type_queries( $loop );
$h = md_loop_heading( 2, $loop );
$h_class = $h !== 'h2' ? ' h2' : '';
$term_link = is_category() || is_tag() || is_tax() ? get_term_link( get_queried_object() ) : '';

if ( ! $type_queries ) {
	if ( empty( $args['query'] ) )
		md_404();

	return false;
}

echo '<div class="' . esc_attr( $loop['categories_classes'] ) . '">';

foreach ( $type_queries as $type => $posts ) :
	$type_object = get_post_type_object( $type );
	$type_link = $term_link && ! is_wp_error( $term_link ) ? add_query_arg( 'post_type', $type, $term_link ) : get_post_type_archive_link( $type );
	$has_more = $type_link && $posts->post_count >= $posts->query_vars['posts_per_page'];
	$type_title = $type_link ? '<a href="' . esc_url( $type_link ) . '">' . esc_html( $type_object->labels->name ) . '</a>' : esc_html( $type_object->labels->name );
?>

<section id="<?php echo esc_attr( $type ); ?>" class="<?php echo esc_attr( $loop['category_classes'] ); ?>">

	<div class="category-title category-title-wrap fl items-start">

		<div class="entry-title grow">

			<<?php echo $h; ?> class="title<?php echo $h_class; ?>"><?php echo $type_title; ?></<?php echo $h; ?>>

		</div>

	</div>

	<?php md_loop( array(
		'post_type' => $type,
		'query' => $posts,
		'loop_type' => 'post_listing',
		'skip_subcategory' => true,
		'no_pagination' => true,
		'nested' => true,
		'page_layout' => array(
			'columns' => $loop['columns'],
			'columns_mobile' => $loop['columns_mobile'] ?? 1,
			'has_sidebar' => ! empty( $loop['has_sidebar'] )
		),
		'heading_offset' => 1
	) ); ?>

	<?php if ( $has_more ) : ?>

	<div class="category-more item byline mt-half">
		<a href="<?php echo esc_url( $type_link ); ?>"><?php echo sprintf( esc_html__( 'See all %s &rarr;', 'md' ), '<strong>' . esc_html( $type_object->labels->name ) . '</strong>' ); ?></a>
	</div>

	<?php endif; ?>

</section>

<?php endforeach;

echo '</div>';