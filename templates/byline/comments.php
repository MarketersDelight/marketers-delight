<?php
if ( ! md_has_comments() )
	return;

$comments = get_comments_number();
$count = number_format_i18n( $comments );
$label = _nx( 'comment', 'comments', $comments, 'Number of comments', 'md' );

if ( $comments == '0' && ! empty( $fields['settings']['hide'] ) )
	return;
?>

<span class="byline-comments byline-item">
	<a href="<?php echo get_comments_link(); ?>">
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