<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE;?></a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <?php echo USER_DISPLAYED_NAME;?> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="<?php echo isPage("dashboard")?"active":"";?>">
                    <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li class="<?php echo isPage("creations")?"active":"";?>">
                    <a href="#"><i class="fa fa-book fa-fw"></i>
                        <?php echo $iniLang["CREATIONS"]["CREATIONS"];?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="index.php?p=creations&c=all"><?php echo $iniLang["CREATIONS"]["ALL_MY_CREATIONS"];?></a>
                        </li>
                        <li>
                            <a href="index.php?p=creations&c=add"><?php echo $iniLang["CREATIONS"]["ADD_A_CREATION"];?></a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li><a href="#"><i class="fa fa-file-image-o fa-fw"></i> Images</a></li>
                <li class="<?php echo isPage("users")?"active":"";?>">
                    <a href="#"><i class="fa fa-users fa-fw"></i>
                        <?php echo $iniLang["USERS"]["USERS"];?><span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="index.php?p=users&c=all"><?php echo $iniLang["USERS"]["ALL_USERS"];?></a>
                        </li>
                        <li>
                            <a href="index.php?p=users&c=add"><?php echo $iniLang["USERS"]["ADD_A_USER"];?></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>