<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Football Website</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link href="/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/assets/css/tables.css" rel="stylesheet">
    <link href="/assets/modules/fantasy-football/css/fantasy-football.css" rel="stylesheet">


    <link href="/bootstrap/docs/assets/css/docs.css" rel="stylesheet">
    <link href="/assets/themes/default/css/style.css" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="nav-collapse collapse">
            <ul class="nav">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Club Info<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/club-history">History</a></li>
                        <li><a href="/club-officials">Officials</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">News &amp; Articles<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/news">News</a></li>
                        <li><a href="/article">Articles</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Matchday<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/player">Squad List</a></li>
                        <li><a href="/match">Fixtures &amp; Results</a></li>
                        <?php
                        $menuLeagues = $this->League_model->fetchAll(array(
                            'season' => Season_model::fetchCurrentSeason()));
                        foreach ($menuLeagues as $menuLeague) { ?>
                        <li><a href="/league/view/id/<?php echo $menuLeague->id; ?>"><?php echo $menuLeague->short_name; ?> League Table</a></li>
                        <?php
                        } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Statistics<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/club-statistics/view">Club Statistics</a></li>
                        <li><a href="/player-statistics/view">Player Statistics</a></li>
                        <li><a href="/fantasy-football/view">Fantasy Football</a></li>
                        <li><a href="/head-to-head">Head to Head</a></li>
                        <?php
                        foreach ($menuLeagues as $menuLeague) { ?>
                        <li><a href="/league-statistics/view/id/<?php echo $menuLeague->id; ?>"><?php echo $menuLeague->short_name; ?> Statistics</a></li>
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
                                <li><a href="/club-statistics/view/season/<?php echo $menuSeason; ?>">Club Statistics</a></li>
                                <li><a href="/player-statistics/view/season/<?php echo $menuSeason; ?>">Player Statistics</a></li>
                                <li><a href="/fantasy-football/view/season/<?php echo $menuSeason; ?>">Fantasy Football</a></li>
                                <?php
                                $menuLeagues = $this->League_model->fetchAll(array(
                                    'season' => $menuSeason));
                                foreach ($menuLeagues as $menuLeague) { ?>
                                <li><a href="/league-statistics/view/id/<?php echo $menuLeague->id; ?>"><?php echo $menuLeague->short_name; ?> Statistics</a></li>
                                <li><a href="/league/view/id/<?php echo $menuLeague->id; ?>"><?php echo $menuLeague->short_name; ?> League Table</a></li>
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
<div class="container">
    <div class="page-header">
        <h1><?php echo Configuration::get('team_name'); ?> <small>...</small></h1>
    </div>