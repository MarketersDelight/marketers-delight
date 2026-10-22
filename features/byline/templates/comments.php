<?php
$post_id = isset( $fields['post_id'] ) ? $fields['post_id'] : get_the_ID();

if ( ! md_has_comments( $post_id ) )
	return;

$comments = get_comments_number( $post_id );
$count = number_format_i18n( $comments );
$label = _nx( 'comment', 'comments', $comments, 'Number of comments', 'md' );

if ( $comments == '0' && ! empty( $fields['settings']['hide'] ) )
	return;
?>

<span class="<?php echo md_byline_classes( $fields, 'byline-comments' ); ?>">
	<a href="<?php echo get_comments_link( $post_id ); ?>" class="clickout">
		<?php
			echo md_icon( 'chat' ) . '<span class="md-byline-label">';

			if ( $comments == '0' && ! empty( $fields['title'] ) )
				$text = $fields['title'];
			elseif ( ! empty( $fields['name'] ) )
				$text = $fields['name'];
			else
				$text = '{count}' . ( ! empty( $fields['settings']['label'] ) ? ' {label}' : '' );

			echo esc_html( strtr( $text, array(
				'{count}' => $count,
				'{label}' => $label,
				'{comments}' => "$count $label"
			) ) );

			echo '</span>';
		?>
	</a>
</span>
