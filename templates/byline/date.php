<?php if ( ! in_array( 'date', $byline ) ) : ?>
	<span class="byline-date byline-item">
		<?php echo md_icon( 'clock' ); ?> <time datetime="<?php the_date( 'c' ); ?>" itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></time>
		<?php if ( in_array( 'last-updated', $byline ) ) : ?>
			(<?php echo __( 'updated ', 'md' ); ?> <?php the_modified_date(); ?>)
		<?php endif; ?>
	</span>
<?php endif; ?>

<?php if ( in_array( 'last-updated', $byline ) && in_array( 'date', $byline ) ) : ?>
	<span class="byline-date-modified byline-item" itemprop="dateModified" content="<?php the_modified_date( 'c' ); ?>">
		<?php echo md_icon( 'clock' ); ?> <?php echo __( 'Last updated:', 'md' ); ?> <?php the_modified_date(); ?>
	</span>
<?php endif; ?>

<?php do_action( 'md_hook_byline_after_date' ); ?>