<nav class="breadcrumbs">
	<div class="inner">

		<a href="<?php echo get_site_url(); ?>"><?php echo get_bloginfo( 'name' ); ?></a>

		<?php echo md_icon( 'angle-right' ); ?>

		<?php if ( ! empty( $post_type ) && ! is_search() && ! is_page() && ! is_404() ) :
			$archive_html = ! is_home() && ! is_post_type_archive() ? 'a' : 'span';
			$archive_href = ! is_home() && ! is_post_type_archive() ? ' href="' . get_post_type_archive_link( $post_type ) . '"' : '';
		?>
			<<?php echo $archive_html . $archive_href; ?>><?php echo esc_html( $post_type_title ); ?></<?php echo $archive_html; ?>>
			<?php if ( ! empty( $category_url ) && ( is_singular() || ( is_tax() || is_category() || is_tag() || is_author() || is_date() ) ) ) : ?>
				<?php echo md_icon( 'angle-right' ); ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $category_url ) && ( ! is_post_type_archive() && ! is_home() && ! is_page() && ! is_search() && ! is_author() ) && ( is_singular() || is_category() || is_tag() || is_tax() ) ) :
			$cat_html = is_singular() ? 'a' : 'span';
			$cat_href = is_singular() ? ' href="' . esc_url( $category_url ) . '"' : '';
		?>
			<<?php echo $cat_html . $cat_href; ?>><?php echo esc_html( $category_title ); ?></<?php echo $cat_html; ?>>
		<?php endif; ?>

		<?php if ( is_year() ) : ?>
			<?php echo get_the_date( 'Y' ); ?>
		<?php endif; ?>

		<?php if ( is_month() ) : ?>
			<?php echo get_the_date( 'F Y' ); ?>
		<?php endif; ?>

		<?php if ( is_day() ) : ?>
			<?php echo get_the_date( 'F j, Y' ); ?>
		<?php endif; ?>

		<?php if ( is_author() ) : ?>
			<?php echo get_the_author_meta( 'display_name' ); ?>
		<?php endif; ?>

		<?php if ( is_search() ) : ?>
			<span class="breadcrumb-text"><?php echo __( 'Search results', 'md' ); ?></span>
			<?php echo md_icon( 'angle-right' ); ?>
			<span><?php echo get_search_query(); ?></span>
		<?php endif; ?>

		<?php if ( is_404() ) : ?>
			<span class="breadcrumb-text"><?php echo __( 'Nothing Found', 'md' ); ?></span>
		<?php endif; ?>

		<?php if ( is_singular() || is_search() || is_404() ) : ?>
			<?php if ( is_page() ) : ?>
				<?php if ( wp_get_post_parent_id( $post_id ) ) :
					$parent_id = wp_get_post_parent_id( $post_id );
				?>
					<span class="breadcrumb-text"><?php echo get_the_title( $parent_id ); ?></span>
					<?php echo md_icon( 'angle-right' ); ?>
				<?php endif; ?>
				<?php the_title(); ?>
			<?php endif; ?>
			<?php echo md_icon( 'angle-down' ); ?>
		<?php endif; ?>

	</div>
</nav>
