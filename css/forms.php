<style type="text/css">

    /*------------------------------*\
        $FORMS
    \*------------------------------*/

    label {
        cursor: pointer;
        display: inline-block;
        margin-bottom: <?php echo $half; ?>px;
    }

    label.required, .required {
        color: #ae2525;
    }

    input, textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
        padding: 16px;
    }

    input[type="text"], input[type="email"], input[type="search"], input[type="url"], input[type="password"], textarea {
        background-color: #fff;
        border-radius: 0;
        border: 1px solid #ddd;
        margin-bottom: <?php echo $half; ?>px;
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

    input[type="text"]:focus, input[type="email"]:focus, input[type="search"]:focus, input[type="url"]:focus, input[type="password"]:focus, textarea:focus {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        outline: none;
    }

    select {
        max-width: 100%;
    }

    .form-input-name, .form-input-email {
        background-position: 16px center;
        background-repeat: no-repeat;
        padding-left: 45px;
    }

    .form-input-name {
        background-image: url('<?php echo MD_URL; ?>lib/assets/images/user.png');
    }

    .form-input-email {
        background-image: url('<?php echo MD_URL; ?>lib/assets/images/mail.png');
    }

    .email-form-title:empty, .email-form-footer:empty {
        display: none;
    }

    .email-form-footer {
        clear: both;
        font-size: 0.8em;
        font-style: italic;
        line-height: 1.5em;
        text-align: center;
    }

    .form-input, .form-submit {
        width: 100%;
    }

    .form-input, .form-submit:not(:last-child) {
        margin-bottom: <?php echo $half; ?>px;
    }

    .form-full .form-input {
        display: block;
    }

    @media all and (min-width: 700px) {
        [class*="form-attached"] {
            position: relative;
        }

        [class*="form-attached"] .form-input {
            border-right-width: 0;
            margin-bottom: 0;
            float: left;
            width: 78%;
        }

        .form-attached-2 .form-input {
            width: 39%;
        }

        [class*="form-attached"] .form-submit {
            border-radius: 0 2px 2px 0;
            border-width: 3px 3px 3px 0;
            float: left;
            font-size: inherit;
            line-height: inherit;
            padding: 16px 7px;
            width: 22%;
        }
    }