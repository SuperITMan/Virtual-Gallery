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
                <li>
                    <a href="index.php?p=users&c=me">
                        <i class="fa fa-user fa-fw"></i> <?php echo $iniLang["USERS"]["MY_PROFILE"];?>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=settings">
                        <i class="fa fa-cog fa-fw"></i> <?php echo $iniLang["SETTINGS"]["TITLE"];?>
                    </a>
                </li>
                <?php if(isSuperAdmin()):?>
                <li class="divider"></li>
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out fa-fw"></i> <?php echo $iniLang["COMMON"]["VERBS"]["LOGOUT"];?>
                    </a>
                </li>
                <?php endif;?>
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
                        <li>
                            <a href="index.php?p=users&c=me"><?php echo $iniLang["USERS"]["MY_PROFILE"];?></a>
                        </li>
                    </ul>
                </li>

                <li class="<?php echo isPage("news")?"active":"";?>">
                    <a href="#"><i class="fa fa-newspaper-o fa-fw"></i>
                        <?php echo $iniLang["NEWS"]["NEWS"];?><span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="index.php?p=news&c=all"><?php echo $iniLang["NEWS"]["ALL_NEWS"];?></a>
                        </li>
                        <li>
                            <a href="index.php?p=news&c=add"><?php echo $iniLang["NEWS"]["ADD_A_NEWS"];?></a>
                        </li>
                    </ul>
                </li>

                <?php if(isSuperAdmin()):?>
                <li class="<?php echo isPage("settings")?"active":"";?>">
                    <a href="index.php?p=settings"><i class="fa fa-cog fa-fw"></i> <?php echo $iniLang["SETTINGS"]["TITLE"];?></a>
                </li>
                <?php endif;?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>