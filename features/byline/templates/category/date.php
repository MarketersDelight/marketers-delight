<?php
$category = $args['category'] ?? null;

if ( ! $category ) return;

$use_modified  = ! empty( $fields['settings']['alt'] );
$date_format   = get_option( 'date_format' );

$recent = new WP_Query( array(
	'posts_per_page' => 1,
	'post_status' => 'publish',
	'orderby' => $use_modified ? 'modified' : 'date',
	'order' => 'DESC',
	'no_found_rows' => true,
	'tax_query' => array( array(
		'taxonomy' => $category->taxonomy,
		'field' => 'term_id',
		'terms' => $category->term_id
	) )
) );

if ( ! $recent->have_posts() )
	return;

$post = $recent->posts[0];
$post_time = $use_modified ? get_post_modified_time( 'U', false, $post ) : get_post_time( 'U', false, $post );
$date = $use_modified ? get_the_modified_date( $date_format, $post ) : get_the_date( $date_format, $post );
$datetime = $use_modified ? get_the_modified_date( 'c', $post ) : get_the_date( 'c', $post );
$default_name = $use_modified ? __( 'Last updated:', 'md' ) : __( 'Last published:', 'md' );
$icon = $use_modified ? 'clock' : 'calendar';

if ( ! empty( $fields['settings']['relative'] ) )
	$date = sprintf( __( '%s ago', 'md' ), human_time_diff( $post_time, current_time( 'U' ) ) );
?>

<span class="<?php echo md_byline_classes( $fields, 'byline-date' ); ?>">

	<?php echo md_icon( $icon ); ?>

	<?php if ( ! empty( $fields['settings']['label'] ) )
		echo '<span class="byline-label">' . ( ! empty( $fields['name'] ) ? $fields['name'] : $default_name ) . '</span>'; ?>

	<time datetime="<?php echo esc_attr( $datetime ); ?>" title="<?php echo esc_attr( $date ); ?>">
		<?php echo esc_html( $date ); ?>
	</time>

</span>
