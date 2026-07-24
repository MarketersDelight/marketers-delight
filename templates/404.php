<p class="intro"><?php echo __( 'The page you are looking for doesn\'t exist, or it has been moved. Please try searching using the form below', 'md' ); ?>.</p>

<?php

$args = array(
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>'
);

the_widget( 'WP_Widget_Search', array(
	'title' => __( 'Search this website', 'md' )
), $args );

the_widget( 'WP_Widget_Recent_Posts', array(
	'title' => __( 'Browse Latest Posts', 'md' )
), $args );

the_widget( 'WP_Widget_Archives', array(
	'title' => __( 'Browse by Month', 'md' ),
	'dropdown' => true
), $args );

?>
