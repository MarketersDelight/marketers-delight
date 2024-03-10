<style type="text/css">

/*------------------------------*\
	$FORMS
\*------------------------------*/

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

/* STRUCTURES */

.form { width: 100%; }

.form,
.inputs,
.input-field,
.input-icon,
.search-form {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
}

.form-full { flex-direction: column; }

.form.inline, .form.inline .inputs { flex: 1; }

.form-full .inputs, .form-full .submit { width: 100%; }

.form-icons .input {
	background-color: transparent;
	border: 0;
	padding-left: 0;
}

.form-small .input { font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px; }

.form-icons .input:focus { box-shadow: none; }

.form-small .input,
.form-small .input-icon {
	padding-bottom: <?php echo $small; ?>px;
	padding-top: <?php echo $small; ?>px;
}

.input-field { gap: 0; }

.input-icon {
	color: <?php echo $colors['site']['text']; ?>;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
	justify-content: center;
}

.form-style .input { padding-left: <?php echo $half; ?>px; }

.form-style .input-icon {
	background-color: rgba(0, 0, 0, 0.15);
	border-right: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 5px 0 0 5px;
	padding-right: <?php echo $half; ?>px;
}

/* SEARCH */

.has-search .triggers { order: 3; }

.has-search .inputs, .has-search .input-field { flex: 1; }

.form-toggle .inputs,
.form-toggle .submit,
.has-search .search-form .trigger-text { display: none; }

.has-search .inputs,
.has-search .submit,
.form-toggle .trigger-search { display: block; }

/* QUERIES */

@media all and (min-width: 600px) {
	.form.inline .submit { flex: 0 1 25%; }
}

@media all and (max-width: 600px) {
	.form.multi { flex-direction: column; }
	.form.multi .inputs { flex-basis: 100%; }
	.form.multi .submit { width: 100%; }
}

/* TRIGGERS */

.trigger {
	align-items: center;
	cursor: pointer;
	display: flex;
	justify-content: center;
	position: relative;
	text-align: center;
}

.trigger-text { margin-left: <?php echo $small; ?>px; }

.hide-label .link-text, .hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before { content: '\e810'; }

.has-search:not(.has-cover) .trigger-search .trigger-icon:before,
.has-mobile-menu:not(.has-cover) .trigger-menu .trigger-icon:before { color: <?php echo $colors['site']['primary']; ?>; }
