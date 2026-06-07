<?php
$category = $args['category'] ?? null;

if ( ! $category ) return;

$recent = new WP_Query( array(
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'orderby'        => 'modified',
	'order'          => 'DESC',
	'no_found_rows'  => true,
	'tax_query'      => array( array(
		'taxonomy' => $category->taxonomy,
		'field'    => 'term_id',
		'terms'    => $category->term_id
	) )
) );

if ( ! $recent->have_posts() ) return;

$date = get_the_modified_date( get_option( 'date_format' ), $recent->posts[0] );
?>
<span class="byline-last-updated"><?php echo esc_html( $date ); ?></span>
