<div class="<?php echo md_byline_classes(); ?>">
    <?php foreach (md_byline_items() as $item => $label) : ?>
        <?php md_byline_item($item); ?>
    <?php endforeach; ?>
</div>