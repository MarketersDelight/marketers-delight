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

input[type="text"], input[type="url"], input[type="email"], input[type="search"], input[type="password"], textarea, .icon-fields {
	background-color: #fff;
	border-radius: 5px;
	border: 1px solid rgba(0, 0, 0, 0.2);
	position: relative;
	width: 100%;
	-webkit-appearance: none;
}

textarea {
	padding: <?php echo $single; ?>px;
	width: 100%;
	-webkit-appearance: none;
}

input[type="text"]:focus, input[type="url"]:focus, input[type="email"]:focus, input[type="search"]:focus, input[type="password"]:focus, textarea:focus {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	outline: none;
}

select { max-width: 100%; }

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

.trigger-icon {
	font-size: <?php echo round( $header['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
	line-height: 1;
}

.trigger-text { margin-left: <?php echo $third; ?>px; }

.hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before {
	color: <?php echo $colors['site']['primary']; ?>;
	content: '\e810';
}

/* LAYOUT */

.inline-form { display: flex; }

.form-inputs, .inline-form .input-icon {
	align-items: center;
	display: flex;
}

/* ICON FIELDS */

.icon-fields .input {
	background-color: rgba(0, 0, 0, 0.02);
	border: 0;
}

.icon-fields .input:focus { box-shadow: none; }

.input-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-right: 1px solid <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
}

.input-icon label { padding: <?php echo $third; ?>px <?php echo $half; ?>px; }

/* SEARCH */

.search-submit {
	flex-basis: 40%;
	margin-left: <?php echo $third; ?>px;
}
/*
.has-search .form-controls .form-inputs { padding-left: <?php echo $half; ?>px; }
*/

.form-toggle .search-input, .form-toggle .search-submit,
.has-search .search-form .trigger-text { display: none; }

.has-search .search-input, .has-search .search-submit,
.form-toggle .trigger-search { display: block; }

/* STYLES */

.form-small .input {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	padding: <?php echo $third; ?>px;
}

.form-small .input-icon label {
	padding-bottom: <?php echo $small; ?>px;
	padding-top: <?php echo $small; ?>px;
}
