<style type="text/css">

/*------------------------------*\
	$FORMS
\*------------------------------*/

label {
	cursor: pointer;
	display: inline-block;
}

.required { color: #ae2525; }

input, textarea {
	font-family: inherit;
	font-size: inherit;
	line-height: inherit;
	padding: <?php echo $half; ?>px;
}

input[type="text"], textarea, input[type="url"], input[type="email"], input[type="search"], input[type="password"] {
	background-color: #fff;
	border-radius: 5px;
	border: 1px solid rgba(0, 0, 0, 0.2);
	line-height: 1;
	position: relative;
	width: 100%;
	-webkit-appearance: none;
}

textarea {
	padding: <?php echo $single; ?>px;
	width: 100%;
	-webkit-appearance: none;
}

input[type="text"]:focus, textarea:focus, input[type="url"]:focus, input[type="email"]:focus, input[type="search"]:focus, input[type="password"]:focus {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	outline: none;
}

select { max-width: 100%; }

/* LAYOUT */

.fields-icons .form-field, .form-inputs,
.search-form, .wp-block-search__inside-wrapper {
	align-items: center;
	display: flex;
}

.form-inputs, .form-full .form-submit { width: 100%; }

.form-full .form-field:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

[class*="form-attached"] {
	align-items: center;
	display: flex;
	position: relative;
}

[class*="form-attached"] .form-field { margin-right: 2%; }

[class*="form-attached-"] .form-submit { flex: 1 0 auto; }

/* SEARCH */

.search-form .search-submit {
	margin-left: <?php echo $third; ?>px;
	width: 40%;
}

.has-search .form-controls .form-inputs { padding-left: <?php echo $half; ?>px; }

.form-toggle .search-input, .form-toggle .search-submit,
.has-search .search-form .trigger-text { display: none; }

.has-search .search-input, .has-search .search-submit, .form-toggle .trigger-search { display: block; }

/* ICON FIELDS */

.fields-icons .form-field, .has-search .form-inputs {
	background-color: #fff;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 5px;
}

.fields-icons .form-input, .fields-icons .form-input:focus,
.has-search .form-input, .has-search .form-input:focus {
	border: 0;
	box-shadow: none;
}

.form-field-icon {
	color: <?php echo $colors['site']['text']; ?>;
	font-size: 1.2em;
	line-height: 1;
	text-align: center;
	width: <?php echo $double; ?>px;
}

.form-field-icon + .form-input { padding-left: 0; }
