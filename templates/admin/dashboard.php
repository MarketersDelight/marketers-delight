<div class="md-dashboard md-content-wrap-med">

	<div class="columns-70-30 columns-single">

		<div class="col col1 md-sep-small">

			<?php do_action( 'md_hook_settings_col2_top' ); ?>

			<div id="md_update" class="md-sep-small">
				<?php $this->updater(); ?>
			</div>

			<div class="md-widget md-toggle md-sep-small">

				<h3 class="md-widget-title"><?php echo __( 'Site Tools', 'md' ); ?></h3>

				<div class="md-widget-item">

					<div class="md-sep-small">
						<?php $this->fields->field( 'css', array(
							'type' => 'checkbox',
							'label' => __( 'CSS Manager', 'md' ),
							'description' => sprintf( __( 'If using a child theme you can save an extra HTTP request by combining your custom stylesheet with MD\'s stylesheet file. For further optimization you can print your stylesheets inline to your site\'s %s.<br /><br />Note: if you combine your child theme styles you will need to resave the MD settings above to see any changes you make to the file afterwards.', 'md' ), '<code>&lt;head&gt;</code>' ),
							'options' => array(
								'child' => sprintf( __( 'Combine child theme CSS into %s', 'md' ), '<code>style.css</code>' ),
								'inline' => sprintf( __( 'Print %s inline', 'md' ), '<code>style.css</code>' ),
								'optimize' => __( 'Enable CSS Optimizer (load CSS conditionally per page)', 'md' )
							)
						) ); ?>
					</div>

					<div class="md-sep-small">
						<?php $this->fields->field( 'css_unit', array(
							'type' => 'select',
							'label' => __( 'CSS Units', 'md' ),
							'description' => __( 'Choose the CSS unit for typography and spacing values. <b>rem</b> (recommended) scales with browser settings, <b>em</b> scales relative to parent, <b>px</b> uses fixed pixels.', 'md' ),
							'options' => array(
								'rem' => __( 'rem (Recommended)', 'md' ),
								'em' => __( 'em', 'md' ),
								'px' => __( 'px', 'md' )
							)
						) ); ?>
					</div>

					<div class="md-sep-small">

						<?php $this->fields->field( '404_page', array(
							'type' => 'number',
							'label' => __( '404 Page ID', 'md' )
						) ); ?>

						<?php if ( $page404 ) : ?>
							<?php $this->fields->description( sprintf( __( 'Success! You can <a href="%s">edit your 404 page here</a>.', 'md' ), admin_url( 'post.php?post=' . esc_attr( $page404 ) . '&action=edit' ) ) ); ?>
						<?php else : ?>
							<?php echo $this->fields->description( sprintf( __( 'Create a custom 404 page by attaching a new <a href="%s">Page ID</a> here.', 'md' ), admin_url( 'edit.php?post_type=page' ) ) ); ?>
						<?php endif; ?>

					</div>

					<div class="md-sep-small">
						<?php $this->fields->field( 'webfonts', array(
							'type' => 'checkbox',
							'label' => __( 'Web Fonts', 'md' ),
							'description' => __( 'By default MD loads all Google and Typekit web fonts through a stylesheet and prefetch method. To <b>attempt</b> to improve font performance and fight off "Flash of invisible text," enable the WebFont loader script here.', 'md' ),
							'options' => array(
								'loader' => __( 'Enable WebFont Loader', 'md' )
							)
						) ); ?>
					</div>

					<div class="md-sep-small">
						<?php $this->fields->field( 'head', array(
							'type' => 'checkbox',
							'label' => __( 'Optimize WP', 'md' ),
							'options' => array(
								'widgets' => __( '<b>Disable</b> Widgets blocks editor', 'md' ),
								'blocks' => __( '<code>&lt;head&gt;</code> <b>Remove</b> <code>block-library.css</code> and block inline styles', 'md' ),
								'wpjson' => __( '<code>&lt;head&gt;</code> <b>Remove</b> <code>/wp-json/</code> REST API', 'md' ),
								'optimize' => __( '<code>&lt;head&gt;</code> <b>Restore</b> all default <code>wp_head</code> tags', 'md' ),
								'oembed' => __( '<code>&lt;/body&gt;</code> <b>Remove</b> <code>wp-embed.js</code> script', 'md' )
							)
						) ); ?>
					</div>

				</div>

			</div>

			<div class="md-widget md-toggle md-sep-small">

				<h3 class="md-widget-title"><?php echo __( 'Sidebars', 'md' ); ?></h3>

				<div class="md-widget-item">
					<?php $this->fields->field( 'sidebars', array(
						'type' => 'group',
						'description' => sprintf( __( 'Create new sidebar areas to use in the <a href="%s">Widgets</a> screen. You can assign custom sidebars to various pages from <strong>Edit</strong> and <strong>Settings</strong> screens.', 'md' ), admin_url( 'widgets.php' ) ),
						'callback' => array( $this, 'sidebars' )
					) ); ?>
				</div>

			</div>

			<?php if ( md_has( 'scripts' ) ) :
				$scripts = new md_scripts( $this->_id );
			?>
				<div class="md-widget md-toggle md-sep-small">
					<h3 class="md-widget-title"><?php echo __( 'Scripts Manager', 'md' ); ?></h3>
					<div class="md-widget-item">
						<?php $scripts->admin_template(); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php do_action( 'md_hook_admin_settings_groups' ); ?>

		</div>

		<div class="col col2">
			<?php $this->fields->save( __( 'Save Settings', 'md' ) ); ?>
		</div>

	</div>

</div>
