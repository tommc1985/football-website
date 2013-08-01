<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Football Website</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("bootstrap/docs/assets/css/bootstrap-responsive.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/css/tables.css"); ?>" rel="stylesheet">
    <link href="<?php echo site_url("assets/modules/fantasy-football/css/fantasy-football.css"); ?>" rel="stylesheet">


    <?php /*<link href="<?php echo site_url("bootstrap/docs/assets/css/docs.css"); ?>" rel="stylesheet">*/ ?>
    <link href="<?php echo site_url("assets/themes/default/css/style.css"); ?>" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-fixed-top frontend-menu <?php echo isset($doubleNav) && $doubleNav ? 'double-nav' : ''; ?>">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo site_url(); ?>"><?php echo Configuration::get('team_name'); ?></a>
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse.frontend-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse frontend-menu">
                    <ul class="nav">
                        <li>
                            <a href="<?php echo site_url(); ?>">Home</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Club Info<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("club-history"); ?>">History</a></li>
                                <li><a href="<?php echo site_url("club-officials"); ?>">Officials</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">News &amp; Articles<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("news"); ?>">News</a></li>
                                <li><a href="<?php echo site_url("article"); ?>">Articles</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Matchday<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("player"); ?>">Squad List</a></li>
                                <li><a href="<?php echo site_url("match"); ?>">Fixtures &amp; Results</a></li>
                                <?php
                                $menuLeagues = $this->League_model->fetchAll(array(
                                    'season' => Season_model::fetchCurrentSeason()));
                                foreach ($menuLeagues as $menuLeague) { ?>
                                <li><a href="<?php echo site_url("league/view/id/{$menuLeague->id}"); ?>"><?php echo $menuLeague->short_name; ?> League Table</a></li>
                                <?php
                                } ?>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Statistics<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("club-statistics/view"); ?>">Club Statistics</a></li>
                                <li><a href="<?php echo site_url("player-statistics/view"); ?>">Player Statistics</a></li>
                                <li><a href="<?php echo site_url("fantasy-football/view"); ?>">Fantasy Football</a></li>
                                <li><a href="<?php echo site_url("head-to-head"); ?>">Head to Head</a></li>
                                <?php
                                foreach ($menuLeagues as $menuLeague) { ?>
                                <li><a href="<?php echo site_url("league-statistics/view/id/{$menuLeague->id}"); ?>"><?php echo $menuLeague->short_name; ?> Statistics</a></li>
                                <?php
                                } ?>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Archive<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            <?php
                            foreach ((array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown())  as $menuSeason => $menuSeasonFriendly) {
                                if ($menuSeason != Season_model::fetchCurrentSeason()) { ?>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><?php echo $menuSeasonFriendly; ?></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url("match/index/season/{$menuSeason}"); ?>">Fixtures &amp; Results</a></li>
                                        <li><a href="<?php echo site_url("player/index/season/{$menuSeason}"); ?>">Squad List</a></li>
                                        <li><a href="<?php echo site_url("club-statistics/view/season/{$menuSeason}"); ?>">Club Statistics</a></li>
                                        <li><a href="<?php echo site_url("player-statistics/view/season/{$menuSeason}"); ?>">Player Statistics</a></li>
                                        <li><a href="<?php echo site_url("fantasy-football/view/season/{$menuSeason}"); ?>">Fantasy Football</a></li>
                                        <?php
                                        $menuLeagues = $this->League_model->fetchAll(array(
                                            'season' => $menuSeason));
                                        foreach ($menuLeagues as $menuLeague) { ?>
                                        <li><a href="<?php echo site_url("league-statistics/view/id/{$menuLeague->id}"); ?>"><?php echo $menuLeague->short_name; ?> Statistics</a></li>
                                        <li><a href="<?php echo site_url("league/view/id/{$menuLeague->id}"); ?>"><?php echo $menuLeague->short_name; ?> League Table</a></li>
                                        <?php
                                        } ?>
                                    </ul>
                                </li>
                            <?php
                                }
                            } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($doubleNav) && $doubleNav) {
    $this->load->view('admin/nav_bar');
} ?>
<div class="container">