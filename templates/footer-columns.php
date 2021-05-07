<?php if (md_has_footer_columns()) :
    $columns = md_footer_columns(); // count active footer columns
    $col_class = $columns > 1 ? ' col' : ''; // add 'col' class if more than one footer widget area
    ?>
    <div class="footer-columns<?php echo $columns > 1 ? " columns-double columns-$columns" : ''; ?> mb-single">
        <?php foreach (md_filter_footer_columns() as $col) : ?>
            <?php if (is_active_sidebar("md-footer-col-$col")) : ?>
                <div class="col<?php echo $col; ?><?php echo $col_class; ?>">
                    <?php dynamic_sidebar("md-footer-col-$col"); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>