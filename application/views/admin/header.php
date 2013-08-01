<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap-responsive.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/css/tables.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/admin/css/style.css"); ?>" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo site_url(); ?>"><?php echo Configuration::get('team_name'); ?> - Admin</a>
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li>
                            <a href="<?php echo site_url(); ?>">The Site</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Players<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("admin/player"); ?>">Players</a></li>
                                <li><a href="<?php echo site_url("admin/player-registration"); ?>">Player Registrations</a></li>
                                <li><a href="<?php echo site_url("admin/award"); ?>">Awards</a></li>
                                <li><a href="<?php echo site_url("admin/player-award"); ?>">Player Awards</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Matchday<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("admin/match"); ?>">Matches</a></li>
                                <li><a href="<?php echo site_url("admin/competition"); ?>">Competitions</a></li>
                                <li><a href="<?php echo site_url("admin/competition-stage"); ?>">Competition Stages</a></li>
                                <li><a href="<?php echo site_url("admin/official"); ?>">Officials</a></li>
                                <li><a href="<?php echo site_url("admin/opposition"); ?>">Opposition</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Leagues<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("admin/league"); ?>">Leagues</a></li>
                                <li><a href="<?php echo site_url("admin/league-match"); ?>">League Matches</a></li>
                                <li><a href="<?php echo site_url("admin/league-registration"); ?>">League Registrations</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Miscellaneous<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("admin/calendar-event"); ?>">Calendar Events</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">

    <?php
    if (isset($message)) {
        echo $message;
    }