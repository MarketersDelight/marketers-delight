<div class="md-tabs md-conditional">
	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active"
		   data-md-tab="md-optins-cta"><?php echo __('Call to Action', 'md'); ?></a>
		<a href="#" class="md-tab nav-tab"
		   data-md-tab="md-optins-floating-bars"><?php echo __('Floating Bars', 'md'); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-optins-popups"><?php echo __('Popups', 'md'); ?></a>
	</div>
	<div class="md-optins-cta md-tab-content active">
		<?php if (!empty($active_cta)) : ?>
			<div class="md-sep-small">
				<?php $this->fields->field('cta_remove', array(
						'type' => 'checkbox',
						'multi' => true,
						'label' => __('Remove from page', 'md'),
						'description' => sprintf(__('The above CTA\'s have been <a href="%s">added to this page</a> and can be removed here, or you can create new ones here.', 'md'), admin_url('admin.php?page=md_optins&tab=md_eail')),
						'options' => $active_cta
				)); ?>
			</div>
		<?php endif; ?>
		<?php $this->fields->field('cta', array(
				'type' => 'group',
				'callback' => array($this, 'cta_meta'),
				'label' => __('Add Call to Action', 'md'),
				'style' => 'boxes'
		)); ?>
	</div>
	<div class="md-optins-floating-bars md-tab-content">
		<?php if (!empty($active_floating_bars)) : ?>
			<div class="md-sep-small">
				<?php $this->fields->field('floating_bars_remove', array(
						'type' => 'checkbox',
						'multi' => true,
						'label' => __('Remove from page', 'md'),
						'description' => sprintf(__('The above Floating Bars have been added to this page from the <a href="%s">Floating Bars Manager</a> and can be removed here, or you can create new ones here.', 'md'), admin_url('admin.php?page=md_optins&tab=md_floating_bars')),
						'options' => $active_floating_bars
				)); ?>
			</div>
		<?php endif; ?>
		<?php $this->fields->field('floating_bars', array(
				'type' => 'group',
				'callback' => array($this, 'floating_bars_meta'),
				'label' => __('Add Floating Bars', 'md'),
				'style' => 'boxes'
		)); ?>
	</div>
	<div class="md-optins-popups md-tab-content">
		<?php if (!empty($active_popups)) : ?>
			<div class="md-sep-small">
				<?php $this->fields->field('popups_remove', array(
						'type' => 'checkbox',
						'multi' => true,
						'label' => __('Remove from page', 'md'),
						'description' => sprintf(__('The above Popups have been added to this page from the <a href="%s">Popups Manager</a> and can be removed here, or modified below.', 'md'), admin_url('admin.php?page=md_optins&tab=md_popups')),
						'options' => $active_popups
				)); ?>
			</div>
		<?php endif; ?>
		<?php $this->fields->field('popups', array(
				'type' => 'group',
				'callback' => array($this, 'popups_meta'),
				'label' => __('Add Popups', 'md'),
				'style' => 'list-box'
		)); ?>
	</div>
</div>
