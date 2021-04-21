<?php
/**
 * Return a list of MD icons. Read documentation and
 * see how to filter in your own icons:
 * https://marketersdelight.com/font-icons/
 *
 * @since 4.9.3
 */

function md_icons() {
	return array_merge( array(
		'md-icon-pencil' => array(
			'glyph' => '\e80d',
			'label' => __( 'Pencil', 'md' )
		),
		'md-icon-quote' => array(
			'glyph' => '\f10e',
			'label' => __( 'Quote', 'md' )
		),
		'md-icon-pin' => array(
			'glyph' => '\e803',
			'label' => __( 'Pin', 'md' )
		),
		'md-icon-bell' => array(
			'glyph' => '\f0f3',
			'label' => __( 'Bell', 'md' )
		),
		'md-icon-tags' => array(
			'glyph' => '\e829',
			'label' => __( 'Tags', 'md' )
		),
		'md-icon-url' => array(
			'glyph' => '\e827',
			'label' => __( 'Link', 'md' )
		),
		'md-icon-book' => array(
			'glyph' => '\e828',
			'label' => __( 'Book', 'md' )
		),
		'md-icon-key' => array(
			'glyph' => '\e82a',
			'label' => __( 'Key', 'md' )
		),
		'md-icon-cart' => array(
			'glyph' => '\e825',
			'label' => __( 'Cart', 'md' )
		),
		'md-icon-download' => array(
			'glyph' => '\e822',
			'label' => __( 'Download', 'md' )
		),
		'md-icon-volume-up' => array(
			'glyph' => '\e826',
			'label' => __( 'Volume Up', 'md' )
		),
		'md-icon-ok' => array(
			'glyph' => '\e804',
			'label' => __( 'Check', 'md' ),
			'classes' => array( 'md-icon-ok-circled', 'list-check li' )
		),
		'md-icon-cancel' => array(
			'glyph' => '\e810',
			'label' => __( 'Cancel (X)', 'md' )
		),
		'md-icon-exclamation' => array(
			'glyph' => '\f12a',
			'label' => __( 'Exclamation (!)', 'md' )
		),
		'md-icon-star' => array(
			'glyph' => '\e81b',
			'label' => __( 'Star', 'md' )
		),
		'md-icon-bolt' => array(
			'glyph' => '\e823',
			'label' => __( 'Lightning Bolt', 'md' )
		),
		'md-icon-trophy' => array(
			'glyph' => '\e824',
			'label' => __( 'Trophy', 'md' )
		),
		'md-icon-mail-alt' => array(
			'glyph' => '\e814',
			'label' => __( 'Email', 'md' )
		),
		'md-icon-phone' => array(
			'glyph' => '\e806',
			'label' => __( 'Phone', 'md' )
		),
		'md-icon-chat' => array(
			'glyph' => '\e811',
			'label' => __( 'Chat', 'md' )
		),
		'md-icon-eye' => array(
			'glyph' => '\e81e',
			'label' => __( 'Eye', 'md' )
		),
		'md-icon-like' => array(
			'glyph' => '\e820',
			'label' => __( 'Thumbs Up', 'md' )
		),
		'md-icon-heart' => array(
			'glyph' => '\e821',
			'label' => __( 'Heart', 'md' )
		),
		'md-icon-heart-empty' => array(
			'glyph' => '\e801',
			'label' => __( 'Heart Empty', 'md' )
		),
		'md-icon-clock' => array(
			'glyph' => '\e812',
			'label' => __( 'Clock', 'md' )
		),
		'md-icon-loading' => array(
			'glyph' => '\e832',
			'label' => __( 'Loading', 'md' )
		),
		'md-icon-search' => array(
			'glyph' => '\e813',
			'label' => __( 'Search', 'md' )
		),
		'md-icon-menu' => array(
			'glyph' => '\e815',
			'label' => __( 'Menu', 'md' )
		),
		'md-icon-code' => array(
			'glyph' => '\f121',
			'label' => __( 'Code', 'md' )
		),
		'md-icon-reply' => array(
			'glyph' => '\f112',
			'label' => __( 'Reply', 'md' )
		),
		'md-icon-share' => array(
			'glyph' => '\e81c',
			'label' => __( 'Share', 'md' )
		),
		'md-icon-hand' => array(
			'glyph' => '\f256',
			'label' => __( 'Hand', 'md' )
		),
		'md-icon-user' => array(
			'glyph' => '\e819',
			'label' => __( 'User', 'md' )
		),
		'md-icon-user-add' => array(
			'glyph' => '\e818',
			'label' => __( 'User Add', 'md' )
		),
		'md-icon-angle-up' => array(
			'glyph' => '\e817',
			'label' => __( 'Up Arrow', 'md' )
		),
		'md-icon-angle-down' => array(
			'glyph' => '\e80e',
			'label' => __( 'Down Arrow', 'md' )
		),
		'md-icon-angle-right' => array(
			'glyph' => '\e80f',
			'label' => __( 'Right Arrow', 'md' )
		),
		'md-icon-angle-left' => array(
			'glyph' => '\e816',
			'label' => __( 'Left Arrow', 'md' )
		),
		'md-icon-twitter' => array(
			'glyph' => '\e800',
			'label' => __( 'Twitter', 'md' )
		),
		'md-icon-facebook' => array(
			'glyph' => '\f09a',
			'label' => __( 'Facebook', 'md' ),
			'classes' => array( 'md-icon-facebook-squared' )
		),
		'md-icon-instagram' => array(
			'glyph' => '\e805',
			'label' => __( 'Instagram', 'md' ),
		),
		'md-icon-telegram' => array(
			'glyph' => '\e839',
			'label' => __( 'Telegram', 'md' ),
		),
		'md-icon-tiktok' => array(
			'glyph' => '\e83a',
			'label' => __( 'TikTok', 'md' ),
			'classes' => array( 'md-icon-tiktok' )
		),
		'md-icon-linkedin' => array(
			'glyph' => '\f0e1',
			'label' => __( 'LinkedIn', 'md' ),
			'classes' => array( 'md-icon-linkedin-squared' )
		),
		'md-icon-youtube-play' => array(
			'glyph' => '\e807',
			'label' => __( 'YouTube', 'md' )
		),
		'md-icon-pinterest' => array(
			'glyph' => '\f231',
			'label' => __( 'Pinterest', 'md' ),
			'classes' => array( 'md-icon-pinterest-squared' )
		),
		'md-icon-wordpress' => array(
			'glyph' => '\e80b',
			'label' => __( 'WordPress', 'md' )
		),
		'md-icon-github-squared' => array(
			'glyph' => '\e802',
			'label' => __( 'GitHub', 'md' )
		),
		'md-icon-vimeo-squared' => array(
			'glyph' => '\e809',
			'label' => __( 'Vimeo', 'md' )
		),
		'md-icon-gplus' => array(
			'glyph' => '\f0d5',
			'label' => __( 'Google+', 'md' )
		),
		'md-icon-tumblr-squared' => array(
			'glyph' => '\e808',
			'label' => __( 'Tumblr', 'md' )
		),
		'md-icon-flickr' => array(
			'glyph' => '\e80a',
			'label' => __( 'Flickr', 'md' )
		),
		'md-icon-dribbble' => array(
			'glyph' => '\e80c',
			'label' => __( 'Dribbble', 'md' )
		),
		'md-icon-medium' => array(
			'glyph' => '\f23a',
			'label' => __( 'Medium', 'md' )
		),
		'md-icon-periscope' => array(
			'glyph' => '\e81d',
			'label' => __( 'Periscope', 'md' )
		),
		'md-icon-speakerdeck' => array(
			'glyph' => '\e81f',
			'label' => __( 'SpeakerDeck', 'md' )
		),
		'md-icon-whatsapp' => array(
			'glyph' => '\f232',
			'label' => __( 'WhatsApp', 'md' )
		),
		'md-icon-slack' => array(
			'glyph' => '\e83c',
			'label' => __( 'Slack', 'md' )
		),
		'md-icon-skype' => array(
			'glyph' => '\f17e',
			'label' => __( 'Skype', 'md' )
		),
		'md-icon-rss' => array(
			'glyph' => '\f09e',
			'label' => __( 'RSS', 'md' )
		)
	), apply_filters( 'md_filter_icons', array() ) );
}