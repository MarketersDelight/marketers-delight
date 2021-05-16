<span id="footnote_<?php echo $id; ?>" class="footnote">
	<sup class="footnote-number"><?php echo $i; ?></sup>
	<cite class="footnote-text<?php echo $align; ?>">
		<span class="footnote-text-number"><?php echo !empty($footnotes['after_post']['show']) ? "<a href=\"{$url}#footnotes\">$i</a>" : $i; ?>.</span>
		<?php echo $footnotes['footnotes'][$id]['footnote']; ?>
		<span class="footnote-triggers">
			<?php if (!empty($footnotes['after_post']['show'])) : ?>
				<a href="<?php echo $url; ?>#footnotes"
				   class="footnote-trigger-list <?php echo md_icon('menu', true); ?>"></a>
			<?php endif; ?>
			<span class="footnote-trigger-close">&times;</span>
		</span>
	</cite>
</span>
<?php if ($i == 1) wp_add_inline_script('marketers-delight', "\tMD.footnotes();");
$i++; ?>
