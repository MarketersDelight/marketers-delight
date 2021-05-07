<?php
/**
 * Create widget interface and frontend output.
 *
 * @since 1.0
 */

class md_accordion_widget extends WP_Widget
{

    /**
     * Create widget attributes and fire any needed actions.
     *
     * @since 1.0
     */

    public function __construct()
    {
        parent::__construct('md_accordion_widget', __('MD &rarr; Accordion Nav', 'md'), array(
            'description' => __('List category links in a highly organized accordion widget.', 'md'),
            'customize_selective_refresh' => true
        ));
    }

    /**
     * Frontend template with passed data.
     *
     * @since 1.0
     */

    public function widget($args, $val)
    {
        $c = 1;
        $title = $val['title'];
        $terms = $current = array();
        $page_id = get_the_ID();
        if (is_home() && get_option('page_for_posts'))
            $page_id = get_queried_object_id();
        $tax = !empty($val['taxonomy']) ? $val['taxonomy'] : 'category';
        $taxonomy = get_taxonomy($tax);
        $post_type = $taxonomy->object_type;
        $get_terms = get_the_terms($page_id, $tax);
        $terms_data = get_terms($tax);
        foreach ($terms_data as $term_count => $term)
            $terms[$term->term_id] = $term;
        if (!empty($get_terms[0])) {
            $current = $terms[$get_terms[0]->term_id];
            unset($terms[$get_terms[0]->term_id]);
        }
        array_unshift($terms, $current);
        include(md_template('widgets/accordion', true));
    }

    /**
     * Sanitize saved data.
     *
     * @since 1.0
     */

    public function update($new, $val)
    {
        $sanitize = new md_sanitize;
        $val['title'] = $sanitize->text($new['title']);
        $val['taxonomy'] = $sanitize->select($new['taxonomy'], md_taxonomy_meta());
        $val['posts_per_category'] = $sanitize->number($new['posts_per_category']);
        return $val;
    }

    /**
     * Build widget form settings.
     *
     * @since 1.0
     */

    public function form($val)
    {
        $val = wp_parse_args((array)$val, array(
            'title' => '',
            'taxonomy' => '',
            'posts_per_category' => ''
        ));
        $taxonomies = md_taxonomy_meta();
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'md'); ?>:</label><br/>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($val['title']); ?>"
                   class="widefat"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php echo __('Category type', 'md'); ?>
                :</label><br/>
            <select id="<?php echo $this->get_field_id('taxonomy'); ?>"
                    name="<?php echo $this->get_field_name('taxonomy'); ?>">
                <option value=""><?php echo __('Select category type...', 'md'); ?></option>
                <?php foreach ($taxonomies as $count => $tax) : ?>
                    <option value="<?php echo esc_attr($tax); ?>"<?php echo selected($val['taxonomy'], esc_attr($tax), false); ?>><?php echo $tax; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_category'); ?>"><?php echo __('Posts per category', 'md'); ?>
                :</label><br/>
            <input type="number" id="<?php echo $this->get_field_id('posts_per_category'); ?>"
                   name="<?php echo $this->get_field_name('posts_per_category'); ?>"
                   value="<?php echo esc_attr($val['posts_per_category']); ?>" class="widefat" placeholder="5"
                   style="width: 25%;"/>
        </p>
    <?php }

}