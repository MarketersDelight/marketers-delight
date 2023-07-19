<form action="<?php echo home_url( '/' ); ?>" method="GET" class="<?php echo esc_attr( $classes ); ?>" role="search" >

	<div class="form-inputs">

		<span class="trigger trigger-search" data-md-parent="<?php echo esc_attr( $parent ); ?>">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>

		<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" class="search-input form-input" placeholder="<?php echo md_text_field( $placeholder ); ?>" required />

	</div>

	<button type="submit" class="search-submit form-submit"><?php echo md_icon( 'search' ); ?></button>

</form>

<?php wp_add_inline_script( 'marketers-delight', 'MD.searchToggle();' );