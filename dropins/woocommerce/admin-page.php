<div class="md-content-wrap-med">
	<div class="columns-2 columns-double">
		<div class="col">
			<div class="md-sep">
				<h2><?php echo __( 'Bonus Features', 'md' ); ?></h2>
				<p>
					<?php $this->fields->field( 'settings', array(
						'type' => 'checkbox',
						'options' => array(
							'header_cart' => __( '<b>Add</b> shopping cart icon to header', 'md' )
						)
					) ); ?>
				</p>
				<p>
					<?php $this->fields->field( 'sale_label', array(
						'type' => 'text',
						'placeholder' => __( 'Enter text used for "Sale!" text', 'md' )
					) ); ?>
				</p>
			</div>
			<div class="md-sep">
				<h2><?php echo __( 'Checkout Page', 'md' ); ?></h2>
				<p>
					<?php $this->fields->field( 'settings', array(
						'type' => 'checkbox',
						'options' => array(
							'minimal_checkout' => __( '<b>Enable</b> minimal checkout fields', 'md' )
						)
					) ); ?>
				</p>
				<p>
					<?php $this->fields->field( 'settings', array(
						'type' => 'checkbox',
						'description' => __( '<b>Note:</b> Minimal checkout removes the following checkout fields: <b>Billing Address</b>, <b>Country</b>, <b>Company</b>, <b>City</b>, <b>State</b>, and <b>Postal Code</b>.', 'md' ),
						'options' => array(
							'remove_order_comments' => __( '<b>Remove</b> order comments field', 'md' ),
							'phone_optional' => __( 'Make phone number field optional', 'md' )
						)
					) ); ?>
				</p>
			</div>
		</div>
		<div class="col">
			<div class="md-sep">
				<h2><?php echo __( 'Sidebar', 'md' ); ?></h2>
				<p>
					<?php $this->fields->field( 'settings', array(
						'type' => 'checkbox',
						'description' => sprintf( __( 'Add <a href="%s">custom sidebars</a> to the shop pages and enable/disable sidebars from the product post editor.', 'md' ), admin_url( 'widgets.php' ) ),
						'options' => array(
							'enable_archives_sidebar' => __( '<b>Add</b> sidebar to shop archives', 'md' ),
							'enable_single_sidebar' => __( '<b>Add</b> sidebar to product pages', 'md' )
						)
					) ); ?>
				</p>
			</div>
			<div class="md-sep">
				<h2><?php echo __( 'Settings', 'md' ); ?></h2>
				<?php $this->fields->field( 'settings', array(
					'type' => 'checkbox',
					'description' => __( 'Marketers Delight enhances WooCommerce by adding its own custom styles to your site. Enable this option to remove those styles.', 'md' ),
					'options' => array(
						'remove_css' => __( '<b>Remove</b> MD custom styles', 'md' )
					)
				) ); ?>
			</div>
		</div>
	</div>
	<p>
		<?php $this->fields->save(); ?>&nbsp;&nbsp;
		<a href="<?php echo admin_url( 'customize.php?autofocus[panel]=woocommerce' ); ?>" class="button button-secondary md-button" target="_blank"><?php echo __( 'WooCommerce Settings', 'md' ); ?></a>
	</p>
</div>