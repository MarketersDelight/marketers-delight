<style type="text/css">

/*------------------------------*\
	$FORMS
\*------------------------------*/

/* INPUTS */

label { cursor: pointer; }

input, textarea {
	font-family: inherit;
	font-size: inherit;
	line-height: 1;
	padding: <?php echo $half; ?>px;
}

input[type="text"],
input[type="url"],
input[type="email"],
input[type="search"],
input[type="password"],
textarea,
.form-icons .input-field {
	background-color: #fff;
	border-radius: 5px;
	border: 1px solid rgba(0, 0, 0, 0.2);
	position: relative;
	width: 100%;
	-webkit-appearance: none;
}

input[type="text"]:focus,
input[type="url"]:focus,
input[type="email"]:focus,
input[type="search"]:focus,
input[type="password"]:focus,
textarea:focus {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	outline: none;
}

textarea { padding: <?php echo $single; ?>px; }

select {
	font-size: inherit;
	max-width: 100%;
	padding: <?php echo $third; ?>px;
	width: 100%;
}

.required { color: #ae2525; }

/* TRIGGERS */

.trigger {
	align-items: center;
	cursor: pointer;
	display: flex;
	justify-content: center;
	position: relative;
	text-align: center;
}

.trigger-icon, .hide-label .link-icon, .link-icon:first-child {
	font-size: <?php echo round( $header['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
	line-height: 1;
}

.trigger-text { margin-left: <?php echo $small; ?>px; }

.hide-label .link-text, .hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before {
	color: <?php echo $colors['site']['primary']; ?>;
	content: '\e810';
}

/* TOOLTIP */

.tooltip {
	background-color: rgba(0, 0, 0, 0.8);
	border-radius: 5px;
	color: #fff;
	cursor: default;
	display: none;
	font-size: 14px;
	line-height: 1;
	margin-left: -80px;
	padding: <?php echo $third; ?>px;
	position: absolute;
		left: 50%;
		top: -40px;
	text-align: center;
	width: 160px;
}

.tooltip:after {
	border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
	border-style: solid;
	border-width: 5px;
	content: '';
	margin-left: -5px;
	position: absolute;
		left: 50%;
		top: 100%;
}

.tooltip-parent { position: relative; }

.tooltip-parent:hover .tooltip { display: block; }

/* STRUCTURES */

.input-icon, .search-form {
	align-items: center;
	display: flex;
}

.has-search .triggers, .search-form .inputs { margin-right: <?php echo $half; ?>px; }

.input-field { display: flex; }

.form-full, .inline-form, .form-full .input-field { margin-bottom: <?php echo $half; ?>px; }

.form-icons .input {
	background-color: transparent;
	border: 0;
	padding-left: 0;
}

.form-style .input { padding-left: <?php echo $half; ?>px; }

.form-small .input { font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px; }

.form-icons .input:focus { box-shadow: none; }

.form-small .input, .form-small .input-icon {
	padding-bottom: <?php echo $small; ?>px;
	padding-top: <?php echo $small; ?>px;
}

.input-icon {
	color: <?php echo $colors['site']['text']; ?>;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
	justify-content: center;
}

.form-style .input-icon {
	background-color: rgba(0, 0, 0, 0.15);
	border-right: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 5px 0 0 5px;
}

.form-full .submit { width: 100%; }

/* SEARCH */

.has-search .inputs, .has-search .input-field { flex: 1; }

.form-toggle .inputs, .form-toggle .submit,
.has-search .search-form .trigger-text { display: none; }

.has-search .inputs, .has-search .submit,
.form-toggle .trigger-search { display: block; }

/* QUERIES */

@media all and (min-width: 800px) {
	.show-mobile { display: none !important; }
	.inline-form, .inline-form .inputs {
		align-items: center;
		display: flex;
		flex: 1;
	}
	.inline-form .inputs, .inline-form .input-field:not(:last-child) { margin-right: <?php echo $half; ?>px; }
}

@media all and (max-width: 800px) {
	.show-desktop { display: none !important; }
	.inline-form .submit { width: 100%; }
	.inline-form .input-field, .inline-form .inputs { margin-bottom: <?php echo $half; ?>px; }
	.trigger-icon, .hide-label .link-icon,
	.hide-label-mobile .link-icon { font-size: <?php echo round( $header['font_size']['desktop'] * 1.5 ); ?>px; }
}