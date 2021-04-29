<div<?php echo md_email_attrs( 'uid', $fields ); ?> class="email-form-wrap <?php echo esc_attr( $classes ); ?>" <?php echo md_style( array( 'bg_color' => $fields['email_bg_color'], 'color' => $fields['email_text_color'], 'bg_image' => $fields['email_image'] ) ); ?>>
	<div class="email-form-inner<?php echo esc_attr( $inner_classes ); ?>">

		<?php if ( $title || $desc ) : ?>
			<div class="email-form-intro mb-single">
				<?php if ( $title ) : ?>
					<?php echo $before_title . $title . $after_title; ?>
				<?php endif; ?>
				<?php if ( $desc ) : ?>
					<div class="email-form-intro-desc">
						<?php echo wpautop( $desc ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( empty( $code ) ) : ?>

			<form action="<?php echo md_email_action( $service, $list ); ?>" method="post" class="email-form <?php echo esc_attr( $form_classes ); ?>"<?php echo md_email_attrs( 'form', $fields ); ?>>

				<?php echo md_email_inputs( $fields ); ?>

				<?php if ( ! empty( $fields['email_form_title'] ) ) : ?>
					<div class="email-form-title small-title mb-single text-center">
						<?php echo md_text_field( $fields['email_form_title'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $fields['email_input']['name'] ) ) : ?>
					<input type="text" class="form-input form-input-name" name="<?php echo md_email_input( 'name', $service ); ?>" placeholder="<?php echo esc_attr( $fields['email_name_label'] ); ?>" required />
				<?php endif; ?>

				<input type="email" class="form-input form-input-email" name="<?php echo md_email_input( 'email', $service ); ?>" placeholder="<?php echo esc_attr( $fields['email_email_label'] ); ?>" required />

				<button class="email-form-submit form-submit button-loading mb-half<?php echo trim( esc_attr( $submit_classes ) ); ?>"<?php echo $submit_style; ?><?php echo md_email_attrs( 'submit', $fields ); ?>><?php echo esc_attr( $fields['email_submit_text'] ); ?> <i class="<?php echo md_icon( 'loading', true ); ?>"></i></button>

				<?php if ( ! empty( $fields['email_form_footer'] ) || is_customize_preview() ) : ?>
					<div class="email-form-footer mb-single">
						<?php echo wpautop( $fields['email_form_footer'] ); ?>
					</div>
				<?php endif; ?>

			</form>

			<?php if ( $service == 'convertkit' ) : ?>
				<div id="ck_error_msg" style="display: none;">
					<p class="required"><?php echo md_icon( 'cancel' ); ?> <?php echo __( 'There was an error submitting your subscription. Please try again.', 'md' ); ?></p>
				</div>
				<div id="ck_success_msg" style="display: none;">
					<p class="block-half" style="background-color: #22A340; border-radius: 3px; color: #fff;"><?php echo md_icon( 'ok' ); ?> <?php echo __( 'Thanks for joining! Check your email to complete your subscription', 'md' ); ?></p>
				</div>
				<script src="https://cdn.convertkit.com/assets/CKJS4.js?v=21"></script>
			<?php elseif ( $service == 'mailerlite' ) : ?>
				<div class="ml-form-successBody ml-block-success row-success" style="display:none">
					<div class="ml-form-successContent">
					<p class="block-half" style="background-color: #22A340; border-radius: 3px; color: #fff;"><?php echo md_icon( 'ok' ); ?> <?php echo __( 'Thank you for joining! Check your email to complete your subscription.', 'md' ); ?></p>
					</div>
				</div>
				<?php if ( ! empty( $fields['email_thank_you'] ) ) : ?>
					<script>
						function ml_webform_success_<?php echo esc_attr( $uid ); ?>() {
							try { window.top.location.href = '<?php echo esc_url( $fields['email_thank_you'] ); ?>'; }
							catch( e ) { window.location.href = '<?php echo esc_url( $fields['email_thank_you'] ); ?>'; }
						}
					</script>
				<?php endif; ?>
				<img src="https://track.mailerlite.com/webforms/o/<?php echo esc_attr( $uid ); ?>/<?php echo esc_attr( $id ); ?>" width="1" height="1" style="max-width: 1px; max-height: 1px; visibility: hidden; padding: 0; margin: 0; display: block;" border="0">
			<?php endif; ?>

		<?php else : ?>
			<?php echo $code; ?>
		<?php endif; ?>

	</div>
</div>
<?php wp_add_inline_script( 'marketers-delight', 'MD.button();' ); ?>