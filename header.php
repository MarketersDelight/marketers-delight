<?php md_template( 'html' ); ?>

<?php md_hook_before_html(); ?>

<?php if ( md_has_header() ) : ?>

	<header id="header" class="<?php echo md_header_classes(); ?>">

		<?php md_hook_header_top(); ?>

		<div class="inner">

			<?php md_hook_before_header(); ?>

			<div class="header-wrap">

				<?php md_hook_header(); ?>

				<?php if ( md_has_menu() ) : ?>
					<div class="header-aside">
						<?php md_hook_header_aside(); ?>
					</div>
				<?php endif; ?>

				<div class="header-triggers">
					<?php md_hook_header_triggers(); ?>
				</div>

			</div>

			<?php md_hook_after_header(); ?>

		</div>

		<?php md_hook_header_bottom(); ?>

	</header>

<?php endif; ?>

<?php if ( md_filter_template() !== false ) : ?>
	<?php md_hook_before_content_box(); ?>
<?php endif; ?>