<style type="text/css">

    /*------------------------------*\
        $ADMIN BAR
    \*------------------------------*/

    <?php
        $bg_color = '#23282c';
        $height = 32;
    ?>

    .has-md-admin-bar {
        padding-top: <?php echo $height; ?>px;
    }

    .md-admin-bar {
        background-color: <?php echo $bg_color; ?>;
        color: #fff;
        font-size: 0.8em;
        height: <?php echo $height; ?>px;
        line-height: 1;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        z-index: 150;
    }

    .md-admin-bar-actions {
        float: left;
    }

    .md-admin-bar-actions::-webkit-scrollbar {
        display: none;
    }

    .md-admin-bar-links {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .md-admin-bar-item {
        display: inline-block;
    }

    .md-admin-bar .md-admin-bar-link {
        color: #fff;
        height: <?php echo $height; ?>px;
        display: block;
        padding: 8px;
    }

    .md-admin-bar-link:hover, .sub-menu .md-admin-bar-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .md-admin-bar .menu-item-sep {
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .md-admin-bar .sub-menu {
        background-color: <?php echo $bg_color; ?>;
        display: none;
        left: 0;
        right: auto;
        width: <?php echo $triple * 2; ?>px;
    }

    .md-admin-bar .sub-menu a {
        color: #fff;
        display: block;
        padding: <?php echo $small; ?>px <?php echo $half; ?>px;
    }

    .md-admin-bar .md-icon-loading {
        animation: none;
    }

    @media all and (min-width: 900px) {
        .md-admin-bar > .menu-item.menu-item-right {
            float: right;
        }
    }

    @media all and (max-width: 900px) {
        .md-admin-bar .menu-item-has-children:hover > .sub-menu {
            display: block;
            position: absolute;
        }
    }