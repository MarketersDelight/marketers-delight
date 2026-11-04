<div id="<?php echo $prefix; ?>_featured_media_video" class="md-conditional-item md-conditional-video" style="display: <?php echo $media_type == 'video' ? 'block' : 'none'; ?>">
	<?php $fields->field( 'video' ); ?>
</div>

<?php if ( $can_edit_code ) : ?>
<div id="<?php echo $prefix; ?>_featured_media_custom_html" class="md-conditional-item md-conditional-custom_html" style="display: <?php echo $media_type == 'custom_html' ? 'block' : 'none'; ?>">
	<?php $fields->field( 'custom_html' ); ?>
</div>
<?php endif; ?>
