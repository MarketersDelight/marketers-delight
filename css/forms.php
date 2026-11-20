<style type="text/css">

/*------------------------------*\
	$FORMS
\*------------------------------*/

label { cursor: pointer; }

input, textarea {
	font-family: inherit;
	font-size: inherit;
	line-height: 1;
	padding: var(--md-half);
}

input[type="text"], input[type="url"], input[type="email"], input[type="search"], input[type="password"], textarea,
.form-icons .input-field {
	--md-text: var(--md-form-text);
	--md-text-muted: var(--md-form-text-muted);
	background-color: var(--md-field-bg);
	color: var(--md-text);
	border-color: var(--md-form-border);
	border-radius: var(--md-field-radius);
	border-style: solid;
	border-width: var(--md-field-border-width);
	position: relative;
	width: 100%;
	-webkit-appearance: none;
}

:is(input[type="text"], input[type="url"], input[type="email"], input[type="search"], input[type="password"], textarea)::placeholder { color: var(--md-text-muted); }

input[type="text"]:focus, input[type="url"]:focus, input[type="email"]:focus, input[type="search"]:focus, input[type="password"]:focus, textarea:focus,
.form-icons .input-field:focus-within {
	border-color: var(--md-links);
	box-shadow: var(--md-field-focus-shadow);
	outline: var(--md-field-focus-outline) solid var(--md-links);
	outline-offset: calc(-1 * var(--md-field-focus-outline));
}

input.no-style, input.no-style:focus {
	background-color: transparent;
	border-radius: inherit;
	border: 0;
	box-shadow: none;
	padding: 0;
	outline: none;
}

textarea {
	line-height: var(--md-line-height);
	padding: var(--md-single);
}

select {
	font-size: inherit;
	max-width: 100%;
	padding: var(--md-third);
	width: 100%;
}

.required { color: var(--md-color-danger); }

/* STRUCTURES */

.form { width: 100%; }

.form, .inputs, .input-field, .input-icon, .search-form {
	align-items: center;
	display: flex;
	gap: var(--md-half);
}

.form-full { flex-direction: column; }

.form.inline, .form.inline .inputs { flex: 1; }

.form-full .inputs, .form-full .submit { width: 100%; }

.form-icons .input {
	background-color: transparent;
	border: 0;
	padding-inline-start: 0;
}

.form-small .input { font-size: var(--md-font-size-tiny); }

.form-icons .input:focus {
	border-color: transparent;
	box-shadow: none;
	outline: none;
}

.form-small .input, .form-small .input-icon { padding-block: var(--md-small); }

.input-field { gap: 0; }

<?php if ( $buttons['form'] ) : ?>
/* FORM BUTTONS */
input[type="submit"], .button.submit, .wp-block-search__button {<?php echo $design->button_vars( $buttons['form'] ); ?> }
<?php endif; ?>

/* JOIN */

.inputs { gap: var(--md-field-gap); }

.inputs > :not(:first-child) {
	border-start-start-radius: var(--md-field-inner-radius);
	border-end-start-radius: var(--md-field-inner-radius);
	margin-inline-start: var(--md-field-overlap);
}

.inputs > :not(:last-child) {
	border-start-end-radius: var(--md-field-inner-radius);
	border-end-end-radius: var(--md-field-inner-radius);
}

.search-form > .inputs > :last-child {
	border-start-end-radius: var(--md-field-inner-radius);
	border-end-end-radius: var(--md-field-inner-radius);
}

.search-form > .submit {
	align-self: var(--md-field-align);
	border-start-start-radius: var(--md-field-button-radius);
	border-end-start-radius: var(--md-field-button-radius);
	margin-inline-start: calc(var(--md-field-gap) - var(--md-half) + var(--md-field-overlap));
}

.input-icon {
	color: var(--md-text);
	padding-inline: var(--md-half);
	justify-content: center;
}

.form-style .input { padding-inline-start: var(--md-half); }

.form-style .input-icon {
	background-color: var(--md-tint-strong);
	border-inline-end: var(--md-border-width) solid var(--md-border);
	border-radius: var(--md-field-radius) 0 0 var(--md-field-radius);
	padding-inline-end: var(--md-half);
}

/* TRIGGERS */

.toggle-search .triggers { order: 3; }

.trigger {
	align-items: center;
	cursor: pointer;
	display: inline-flex;
	justify-content: center;
	position: relative;
	text-align: center;
}

.trigger-text { margin-inline-start: var(--md-small); }

.hide-label :is(.link-wrap, .link-name, .trigger-text) { display: none; }

.toggle-search .trigger-search .trigger-icon:before,
.toggle-menu .trigger-menu .trigger-icon:before { content: '\e810'; }

.toggle-search:not(.cover) .trigger-search .trigger-icon:before,
.toggle-menu:not(.cover) .trigger-menu .trigger-icon:before { color: var(--md-header-menu-hover); }

/* TOGGLES */

.search-form .inputs { flex: 1; }

.form-toggle .inputs,
.form-toggle .submit,
.toggle-search .search-form .trigger-text { display: none; }

.toggle-search .inputs, .form-toggle .trigger-search { display: flex; }

.toggle-search .submit { display: block; }

/* QUERIES */

@media (min-width: 600px) {
	.form.inline .submit { flex: 0 1 25%; }
	.form.inline {
		align-items: var(--md-field-align);
		gap: var(--md-field-gap);
	}
	.form.inline > .inputs > :last-child {
		border-start-end-radius: var(--md-field-inner-radius);
		border-end-end-radius: var(--md-field-inner-radius);
	}
	.form.inline > .submit {
		border-start-start-radius: var(--md-field-button-radius);
		border-end-start-radius: var(--md-field-button-radius);
		margin-inline-start: var(--md-field-overlap);
	}
}

@media (max-width: 600px) {
	.form:is(.multi, .inline) { flex-direction: column; }
	.form:is(.multi, .inline) .inputs { flex-basis: 100%; flex-direction: column; width: 100%; }
	.form:is(.multi, .inline) .submit { width: 100%; }
	.form:is(.multi, .inline) .inputs > :not(:first-child) {
		border-start-start-radius: var(--md-field-inner-radius);
		border-start-end-radius: var(--md-field-inner-radius);
		border-end-start-radius: var(--md-field-radius);
		margin-inline-start: 0;
		margin-block-start: var(--md-field-overlap);
	}
	.form:is(.multi, .inline) .inputs > :not(:last-child) {
		border-start-end-radius: var(--md-field-radius);
		border-end-start-radius: var(--md-field-inner-radius);
		border-end-end-radius: var(--md-field-inner-radius);
	}
	.form:is(.multi, .inline) .inputs > :not(:first-child):not(:last-child) { border-start-end-radius: var(--md-field-inner-radius); }
}

@media (max-width: 900px) {
	.hide-label-mobile :is(.link-wrap, .link-name, .trigger-text) { display: none; }
}
