<?php if ( md_has_comments() ) :
	$comments = get_comments_number();

	if ( $comments == 0 && ! empty( $fields['settings']['hide'] ) )
		return;
?>

<span class="byline-comments byline-item">
	<a href="<?php echo get_comments_link(); ?>">
		<?php
			echo md_icon( 'chat' ) . '<span class="md-byline-label">';

			if ( $comments == 0 && ! empty( $fields['name'] ) )
				echo esc_html( $fields['name'] );
			else {
				echo number_format_i18n( $comments );

				if ( ! empty( $fields['settings']['label'] ) )
					echo _nx( ' comment', ' comments', $comments, 'Number of comments', 'md' );
			}

			echo '</span>';
		?>
	</a>
</span>

<?php endif; ?>
