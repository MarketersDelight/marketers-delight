<form action="<?php echo home_url( '/' ); ?>" method="GET" class="<?php echo esc_attr( $classes ); ?>" role="search" >

	<div class="triggers">

		<span class="trigger trigger-search" title="<?php echo esc_attr( $title ); ?>" data-md-parent="<?php echo esc_attr( $parent ); ?>">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>

	</div>

	<div class="inputs">

		<div class="input-field">
			<label class="input-icon"><?php echo md_icon( 'search' ); ?></label>
			<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" class="input" placeholder="<?php echo md_text_field( $placeholder ); ?>" required />
		</div>

	</div>

	<button type="submit" class="submit">
		<span class="button-text"><?php echo md_text_field( $submit_text ); ?></span>
	</button>

</form>

<?php wp_add_inline_script( 'marketers-delight', 'MD.searchToggle();' );
