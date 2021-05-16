<?php
$social = md_block_field($attributes, 'socialMedia');
if (empty($social))
	$social = 'twitter';
$text = md_block_field($attributes, 'text');
$textencode = urlencode($text);
if (empty($text))
	$text = __('Enter share text here...', 'md');
$url = get_permalink();
$urlencode = urlencode($url);
$image = get_the_post_thumbnail_url();
$imageencode = urlencode($image);
$boxStyle = md_block_field($attributes, 'boxStyle');
if (empty($boxStyle))
	$boxStyle = 'outline';
$buttonStyle = md_block_field($attributes, 'buttonStyle');
$buttonText = md_block_field($attributes, 'buttonText');
$align = md_block_field($attributes, 'align');
$alignment = md_block_field($attributes, 'alignment');
$marginBottom = md_block_field($attributes, 'marginBottom');
$padding = md_block_field($attributes, 'padding');
$className = md_block_field($attributes, 'className');
$classes = $button_classes = array();
$classes[] = 'share-notice';
$classes[] = "share-notice-{$social}";
$classes[] = "share-notice-{$boxStyle}";
$classes[] = !empty($align) ? "align{$align}" : '';
$classes[] = !empty($marginBottom) ? $marginBottom : 'mb-mid';
$classes[] = !empty($padding) ? $padding : 'block-mid';
if (!empty($alignment))
	$classes[] = "text-{$alignment}";
if (!empty($className))
	$classes[] = $className;
$classes = join(' ', $classes);
$button_classes[] = 'button';
if ($buttonStyle == 'full')
	$button_classes[] = 'button-full';
else
	$button_classes[] = 'button-outline';
if ($boxStyle == 'full')
	$button_classes[] = 'white';
$button_classes = join(' ', $button_classes);
if ($social == 'twitter') {
	$username = get_the_author_meta('twitter');
	$share_url = "https://twitter.com/intent/tweet?text={$textencode}" . (!empty($username) ? "&via={$username}" : '') . "&url={$urlencode}";
} elseif ($social == 'facebook')
	$share_url = "https://www.facebook.com/sharer.php?u={$urlencode}&t={$textencode}";
elseif ($social == 'pinterest')
	$share_url = "https://pinterest.com/pin/create/button/?url={$urlencode}&description={$textencode}&media={$imageencode}&is_video=false";
elseif ($social == 'linkedin')
	$share_url = "https://www.linkedin.com/sharing/share-offsite/?url={$urlencode}";
if (empty($buttonText)) {
	if (empty($social) || $social == 'twitter')
		$button_text = __('Tweet This', 'md');
	elseif ($social == 'facebook')
		$button_text = __('Post to Facebook', 'md');
	elseif ($social == 'pinterest')
		$button_text = __('Pin This', 'md');
	elseif ($social == 'linkedin')
		$button_text = __('Share on LinkedIn', 'md');
} else
	$button_text = $buttonText;
?>
<div class="<?php echo esc_attr($classes); ?>">
	<p class="share-notice-text small-text"><?php echo md_text_field($text); ?></p>
	<i class="share-notice-icon md-icon-<?php echo esc_attr($social); ?>"></i>
	<p class="share-notice-button">
		<a href="<?php echo esc_url($share_url); ?>" class="<?php echo esc_attr($button_classes); ?>" target="_blank"
		   data-share="true"><?php echo esc_html($button_text); ?></a>
	</p>
</div>
