<style type="text/css">

    /*------------------------------*\
        $COLUMNS
    \*------------------------------*/

    .col {
        position: relative;
    }

    @media all and (min-width: 900px) {
        .col {
            float: left;
        }

        .columns-flex:not([class*="block-"]) {
            padding-left: 5px;
            padding-right: 5px;
        }

        .columns-flex > .col {
            display: inline-block;
            float: none;
            margin-left: -5px;
            vertical-align: top;
        }

        [class*="columns-"] .col-right {
            float: right;
        }

        .width-50, .columns-2 > .col {
            width: 50%;
        }

        .columns-3 > .col {
            width: 33.333333333%;
        }

        .width-25, .columns-4 > .col {
            width: 25%;
        }

        .width-20, .columns-5 > .col {
            width: 20%;
        }

        .columns-6 > .col {
            width: 16.666666667%;
        }

        .columns-10-90 > .col2 {
            width: 90%;
        }

        .width-80, .columns-80-20 > .col1, .columns-20-80 > .col2 {
            width: 80%;
        }

        .width-75, .columns-25-75 > .col2 {
            width: 75%;
        }

        .width-70, .columns-70-30 > .col1, .columns-30-70 > .col2 {
            width: 70%;
        }

        .width-65, .columns-65-35 > .col1, .columns-35-65 > .col2 {
            width: 65%;
        }

        .width-60, .columns-60-40 > .col1, .columns-40-60 > .col2 {
            width: 60%;
        }

        .width-55, .columns-55-45 > .col1, .columns-45-55 > .col2 {
            width: 55%;
        }

        .width-45, .columns-55-45 > .col2, .columns-45-55 > .col1 {
            width: 45%;
        }

        .width-40, .columns-60-40 > .col2, .columns-40-60 > .col1 {
            width: 40%;
        }

        .width-35, .columns-65-35 > .col2, .columns-35-65 > .col1 {
            width: 35%;
        }

        .width-30, .columns-70-30 > .col2, .columns-30-70 > .col1 {
            width: 30%;
        }

        .width-25, .columns-25-75 > .col1 {
            width: 25%;
        }

        .width-20, .columns-80-20 > .col2, .columns-20-80 > .col1 {
            width: 20%;
        }

        .columns-10-90 > .col1 {
            width: 10%;
        }
    }

    @media all and (min-width: 700px) {
        .columns-half {
            margin-left: -<?php echo $half; ?>px;
        }

        .columns-half > .col {
            padding-left: <?php echo $half; ?>px;
        }

        .columns-single {
            margin-left: -<?php echo $single; ?>px;
        }

        .columns-single > .col {
            padding-left: <?php echo $single; ?>px;
        }

        .columns-mid {
            margin-left: -<?php echo $mid; ?>px;
        }

        .columns-mid > .col {
            padding-left: <?php echo $mid; ?>px;
        }

        .columns-double {
            margin-left: -<?php echo $double; ?>px;
        }

        .columns-double > .col {
            padding-left: <?php echo $double; ?>px;
        }

        .columns-triple {
            margin-left: -<?php echo $triple; ?>px;
        }

        .columns-triple > .col {
            padding-left: <?php echo $triple; ?>px;
        }
    }

    @media all and (max-width: 700px) {
        .col.mt-single {
            margin-top: 0;
        }
    }