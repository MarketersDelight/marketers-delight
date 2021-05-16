<div class="columns-2 columns-single">
	<div class="col">
		<p><?php $this->fields->label('shortcode', array('label' => __('Shortcode', 'md'))); ?></p>
		<p><code>[book id="<?php echo get_the_ID(); ?>"]</code></p>
		<?php $this->fields->description(__('Use this shortcode to embed this book anywhere on your site.', 'md')); ?>
	</div>
	<div class="col">
		<?php $this->fields->field('book_rating', array(
				'type' => 'number',
				'label' => __('Star Rating', 'md'),
				'description' => __('Enter a star rating 1-5 (or higher), whole numbers only.', 'md')
		)); ?>
	</div>
</div>
<div class="columns-2 columns-single">
	<div class="col">
		<?php $this->fields->field('book_title', array(
				'type' => 'text',
				'label' => __('Book Title', 'md'),
				'description' => __('Enter the title of book here.', 'md')
		)); ?>
	</div>
	<div class="col">
		<?php $this->fields->field('book_author', array(
				'type' => 'text',
				'label' => __('Book Author', 'md'),
				'description' => __('The author(s) who wrote this book.', 'md')
		)); ?>
	</div>
</div>
<div class="columns-2 columns-single">
	<div class="col">
		<?php $this->fields->field('book_download_text', array(
				'type' => 'text',
				'label' => __('Download Text', 'md'),
				'description' => __('Set your own custom download text for visitors to click on.', 'md'),
				'placeholder' => __('Get this book', 'md')
		)); ?>
	</div>
	<div class="col">
		<?php $this->fields->field('book_download_url', array(
				'type' => 'url',
				'label' => __('Download URL', 'md'),
				'description' => __('Link to the Amazon page/website to download or buy this book.', 'md')
		)); ?>
	</div>
</div>
