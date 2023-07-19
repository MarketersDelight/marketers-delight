<?php echo $args['before_widget']; ?>

<?php if ( $val['title'] ) : ?>
	<?php echo $args['before_title']; ?><?php echo $val['title']; ?><?php echo $args['after_title']; ?>
<?php endif; ?>

<div id="accordion_<?php echo esc_attr( $args['id'] ); ?>" class="accordion">
	<?php foreach ( $terms as $term ) :
		$articles = new WP_Query( array(
			'fields' => 'ids',
			'post_type' => $post_type,
			'posts_per_page' => ! empty( $val['posts_per_category'] ) ? (int) $val['posts_per_category'] : 5,
			'tax_query' => array( array(
				'taxonomy' => $tax,
				'field' => 'slug',
				'terms' => ! empty( $term->slug ) ? $term->slug : ''
			) )
		) );
		$string = sprintf( __( 'See all <b>%s</b> %1s &rarr;', 'md' ), $term->count, $term->name );
		if ( ! empty( $val['see_more'] ) )
			$string = str_replace( array( '{count}', '{category}' ), array( $term->count, $term->name ), $val['see_more'] );
	?>
		<?php if ( $articles->have_posts() ) : ?>
			<div id="accordion_<?php echo esc_attr( $args['id'] ); ?>_<?php echo $c; ?>" class="accordion-group<?php echo $c == 1 ? ' active' : ''; ?>">
				<div class="accordion-title" data-accordion="<?php echo $c; ?>"><?php echo $term->name; ?></div>
				<div class="accordion-content">
					<ul class="list">
						<?php while ( $articles->have_posts() ) : $articles->the_post();
							$current = $page_id == get_the_ID() ? ' class="current"' : '';
						?>
							<li<?php echo $current; ?>><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
						<?php endwhile; ?>
						<li class="small"><a href="<?php echo get_term_link( $term->term_id ); ?>"><?php echo md_text_field( $string ); ?></a></li>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<?php wp_reset_query(); ?>
	<?php $c++; endforeach; ?>
</div>

<?php echo $args['after_widget']; ?>

<?php wp_add_inline_script( 'marketers-delight', "MD.accordion( 'accordion_" . esc_attr( $args['id'] ) . "' );" ); ?>