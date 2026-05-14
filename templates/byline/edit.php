<?php edit_post_link(
	md_icon( 'pencil', array( 'classes' => 'circle-icon' ) ).
	( ! empty( $fields['name'] ) ? '<span class="byline-label">' . esc_html( $fields['name'] ) . '</span>' : null ),
	'<span class="byline-edit byline-item">',
	'</span>'
); ?>