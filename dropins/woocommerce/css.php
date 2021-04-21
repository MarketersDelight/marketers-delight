<style type="text/css">

/*------------------------------*\
	$WOOCOMMERCE
\*------------------------------*/

.woocommerce .loop-default.style-default .post-box,
.woocommerce .loop-default.style-default .archives-title {
	background-color: transparent;
	box-shadow: none;
}

.woocommerce .style-default .site-main {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
	padding: <?php echo $double; ?>px <?php echo $mid; ?>px;
}

.woocommerce .loop-default.style-default .post-box > div { border-bottom: 0; }

.woocommerce label { font-weight: bold; }

.woocommerce form .form-row textarea { height: auto; }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.woocommerce .style-minimal .site-main { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.woocommerce .style-default .site-main { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
}

/* ELEMENTS */

.woocommerce-terms-and-conditions-wrapper,
.woocommerce #comments,
.woocommerce-product-details__short-description,
.woocommerce div.product form.cart div.quantity,
.woocommerce .woocommerce-breadcrumb {
	margin-bottom: <?php echo $single; ?>px;
}

.woocommerce .star-rating, .woocommerce ul.products li.product .star-rating { color: #FFB900; }

.woocommerce-breadcrumb a { border-bottom-color: #ccc; }

.header-cart {
	color: <?php echo $colors['header']['menu']['links']; ?>;
	font-weight: bold;
	vertical-align: top;
}

.featured-image-cover .header-cart { color: <?php echo ( ! empty( $content['featured_image']['styles']['text_color'] ) ? $colors['site']['headline'] : '#FFFFFF' ); ?>; }

.woocommerce-products-header { margin-bottom: <?php echo $double; ?>px; }

.woocommerce .woocommerce-result-count, .woocommerce .woocommerce-ordering select { margin-bottom: <?php echo $single; ?>px; }

.content .cart-empty, .content .cart-empty + .return-to-shop { text-align: center; }

/* MESSAGE */

.woocommerce .woocommerce-message {
	border-top-color: #22A340;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	margin-bottom: <?php echo $single; ?>px;
}

.woocommerce-message:before { color: #22A340; }

/* BUTTONS */

.woocommerce a.button,
.woocommerce button.button,
.woocommerce button.button.alt,
.woocommerce a.button.alt,
.woocommerce input.button,
.woocommerce #respond input#submit,
.woocommerce a.button.alt:hover,
.woocommerce a.button:hover,
.woocommerce button.button:hover,
.woocommerce button.button.alt:hover,
.woocommerce button.button.alt.disabled,
.woocommerce button.button.alt.disabled:hover,
.woocommerce .actions button.button {
	background-color: <?php echo $colors['site']['button']; ?>;
	color: <?php echo $colors['site']['button-text']; ?>;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	font-size: inherit;
	font-weight: inherit;
	padding: 16px;
}

.woocommerce a.button.loading:after {
	content: '\e832' !important;
	top: 5px;
	right: -9px;
}

.woocommerce a.button.added:after {
	content: '\e804';
}

.woocommerce a.button.added:after,
.woocommerce a.button.loading:after,
.woocommerce .added_to_cart:after {
	display: inline-block;
	font-family: 'md-icon';
	font-weight: 400;
	margin-left: 7px;
}

.woocommerce a.added_to_cart {
	background-color: rgba(0, 0, 0, 0.8);
	border-bottom: 0;
	border-radius: 3px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
	color: #fff;
	font-size: 14px;
	padding: 3px <?php echo $half; ?>px;
	position: absolute;
		bottom: 0;
		right: -<?php echo $triple + $small; ?>px;
	text-align: center;
	width: <?php echo $quad; ?>px;
	z-index: 20;
}

.woocommerce a.added_to_cart:before {
	border-bottom: 8px solid transparent;
	border-top: 8px solid transparent;
	border-right: 8px solid rgba(0, 0, 0, 0.8);
	content: '';
	height: 0;
	position: absolute;
		left: -8px;
		top: 10px;
	width: 0;
}

.woocommerce .added_to_cart:after { content: '\e80f'; }

.woocommerce .shop_table button.button:disabled, .woocommerce .shop_table button.button:disabled:hover { color: <?php echo $colors['site']['button-sec-text']; ?>; }

.woocommerce .shop_table button.button:disabled,
.woocommerce .shop_table button.button:hover:disabled,
.woocommerce .actions button.button,
.woocommerce .actions button.button:hover {
	background-color: <?php echo $colors['site']['button-sec']; ?>;
	padding: <?php echo $half; ?>px;
}

.woocommerce ul.products li.product .button {
	background-color: transparent;
	box-shadow: none;
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-weight: bold;
	letter-spacing: normal;
	margin-top: 0;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px 0;
	text-transform: none;
	width: auto;
}

.woocommerce ul.products li.product .button:after { margin-left: <?php echo $small; ?>px; }

/* PRODUCT LOOP */

.woocommerce span.onsale {
	background-color: #cd201f;
	border-radius: 20px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
	font-size: inherit;
	line-height: 1;
	min-height: inherit;
	min-width: inherit;
	padding: 8px 13px;
}

.woocommerce ul.products li.product {
	margin-bottom: <?php echo $single; ?>px;
	text-align: center;
}

.woocommerce-loop-product__link {
	color: #1e1e1e;
	text-align: center;
}

.woocommerce ul.products li.product a img { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); }

.woocommerce div.product div.images img { box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); }

.woocommerce ul.products li.product .woocommerce-loop-product__title {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
	margin-bottom: <?php echo $small; ?>px;
	padding-bottom: 0;
	padding-top: 0;
}

.woocommerce div.product p.price,
.woocommerce div.product span.price,
.woocommerce ul.products li.product .price,
ins .woocommerce-Price-amount {
	color: #22A340;
	font-size: inherit;
	line-height: inherit;
}

.woocommerce-pagination .page-numbers { border-bottom: 0; }

.woocommerce ul.products li.product .star-rating {
	margin-left: auto;
	margin-right: auto;
}

/* SINGLE PRODUCT */

.woocommerce div.product .woocommerce-tabs ul.tabs {
	margin-bottom: 0;
	margin-left: 16px;
}

.woocommerce #content div.product .woocommerce-tabs ul.tabs:before { display: none; }

.woocommerce div.product .woocommerce-tabs ul.tabs li {
	border: 0;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce p.stars a { border-bottom: 0; }

.woocommerce div.product .woocommerce-tabs .panel {
	background-color: #fff;
	border-radius: 5px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
	padding: <?php echo $single; ?>px;
}

.woocommerce p.stars a:not(:last-child) { margin-right: <?php echo $small; ?>px; }

.shop_table .actions .button {
	background-color: #ddd;
	color: #777;
}

.woocommerce-page #content table.cart td.actions .input-text, .woocommerce-page table.cart td.actions .input-text { width: 50%; }

.woocommerce .related.products { clear: both; }

/* CHECKOUT PAGE */

#order_review_heading { margin-top: 0; }

.woocommerce .woocommerce-checkout .col-1, .woocommerce .woocommerce-checkout .col-2 {
	float: none;
	width: 100%;
}

.woocommerce form .form-row { margin-bottom: <?php echo $half; ?>px; }

a.about_paypal {
	border-bottom: 0;
	margin-left: <?php echo $half; ?>px;
}

.woocommerce-checkout-payment { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); }

.woocommerce .woocommerce-checkout .col-1, .woocommerce-form-coupon-toggle { margin-bottom: <?php echo $single; ?>px; }

.woocommerce #payment #place_order { float: none; }

/* ACCOUNT PAGE */

.woocommerce-account .woocommerce h2 {
	margin-top: 0;
	text-align: center;
}

/* GENERAL WIDGETS */

.woocommerce.widget_rating_filter ul li a,
.woocommerce .woocommerce-widget-layered-nav-list__item a,
.woocommerce.widget_product_categories a,
.woocommerce.widget_product_tag_cloud a,
.woocommerce .product_list_widget a { border-bottom: 0; }

.widget_product_categories > ul,
.woocommerce.widget_rating_filter ul,
.woocommerce ul.product_list_widget {
	background-color: #fff;
	box-shadow: 0px 2px 1px rgba(0, 0, 0, 0.05);
	margin-left: 0;
	padding: <?php echo $single; ?>px;
	border-radius: 3px;
}

.widget_product_categories ul { list-style: none; }

.widget_product_categories ul li:not(:last-child), .woocommerce .product_list_widget li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.widget_product_categories .children {
	border-left: 3px solid rgba(0, 0, 0, 0.15);
	padding-left: <?php echo $half; ?>px;
}

/* WIDGET: LIST WIDGET */

.product_list_widget ins {
	font-weight: bold;
	text-decoration: none;
}

.woocommerce ul.product_list_widget li img {
	border-radius: 3px;
	box-shadow: 0 2px 3px rgba(0, 0, 0, 0.15);
	width: 70px;
}

.product_list_widget .product-title, .product_list_widget .star-rating {
	display: block;
	margin-bottom: <?php echo $small; ?>px;
}

/* WIDGET: FILTER BY PRICE */

.woocommerce.widget_price_filter .price_slider_wrapper .ui-widget-content { background-color: #ccc; }

.woocommerce.widget_price_filter .ui-slider .ui-slider-range, .woocommerce.widget_price_filter .ui-slider .ui-slider-handle { background-color: <?php echo $colors['site']['primary']; ?>; }

.widget_price_filter button[type="submit"], .widget_price_filter button[type="submit"]:hover { background-color: <?php echo $colors['site']['button-sec']; ?>; }

/* WIDGET: FILTER BY ATTRIBUTES */

.woocommerce-widget-layered-nav-list li { display: inline-block; }

.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item {
	background-color: #fff;
	border-radius: 3px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	margin-right: <?php echo $half; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.woocommerce-widget-layered-nav-list__item .count, .product-categories .count {
	color: #777;
	font-size: 0.9em;
}

.woocommerce-widget-layered-nav-list__item--chosen { border: 1px solid <?php echo $colors['site']['primary']; ?>; }

.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item--chosen a:before { font-size: 0.9em; }

/* QUERIES */

@media all and (min-width: <?php echo $site_width; ?>px) {
	[class*="block-full"] .woocommerce {
		margin-left: -<?php echo $quad; ?>px;
		margin-right: -<?php echo $quad; ?>px;
	}
}

@media all and (min-width: 900px) {
	/* HEADER */
	.header-cart { line-height: 1; }
	.woocommerce .quantity .qty {
		padding: 11px 7px 11px 7px;
		width: 100%;
	}
	.woocommerce div.product form.cart div.quantity {
		margin: 0;
		width: 15%;
	}
	.woocommerce div.product form.cart .button {
		margin-left: 3%;
		width: 82%;
	}
	/* WIDGET: SEARCH */
	.woocommerce-product-search:after {
		clear: both;
		content: '';
		display: table;
	}
	.woocommerce-product-search .search-field {
		border-radius: 3px 0 0 3px;
		border-width: 1px 0 1px 1px;
		float: left;
		line-height: 1;
		width: 70%;
	}
	.woocommerce-product-search button[type="submit"] {
		border-radius: 0 3px 3px 0;
		float: left;
		line-height: 1;
		padding: 17px 5px;
		width: 30%;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	/* BUTTONS */
	.woocommerce a.added_to_cart {
		margin-left: -<?php echo $double; ?>px;
		bottom: -<?php echo $lh + $lhh; ?>px;
		left: 50%;
		right: auto;
	}
	.woocommerce a.added_to_cart:before {
		border-left: 8px solid transparent;
		border-right: 8px solid transparent;
		border-bottom: 8px solid rgba(0, 0, 0, 0.8);
		border-top: 0;
		margin-left: -4px;
		left: 50%;
		top: -7px;
	}
	/* CONTENT */
	.woocomerce .style-minimal .site-main { padding: <?php echo $half; ?>px; }
}

@media all and (max-width: 768px) {
	.woocommerce .woocommerce-ordering, .woocommerce-page .woocommerce-ordering, .woocommerce div.product form.cart div.quantity { float: none; }
	.woocommerce ul.products li.product {
		float: none !important;
		width: 100% !important;
	}
	.woocommerce .quantity .qty { width: 100%; }
}