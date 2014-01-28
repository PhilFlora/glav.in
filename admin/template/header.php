<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="<?php echo base_url(); ?>admin/template/css/normalize.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>admin/template/css/main.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div id="about-wrap">
            <div id="about">
                <button id="about-close-btn">Close</button>

                <div id="about-content">
                    <img src="<?php echo base_url(); ?>admin/template/img/about_logo.png" id="about-logo">
                    <p>When you just need a simple CMS for a simple website, Glav.in may be your answer. It's easy to install and can be up in running in minutes! Why waste your time?</p>
                    <a href="http://glav.in" class="btn">Glav.in Documentation</a>
                    <a href="https://github.com/GlavinCMS/glav.in" class="btn">Glav.in on Github</a>
                </div><!-- #about-content -->
            </div><!-- #about -->
        </div><!-- #about -->

        <div id="admin-wrap">
            <?php if ( isset( $is_logged_in ) ) { ?>
            <div id="header-container">
                <header class="wrapper clearfix">
                    <div id="header-toolbar">
                        <h1 id="logo"><a href="<?php echo base_url(); ?>admin/pages" title="Pages"><span class="hidden">Glav.in</span></a></h1>
                        <?php echo $is_logged_in ? '<button id="nav-button">Navigation</button>' : ''; ?>
                    </div><!-- #header-toolbar -->
                    <?php if( $is_logged_in ) { ?>
                    <div id="nav-container">
                        <nav>
                            <ul>
                                <li><a href="<?php echo base_url(); ?>admin/pages">Pages</a></li>
                                <li><a href="<?php echo base_url(); ?>admin/users">Users</a></li>
                                <li><a href="<?php echo base_url(); ?>admin/settings">Settings</a></li>
                                <li><a href="<?php echo base_url(); ?>admin/logout">Logout</a></li>
                                <li><a href="#" class="about-link">About Glav.in</a></li>
                            </ul>
                        </nav>
                    </div><!-- #nav-container -->
                    <? } ?>
                </header>
            </div>
            <?php } ?>
            <div id="page-title-wrap">
                <div id="page-title">
                    <h1><? echo $title; ?></h1>
                </div><!-- #page-title -->
            </div><!-- #page-title-wrap -->

            <div id="main-container">
                <div id="main wrapper clearfix">