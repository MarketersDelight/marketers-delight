<div class="md-dashboard md-content-wrap-med">

	<?php do_action( 'md_hook_settings_col2_top' ); ?>

	<div id="md_update">
		<?php $this->updater(); ?>
	</div>

	<div class="md-dashboard-columns">

		<div class="md-dashboard-main">

			<div class="md-widget md-toggle md-dashboard-card md-site-tools">

				<h3 class="md-widget-title"><?php echo __( 'Site Tools', 'md' ); ?></h3>

				<div class="md-tools-row">
					<?php $this->fields->field( 'css', array(
						'description' => sprintf( __( 'If using a child theme you can save an extra HTTP request by combining your custom stylesheet with MD\'s stylesheet file. For further optimization you can print your stylesheets inline to your site\'s %s.', 'md' ), '<code>&lt;head&gt;</code>' )
					) ); ?>
				</div>

				<div class="md-tools-row">

					<?php $this->fields->field( '404_page' ); ?>

					<?php if ( $page404 )
							$this->fields->description( sprintf( __( '<a href="%s">Edit your <strong>404 page</strong> &rarr;</a>', 'md' ), admin_url( 'post.php?post=' . esc_attr( $page404 ) . '&action=edit' ) ) );
						else
							$this->fields->description( sprintf( __( 'Create a custom 404 page by attaching a new <a href="%s">Page ID</a> here.', 'md' ), admin_url( 'edit.php?post_type=page' ) ) );
					?>

				</div>

				<div class="md-tools-row">
					<?php $this->fields->field( 'head' ); ?>
				</div>

				<div class="md-tools-row">
					<?php $this->fields->field( 'abilities', array(
						'description' => __( 'Apps and AI assistants you sign in with an application password can use actions from MD and its Drop-ins, such as creating a Stream post. Nothing is shared with anyone who isn&rsquo;t signed in.', 'md' )
					) ); ?>
				</div>

			</div>

			<?php do_action( 'md_hook_admin_after_tools' ); ?>

			<?php do_action( 'md_hook_admin_settings_groups' ); ?>

		</div>

		<div class="md-dashboard-side">

			<div class="md-widget md-toggle md-dashboard-card">

				<h3 class="md-widget-title"><?php echo __( 'Sidebars', 'md' ); ?></h3>

				<p class="md-dashboard-card-description"><?php printf( __( 'Create new sidebar areas to use in the <a href="%s">Widgets</a> screen. You can assign custom sidebars to various pages from <strong>Edit</strong> and <strong>Settings</strong> screens.', 'md' ), admin_url( 'widgets.php' ) ); ?></p>

				<?php $this->fields->field( 'sidebars', array(
					'style' => 'simple',
					'add_label' => __( 'New sidebar name', 'md' ),
					'callback' => function( $group, $field ) {
						$this->fields->field( array( $group, $field, 'name' ), array(
							'placeholder' => __( 'Enter sidebar name...', 'md' ),
							'classes' => 'md-focus'
						) );
					}
				) ); ?>

			</div>

			<div class="md-widget md-toggle md-dashboard-card">

				<h3 class="md-widget-title"><?php echo __( 'Panels', 'md' ); ?></h3>

				<p class="md-dashboard-card-description"><?php printf( __( 'Create new Panel areas to use in the <a href="%s">Widgets</a> screen. You can assign custom panels to various pages from <strong>Edit</strong> and <strong>Settings</strong> screens.', 'md' ), admin_url( 'widgets.php' ) ); ?></p>

				<?php $this->fields->field( 'panels', array(
					'style' => 'simple',
					'add_label' => __( 'New panel name', 'md' ),
					'callback' => function( $group, $field ) {
						$this->fields->field( array( $group, $field, 'name' ), array(
							'placeholder' => __( 'Enter panel name...', 'md' ),
							'classes' => 'md-focus'
						) );
					}
				) ); ?>

			</div>

		</div>

	</div>

</div>
