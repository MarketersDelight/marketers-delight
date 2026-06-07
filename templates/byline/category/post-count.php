<?php
$category = $args['category'] ?? null;
$count = $category->count ?? 0;
$label = sprintf( _n( '%s post', '%s posts', $count, 'md' ), number_format_i18n( $count ) );
?>
<span class="byline-post-count"><?php echo esc_html( $label ); ?></span>
