<?php
global $wp_query;
$big = 999999999;
$paginate = paginate_links(array(
		'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
		'format' => '?paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'prev_text' => '<i class="' . md_icon('angle-left', true) . '"></i> ' . __('Previous', 'md'),
		'next_text' => __('Next', 'md') . ' <i class="' . md_icon('angle-right', true) . '"></i>',
		'total' => $wp_query->max_num_pages
));
?>
<?php if ($paginate) : ?>
	<div class="pagination">
		<?php echo $paginate; ?>
	</div>
<?php endif; ?>
