<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'md' ); ?>:</label><br />
    <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php echo __( 'Category type', 'md' ); ?>:</label><br />
    <select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
        <option value=""><?php echo __( 'Auto-detect categories', 'md' ); ?></option>
        <?php foreach ( $taxonomies as $count => $tax ) :
            $taxonomy = get_taxonomy( $tax );
        ?>
         <option value="<?php echo esc_attr( $tax ); ?>"<?php echo selected( $val['taxonomy'], esc_attr( $tax ), false ); ?>><?php echo esc_html( $taxonomy->labels->singular_name ); ?></option>
        <?php endforeach; ?>
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php echo __( 'List direction', 'md' ); ?>:</label><br />
    <select id="<?php echo $this->get_field_id( 'direction' ); ?>" name="<?php echo $this->get_field_name( 'direction' ); ?>">
        <option value=""><?php echo __( 'Ascending (default)', 'md' ); ?></option>
        <option value="DESC"<?php echo selected( $val['direction'], 'DESC', false ); ?>><?php echo __( 'Descending', 'md' ); ?></option>
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php echo __( 'Order by', 'md' ); ?>:</label><br />
    <select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
        <option value=""><?php echo __( 'Name (default)', 'md' ); ?></option>
        <?php foreach ( $this->terms_order as $term_slug => $term_name ) : ?>
            <option value="<?php echo esc_attr( $term_slug ); ?>"<?php echo selected( $val['order'], esc_attr( $term_slug ), false ); ?>><?php echo esc_html( $term_name ); ?></option>
        <?php endforeach; ?>
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'posts_per_category' ); ?>"><?php echo __( 'Posts per category', 'md' ); ?>:</label><br />
    <input type="number" id="<?php echo $this->get_field_id( 'posts_per_category' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_category' ); ?>" value="<?php echo esc_attr( $val['posts_per_category'] ); ?>" class="widefat" placeholder="5" style="width: 25%;" />
</p>

<div>
    <label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php echo __( 'Exclude', 'md' ); ?>:</label>
    <p><input type="text" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo esc_attr( $val['exclude'] ); ?>" placeholder="23, 45, 345" class="widefat" /></p>
    <span><?php echo __( 'Exclude category IDs, comma-separated.', 'md' ); ?></span>
</div>

<hr />

<p style="margin-bottom: 5px;"><strong><?php echo __( 'Post filter', 'md' ); ?></strong></p>

<p style="margin-top: 0; font-style: italic;"><?php echo __( 'Advanced: you can filter posts to only show posts that have matching categories across post types.', 'md' ); ?></p>

<div class="md-auto-filter-manual" style="<?php echo ! empty( $val['auto_filter'] ) ? 'display: none;' : ''; ?>">
    <label for="<?php echo $this->get_field_id( 'filter_term' ); ?>"><?php echo __( 'Enter category ID', 'md' ); ?>:</label>
    <p><input type="number" id="<?php echo $this->get_field_id( 'filter_term' ); ?>" name="<?php echo $this->get_field_name( 'filter_term' ); ?>" value="<?php echo esc_attr( $val['filter_term'] ); ?>" style="width: 25%;" /></p>
</div>

<p class="md-auto-filter-taxonomy" style="<?php echo empty( $val['auto_filter'] ) ? 'display: none' : ''; ?>;">
    <select id="<?php echo $this->get_field_id( 'filter_taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'filter_taxonomy' ); ?>">
        <option value=""><?php echo __( 'Select taxonomy', 'md' ); ?></option>
        <?php foreach ( $taxonomies as $tax_slug => $tax_slug ) :
            $filter_taxonomy = get_taxonomy( $tax_slug );
        ?>
        <option value="<?php echo esc_attr( $tax_slug ); ?>"<?php echo selected( $val['filter_taxonomy'], $tax_slug, false ); ?>><?php echo esc_html( $filter_taxonomy->labels->singular_name ); ?></option>
        <?php endforeach; ?>
    </select>
</p>

<p>
    <input type="checkbox" id="<?php echo $this->get_field_id( 'auto_filter' ); ?>" name="<?php echo $this->get_field_name( 'auto_filter' ); ?>" value="1"<?php checked( $val['auto_filter'], '1' ); ?> class="md-auto-filter-toggle" />
    <label for="<?php echo $this->get_field_id( 'auto_filter' ); ?>"><?php echo __( 'Auto-detect from taxonomy', 'md' ); ?></label>
</p>

<hr />

<p><strong><?php echo __( 'Settings', 'md' ); ?></strong></p>

<p>
    <input type="checkbox" id="<?php echo $this->get_field_id( 'settings_open' ); ?>" name="<?php echo $this->get_field_name( 'settings' ); ?>[open]" value="1"<?php checked( ! empty( $val['settings']['open'] ), true ); ?> />
    <label for="<?php echo $this->get_field_id( 'settings_open' ); ?>"><?php echo __( 'Open first item by default', 'md' ); ?></label>
</p>

<p>
    <input type="checkbox" id="<?php echo $this->get_field_id( 'settings_parent' ); ?>" name="<?php echo $this->get_field_name( 'settings' ); ?>[parent]" value="1"<?php checked( ! empty( $val['settings']['parent'] ), true ); ?> />
    <label for="<?php echo $this->get_field_id( 'settings_parent' ); ?>"><?php echo __( 'Show parent categories only', 'md' ); ?></label>
</p>

<p>
    <input type="checkbox" id="<?php echo $this->get_field_id( 'settings_show_count' ); ?>" name="<?php echo $this->get_field_name( 'settings' ); ?>[show_count]" value="1"<?php checked( ! empty( $val['settings']['show_count'] ), true ); ?> />
    <label for="<?php echo $this->get_field_id( 'settings_show_count' ); ?>"><?php echo __( 'Show post count per category', 'md' ); ?></label>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'see_more' ); ?>"><?php echo __( 'See more text', 'md' ); ?>:</label><br />
    <input type="text" id="<?php echo $this->get_field_id( 'see_more' ); ?>" name="<?php echo $this->get_field_name( 'see_more' ); ?>" value="<?php echo esc_attr( $val['see_more'] ); ?>" class="widefat" />
    <span><?php echo __( '<b>Default:</b> <i>All {count} {category} &rarr;</i>', 'md' ); ?></span>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'classes' ); ?>"><?php echo __( 'CSS classes', 'md' ); ?>:</label><br />
    <input type="text" id="<?php echo $this->get_field_id( 'classes' ); ?>" name="<?php echo $this->get_field_name( 'classes' ); ?>" value="<?php echo esc_attr( $val['classes'] ); ?>" class="widefat" placeholder="my-class another-class" />
</p>

<script>
( function( $ ) {
    function syncAutoFilter( checkbox ) {
        var scope = checkbox.closest( '.widget-content, form' ),
            checked = checkbox.is( ':checked' );
        scope.find( '.md-auto-filter-taxonomy' ).toggle( checked );
        scope.find( '.md-auto-filter-manual' ).toggle( ! checked );
    }

    $( document )
        .on( 'change', '.md-auto-filter-toggle', function() {
            syncAutoFilter( $( this ) );
        } )
        .on( 'widget-added widget-updated', function( e, widget ) {
            widget.find( '.md-auto-filter-toggle' ).each( function() {
                syncAutoFilter( $( this ) );
            } );
        } );
} )( jQuery );
</script>
