<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

button {
	appearance: none;
	background-color: transparent;
	border: 0;
	font: inherit;
}

input[type="submit"],
.link, .tag, .button, .wp-element-button {
	align-items: center;
	display: inline-flex;
	gap: var(--md-third);
	line-height: 1;
}

.cta, .wp-block-buttons {
	align-items: center;
	display: flex;
	gap: var(--md-half) calc(var(--md-half) + var(--md-third));
	justify-content: center;
}

/* LINKS */

.link:hover { text-decoration: none; }

.link-wrap {
	display: inline-flex;
	flex-direction: column;
	gap: var(--md-third);
	text-align: initial;
}

.link-icon, .trigger-icon, .input-icon {
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
}

.link-subtitle {
	font-size: calc(var(--md-font-size-sm) - 2px);
	font-weight: normal;
}

/* TAGS */

.tag {
	background-color: var(--md-content-border);
	border-radius: 50px;
	color: var(--md-site-text);
	flex-shrink: 0;
	font-size: var(--md-font-size-sm);
	padding: var(--md-third) var(--md-half);
	text-decoration: none;
	transition: var(--md-transition);
}

.tag:hover {
	background-color: var(--md-content-border);
	color: var(--md-site-links);
}

/* BUTTONS */

input[type="submit"],
.button, .wp-element-button {
	appearance: none;
	background-color: var(--md-action-primary);
	border: 0;
	border-radius: var(--md-border-radius);
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: var(--md-action-primary-text);
	cursor: pointer;
	font-size: inherit;
	font-family: inherit;
	font-style: normal;
	justify-content: center;
	padding: var(--md-half) calc(var(--md-half) + var(--md-third));
	text-decoration: none;
	transition: var(--md-transition);
}

a.button { text-decoration: none; }

input[type="submit"]:hover,
.button:hover, .wp-element-button:hover { transform: translateY(1px); }

/* COLORS */

.button.white {
	background-color: var(--md-color-white);
	color: var(--md-site-text);
}

.button.button-sec {
	background-color: var(--md-action-secondary);
	color: var(--md-action-secondary-text);
}

.button.button-outline,
.is-style-outline .wp-element-button {
	background-color: transparent;
	border: 3px solid var(--md-action-primary);
	color: var(--md-action-primary);
}

.button.button-outline.white {
	border-color: var(--md-color-white);
	color: var(--md-color-white);
}

/* SIZES */

.button.button-small {
	font-size: var(--md-font-size-sm);
	gap: var(--md-third);
	padding: var(--md-third) var(--md-half);
}

.button-small .link-icon { font-size: calc(var(--md-font-size) + 2px); }

.button.button-large {
	font-size: 1.4em;
	padding: calc(var(--md-half) + var(--md-small)) var(--md-single);
}

/* STYLES */

.button.button-cancel { background-color: var(--md-color-danger); }

.button.button-disabled {
	background-color: #999;
	cursor: not-allowed;
}

.button.pill { border-radius: 50px; }

.button-arrow { flex-direction: row; }

.button.button-arrow:after {
	content: '\e80f';
	font-family: 'md-icon';
	transition: var(--md-transition);
}

.button.button-arrow:hover:after { transform: translateX(4px); }

.button-arrow.down:after { content: '\e80e'; }

.button-arrow.down:hover:after { transform: none; }

/* QUERIES */

@media (max-width: 900px) {
	.cta .link {
		flex: 1;
		justify-content: center;
	}
}
