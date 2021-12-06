<nav class="breadcrumbs">
	<div class="inner">

		<a class="mr-small" href="<?php echo get_site_url(); ?>"><?php echo get_bloginfo( 'name' ); ?></a>
	
		<?php echo md_icon( 'angle-right', array( 'classes' => 'mr-small' ) ); ?>
		
		<?php if ( ! is_search() && ! is_page() && ! is_404() ) : ?>
			<a class="mr-small" href="<?php echo get_post_type_archive_link( $post_type ); ?>"><?php echo $post_type_title; ?></a>
			<?php if ( ! empty( $category_url ) && ( is_singular() || ( is_tax() || is_category() || is_tag() || is_author() ) ) ) : ?>
				<?php echo md_icon( 'angle-right', array( 'classes' => 'mr-small' ) ); ?>
			<?php endif; ?>
		<?php endif; ?>
	
		<?php if ( ! empty( $category_url ) && ( ! is_post_type_archive() && ! is_home() && ! is_page() && ! is_search() && ! is_author() ) && ( is_singular() || is_category() || is_tax() ) ) : ?>
			<a href="<?php echo esc_url( $category_url ); ?>" class="mr-small"><?php echo esc_html( $category_title ); ?></a>
		<?php endif; ?>
	
		<?php if ( is_author() ) : ?>
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a>	
		<?php endif; ?>
	
		<?php if ( is_search() ) : ?>
			<span class="mr-small"><?php echo __( 'Search results', 'md' ); ?></span>
			<?php echo md_icon( 'angle-right', array( 'classes' => 'mr-small' ) ); ?>
			<span><?php echo get_search_query(); ?></span>
		<?php endif; ?>
	
		<?php if ( is_404() ) : ?>
			<span class="mr-small"><?php echo __( 'Nothing Found', 'md' ); ?></span>
		<?php endif; ?>
	
		<?php if ( is_singular() || is_search() || is_404() ) : ?>
			<?php if ( is_page() ) : ?>
				<?php if ( wp_get_post_parent_id( $post_id ) ) :
					$parent_id = wp_get_post_parent_id( $post_id );
				?>
					<span class="mr-small"><?php echo get_the_title( $parent_id ); ?></span>
					<?php echo md_icon( 'angle-right', array( 'classes' => 'mr-small' ) ); ?>
				<?php endif; ?>
				<?php the_title(); ?>
			<?php endif; ?>
			<?php echo md_icon( 'angle-down' ); ?>
		<?php endif; ?>
	
	</div>
</nav>