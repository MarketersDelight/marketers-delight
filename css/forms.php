<style type="text/css">

/*------------------------------*\
	$FORMS
\*------------------------------*/

label {
	cursor: pointer;
	display: inline-block;
	margin-bottom: <?php echo $half; ?>px;
}

label.required, .required { color: #ae2525; }

input, textarea {
	font-family: inherit;
	font-size: inherit;
	line-height: inherit;
	padding: <?php echo $half; ?>px;
}

input[type="text"], textarea,
input[type="url"], input[type="email"],
input[type="search"], input[type="password"] {
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

fieldset {
	border: 1px solid rgba(0, 0, 0, 0.15);
	padding: <?php echo $single; ?>px;
}

input[type="text"]:focus, textarea:focus,
input[type="url"]:focus, input[type="email"]:focus,
input[type="search"]:focus, input[type="password"]:focus {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	outline: none;
}

select { max-width: 100%; }

/* ICON FIELDS */

.fields-icons .form-field,
.has-search .form-controls .form-inputs {
	background-color: #fff;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 5px;
}

.fields-icons .form-input, .fields-icons .form-input:focus,
.has-search .form-controls .form-input {
	border: 0;
	box-shadow: none;
}

.form-field-icon {
	color: <?php echo $colors['site']['text']; ?>;
	font-size: 1.2em;
	line-height: 1;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
	text-align: center;
}

.form-field-icon + .form-input { padding-left: 0; }

.form-field-icon, .format .fields-icons .form-input { margin-bottom: 0; }

/* LAYOUT */

.form-full .form-field { display: block; }

.form-inputs, .form-full .form-submit { width: 100%; }

.search-form .form-submit { width: 40%; }

[class*="form-attached"] {
	align-items: center;
	display: flex;
	position: relative;
}

[class*="form-attached"] .form-field { margin-right: 2%; }

[class*="form-attached-"] .form-submit { flex: 1 0 auto; }

.form-multi-fields [class*="form-attached"] .form-field { width: auto; }

/* SEARCH */

.search-form .search-submit { margin-left: <?php echo $third; ?>px; }

.has-search .form-controls .form-inputs { padding-left: <?php echo $half; ?>px; }

/*
.form-inputs .trigger,
*/
.form-toggle .search-input, .form-toggle .search-submit,
.has-search .search-form .trigger-text { display: none; }

/*
.form-toggle .trigger { display: flex; }
*/
.has-search .search-input, .has-search .search-submit { display: block; }