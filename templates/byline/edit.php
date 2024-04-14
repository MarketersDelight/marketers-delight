<?php edit_post_link(
	md_icon( 'pencil' ) . ( ! empty( $fields['title'] ) ? ' ' . esc_html( $fields['title'] ) : null ),
	'<span class="byline-edit byline-item">',
	'</span>'
); ?>