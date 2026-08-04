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
	background-color: var(--md-form-background);
	border-radius: var(--md-border-radius);
	border: 1px solid rgba(0, 0, 0, 0.2);
	position: relative;
	width: 100%;
	-webkit-appearance: none;
}

input[type="text"]:focus, input[type="url"]:focus, input[type="email"]:focus, input[type="search"]:focus, input[type="password"]:focus, textarea:focus {
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	outline: none;
}

input.no-style, input.no-style:focus {
	background-color: transparent;
	border-radius: inherit;
	border: 0;
	box-shadow: none;
	padding: 0;
}

textarea { padding: var(--md-single); }

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

.form-small .input { font-size: calc(var(--md-font-size-sm) - 2px); }

.form-icons .input:focus { box-shadow: none; }

.form-small .input, .form-small .input-icon { padding-block: var(--md-small); }

.input-field { gap: 0; }

.input-icon {
	color: var(--md-site-text);
	padding-inline: var(--md-half);
	justify-content: center;
}

.form-style .input { padding-inline-start: var(--md-half); }

.form-style .input-icon {
	background-color: rgba(0, 0, 0, 0.15);
	border-inline-end: 1px solid var(--md-content-border);
	border-radius: var(--md-border-radius) 0 0 var(--md-border-radius);
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

.hide-label .link-text, .hide-label .trigger-text { display: none; }

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
}

@media (max-width: 600px) {
	.form.multi { flex-direction: column; }
	.form.multi .inputs { flex-basis: 100%; }
	.form.multi .submit { width: 100%; }
}

@media (max-width: 900px) {
	.hide-label-mobile .trigger-text { display: none; }
}
