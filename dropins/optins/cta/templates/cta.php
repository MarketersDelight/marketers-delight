<div class="cta-box <?php echo esc_attr( $classes ); ?>"<?php echo md_style( $style ); ?>>
	<div class="inner">
		<div class="cta-inner format">
			<?php if ( ! empty( $fields['title'] ) ) : ?>
				<<?php echo $title_html; ?> class="cta-title <?php echo esc_attr( $title_classes ); ?>"><?php echo md_text_field( $fields['title'] ); ?></<?php echo $title_html; ?>>
			<?php endif; ?>
			<?php if ( ! empty( $fields['image']['id'] ) ) : ?>
				<div class="cta-image <?php echo ! empty( $fields['image_alignment'] ) ? $fields['image_alignment'] : 'alignnone'; ?>"<?php echo md_style( array( 'width' => $fields['image_width'], 'width_unit' => '%' ) ); ?>>
					<?php echo wp_get_attachment_image( $fields['image']['id'], false, 'full', array(
						'class' => $image_classes
					) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $fields['text'] ) ) : ?>
				<div class="<?php echo esc_attr( $text_classes ); ?> mb-single"<?php echo md_style( array( 'color' => $fields['text_sec_color'] ) ); ?>>
					<?php echo wpautop( $fields['text'] ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $fields['cta_type'] == 'button' ) : ?>
				<?php echo md_button( array(
					'action' => $fields['button_type'],
					'link' => $fields['button_url'],
					'popup' => $fields['button_popup'],
					'bg_color' => $fields['button_color'],
					'color' => $fields['button_text_color'],
					'text' => $fields['button_text']
				) ); ?>
			<?php elseif ( $fields['cta_type'] == 'email' && $fields['email_list'] != 'custom_html' ) : ?>
				<?php md_email_form(
					array(
						'email_list' => $fields['email_list'],
						'email_input' => array( 'name' => $fields['name'] ),
						'email_name_label' => $fields['email_name_label'],
						'email_email_label' => $fields['email_email_label'],
						'email_submit_text' => $fields['email_submit_text'],
						'email_form_style' => array( 'attached' => $fields['attached'] ),
						'email_form_footer' => $fields['email_form_footer'],
						'email_thank_you' => $fields['email_thank_you']
					),
					array(
						'email_submit_bg_color' => $fields['button_color'],
						'email_submit_color' => $fields['button_text_color']
					)
				); ?>
			<?php elseif ( $fields['cta_type'] == 'html' && ! empty( $fields['html'] ) ) : ?>
				<?php if ( ! empty( $fields['html_format']['wp'] ) ) : ?>
					<?php echo apply_filters( 'the_content', $fields['html'] ); ?>
				<?php else : ?>
					<?php echo $fields['html']; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>