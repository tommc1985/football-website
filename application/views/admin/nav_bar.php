<div class="navbar navbar-inverse navbar-fixed-top admin-menu <?php echo isset($doubleNav) && $doubleNav ? 'double-nav' : ''; ?>">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo site_url('admin'); ?>">Admin</a>
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse.admin-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse admin-menu">
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
                        <li>
                            <a href="<?php echo site_url('admin/auth/logout'); ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>