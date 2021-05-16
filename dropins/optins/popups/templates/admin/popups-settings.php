<div class="md-popups md-content-wrap-wide">
	<div class="columns-60-40 columns-single md-sep-small">
		<div class="col col1">
			<?php $this->fields->field('popups', array(
					'type' => 'group',
					'callback' => array($this, 'fields'),
					'style' => 'boxes',
					'label' => __('Popups Manager', 'md'),
					'active_key' => 'locations'
			)); ?>
		</div>
		<?php if (!empty($options)) : ?>
			<div class="col col2">
				<div class="md-group-head">
					<h2 class="md-title"><?php echo __('Popups Extras', 'md'); ?></h2>
				</div>
				<div class="md-widget md-toggle md-sep-small">
					<h3 class="md-widget-title"><?php echo __('Popup Hotspots', 'md'); ?></h3>
					<div class="md-widget-item">
						<?php if (md_setting('popups')) : ?>
							<div class="md-sep-micro">
								<?php $this->fields->field('main_menu', array(
										'type' => 'select',
										'label' => __('Main Menu', 'md'),
										'empty_label' => __('Select a popup&hellip;', 'md'),
										'description' => __('Add an email icon to the Main Menu that opens a popup when clicked.', 'md'),
										'options' => $options
								)); ?>
							</div>
							<hr class="md-sep-small"/>
							<div class="md-sep-micro">
								<?php $this->fields->field('byline', array(
										'type' => 'select',
										'label' => __('Byline', 'md'),
										'empty_label' => __('Select a popup&hellip;', 'md'),
										'description' => __('Add a link to blog post bylines that open a popup when clicked.', 'md'),
										'options' => $options
								)); ?>
							</div>
							<div id="popups_byline_row" class="md-sep-micro"
								 style="display: <?php echo !empty($byline_text) ? 'block' : 'none'; ?>">
								<?php $this->fields->field('byline_text', array(
										'type' => 'text',
										'label' => __('Byline Text', 'md'),
										'placeholder' => __('Get updates', 'md')
								)); ?>
							</div>
							<hr class="md-sep-small"/>
							<div class="md-sep-micro">
								<?php $this->fields->field('header_menu', array(
										'type' => 'select',
										'label' => __('Header Menu', 'md'),
										'empty_label' => __('Select a popup&hellip;', 'md'),
										'description' => __('Add a popup button or link to the end of the header nav menu.', 'md'),
										'options' => $options
								)); ?>
							</div>
							<div id="popups_header_menu_row"
								 style="display: <?php echo !empty($header_menu) ? 'block' : 'none'; ?>">
								<div class="md-sep-micro">
									<?php $this->fields->field('header_menu_text', array(
											'type' => 'text',
											'label' => __('Header Menu Text', 'md'),
											'placeholder' => __('Get updates', 'md')
									)); ?>
								</div>
								<div class="md-sep-micro">
									<?php $this->fields->field('header_menu_button', array(
											'type' => 'checkbox',
											'label' => __('Header Menu Button', 'md'),
											'options' => array(
													'enable' => __('Show as button', 'md')
											)
									)); ?>
								</div>
							</div>
							<script>
								(function () {
									document.getElementById('<?php echo $this->_prefix; ?>_byline').onchange = function () {
										document.getElementById('popups_byline_row').style.display = this.value != '' ? 'block' : 'none';
									};
									document.getElementById('<?php echo $this->_prefix; ?>_header_menu').onchange = function () {
										document.getElementById('popups_header_menu_row').style.display = this.value != '' ? 'block' : 'none';
									}
								})();
							</script>
						<?php else : ?>
							<?php md_popup_connect_notice(); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<hr class="md-sep-small"/>
	<?php $this->fields->save(); ?>
</div>
