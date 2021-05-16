<?php
/**
 * Load frontend Bookshelf templates and actions.
 *
 * @since 5.0
 */

class md_bookshelf_templates extends md_api
{

	public $taxonomy_label = 'bookshelf_categories';
	public $dir = 'dropins';

	/**
	 * Fire important class actions.
	 *
	 * @since 5.0
	 */

	public function actions()
	{
		add_filter('md_stream_types', array($this, 'stream_type'));
		add_filter('md_stream_template_bookshelf', array($this, 'stream_template'));
		add_shortcode('book', array($this, 'shortcode'));
		if (!is_admin())
			add_action('parse_query', array($this, 'parse_query'));
	}

	/**
	 * Manipulate Bookshelf templates.
	 *
	 * @since 4.8.5
	 */

	public function template()
	{
		if (is_post_type_archive('bookshelf') || is_tax($this->taxonomy_label)) {
			add_filter('md_filter_content_box_classes', array($this, 'content_box_classes'));
			remove_action('md_hook_content', 'md_archives_title');
			add_filter('md_filter_loop_type', array($this, 'loop_type'));
			remove_action('md_hook_content', 'md_loop');
			remove_action('md_hook_content', 'md_comments'); // wtf?
			add_action('md_hook_content', array($this, 'page_title'));
			add_action('md_hook_content', array($this, 'loop'));
			if (md_setting(array('bookshelf', 'archives_layout', 'sidebar')))
				add_filter('md_filter_has_sidebar', '__return_true');
			else
				add_filter('md_filter_has_sidebar', '__return_false');
		}
	}

	/**
	 * Add custom class to content box.
	 *
	 * @since 5.0
	 */

	public function content_box_classes($classes)
	{
		$classes[] = 'bookshelf content-teasers';
		return $classes;
	}

	/**
	 * A simple way to override the post count loop for archive.
	 *
	 * @since 4.8.5
	 */

	public function parse_query($wp)
	{
		if (isset($wp->query['post_type']) && $wp->query['post_type'] == 'bookshelf' && $wp->is_main_query() && $wp->is_post_type_archive) {
			$custom = md_setting(array('bookshelf', 'posts_per_page'));
			$wp->query_vars['posts_per_page'] = $custom ? preg_replace('/\D/', '', $custom) : 20;
			$wp->query_vars['orderby'] = 'rand';
		}
		return $wp;
	}

	/**
	 * Bookshelf Page Title.
	 *
	 * @since 4.8.5
	 */

	public function page_title()
	{
		$c = 1;
		$title = md_setting(array('bookshelf', 'archives_title'));
		$text = md_setting(array('bookshelf', 'archives_text'));
		$tax = $this->taxonomy_label;
		$terms = get_terms(array(
			'taxonomy' => $tax,
			'hide_empty' => false
		));
		$term_id = get_queried_object_id();
		$has_categories = md_setting(array('bookshelf', 'archives_layout', 'categories')) ? true : false;
		$has_sidebar = md_setting(array('bookshelf', 'archives_layout', 'sidebar')) ? true : false;
		if ($title || $text)
			include(md_template($this->dir, 'bookshelf/bookshelf-title', true));
	}

	/**
	 * Collect book post data for templates.
	 *
	 * @since 4.8.5.3
	 */

	public function the_post($args = null)
	{
		$id = get_the_ID();
		$string = __('Read book review', 'md');
		$download_text = md_post_meta(array('bookshelf', 'book_download_text'));
		$title = md_post_meta(array('bookshelf', 'book_title'));
		$post = array(
			'post_id' => $id,
			'title' => $title ? $title : get_the_title(),
			'string' => $string,
			'link' => get_permalink(),
			'excerpt' => get_post_field('post_excerpt', $id),
			'content' => get_the_content($string),
			'author' => md_post_meta(array('bookshelf', 'book_author')),
			'download_url' => md_post_meta(array('bookshelf', 'book_download_url')),
			'rating' => md_post_meta(array('bookshelf', 'book_rating')),
			'download_text' => $download_text,
			'default_text' => !empty($download_text) ? $download_text : __('Get this book', 'md'),
		);
		return $post;
	}

	/**
	 * Change Loop type for Stream pages.
	 *
	 * @since 5.0.9
	 */

	public function loop_type()
	{
		return 'bookshelf';
	}

	/**
	 * Shortcode to pull individual book info.
	 *
	 * @since 4.8.5
	 */

	public function shortcode($atts, $content = '')
	{
		extract(shortcode_atts(array(
			'id' => '',
			'type' => '',
			'count' => '',
			'row' => ''
		), $atts, 'book'));
		ob_start();
		$type = !empty($atts['type']) ? $atts['type'] : 'grid';
		$row = !empty($atts['row']) ? $atts['row'] : null;
		$count = !empty($atts['count']) ? $atts['count'] : 6;
		$book_ids = (!empty($atts['id']) ? explode(',', str_replace(' ', '', $atts['id'])) : '');
		$this->loop(null, array(
			'post_id' => $book_ids,
			'type' => $type,
			'posts_per_page' => $count,
			'row' => $row
		));
		return ob_get_clean();
	}

	/**
	 * Bookshelf gallery loop template.
	 *
	 * @since 4.8.5
	 */

	public function loop($location, $args = null)
	{
		$c = $cc = 1;
		$default_type = md_setting(array('bookshelf', 'archives_listing')) ? md_setting(array('bookshelf', 'archives_listing')) : 'grid';
		$listing = !empty($args['type']) ? $args['type'] : $default_type;
		$columns_count = $listing == 'excerpt' ? 1 : (!empty($args['row']) ? $args['row'] : 5);
		include(md_template($this->dir, 'bookshelf/bookshelf-loop', true));
	}

	/**
	 * Add bookshelf to stream types
	 *
	 * @since 4.8.5
	 */

	public function stream_type($types)
	{
		$types['bookshelf'] = array(
			'label' => 'book',
			'template' => true
		);
		return $types;
	}

	/**
	 * Template to use for all Changelog stream entries.
	 *
	 * @since 4.8.5.1
	 */

	public function stream_template($post)
	{
		$this->loop(null, array(
			'post_id' => (array)$post['post_id'],
			'type' => 'excerpt'
		));
	}

	/**
	 * Overwrite book popup templates with our own
	 * custom markup for book popup preview.
	 *
	 * @since 4.8.5
	 */

	public function popup($post)
	{
		if (!empty($post['content']))
			include(md_template($this->dir, 'bookshelf/bookshelf-popup', true));
	}

}

new md_bookshelf_templates;
