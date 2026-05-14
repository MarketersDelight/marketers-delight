<?php if ( md_filter_template() !== false ) : md_hook_before_footer(); ?>

	<?php if ( md_has_footer() ) : ?>

	<footer class="<?php echo md_footer_classes(); ?>">

		<?php md_hook_footer_top(); ?>

		<div class="inner">

			<?php md_hook_footer(); ?>

		</div>

		<?php md_hook_footer_bottom(); ?>

	</footer>

	<?php endif; ?>

<?php md_hook_after_footer(); endif; ?>

<?php wp_footer(); ?>

</body>
</html>