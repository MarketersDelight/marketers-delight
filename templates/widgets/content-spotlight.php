<?php echo $args['before_widget']; ?>

<?php if ($widget_title) : ?>
	<?php echo $args['before_title']; ?><?php echo $widget_title; ?><?php echo $args['after_title']; ?>
<?php endif; ?>

	<<?php echo $html; ?> class="<?php echo $image_classes; ?> content-spotlight block-mid shadow"<?php echo $src; ?>>
<?php if ($intro) : ?>
	<small class="display-block mb-half"><?php echo esc_html($intro); ?></small>
<?php endif; ?>
<?php if ($title) : ?>
	<p class="content-spotlight-title small-title"><?php echo $title; ?></p>
<?php endif; ?>
	</<?php echo $html_c; ?>>

<?php echo $args['after_widget']; ?>
