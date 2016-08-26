<!-- Add user page -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $iniLang["USERS"]["ADD_A_USER"];?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php
    if (isset($_POST["username"], $_POST["password"])) {
        if($userId = addUser($_POST))
            echo "<script>window.location = \"index.php?p=users&c=all&id=".$userId."\";</script>";
        else
            displayErrorMessage("Il y a eu un problème...");
    }
    ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $iniLang["USERS"]["ADDING_OF_A_USER_ON_YOUR_WEBSITE"];?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
<!--                    <div class="container"-->
                    <form class="form-horizontal add-user" role="form" method="POST" action="index.php?p=users&c=add">
                        <div class="form-group">
                            <label for="firstName" class="col-xs-3 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["USERS"]["FIRST_NAME"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="firstName"
                                       name="firstName"
                                       placeholder="<?php echo $iniLang["USERS"]["FIRST_NAME"];?>"
                                       class="form-control"
                                       autofocus
                                       required>
                                <span class="help-block">Last Name, First Name, eg.: Smith, Harry</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName" class="col-xs-3 col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["USERS"]["LAST_NAME"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="lastName"
                                       name="lastName"
                                       placeholder="<?php echo $iniLang["USERS"]["LAST_NAME"];?>"
                                       class="form-control"
                                       autofocus>
                                <span class="help-block">Last Name, First Name, eg.: Smith, Harry</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-sm-3 col-md-3 col-lg-2 control-label">
                                <?php echo $iniLang["USERS"]["USERNAME"];?>
                            </label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="text"
                                       id="username"
                                       name="username"
                                       placeholder="<?php echo $iniLang["USERS"]["USERNAME"];?>"
                                       class="form-control"
                                       autofocus>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-3 col-md-3 col-lg-2 text-left control-label">Email</label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="email" id="email" name="email" placeholder="Email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-3 col-md-3 col-lg-2 control-label">Password</label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       placeholder="<?php echo $iniLang["USERS"]["PASSWORD"];?>"
                                       class="form-control"
                                       required
                                       autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-3 col-md-3 col-lg-2 control-label">Password</label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="password"
                                       id="passwordConf"
                                       name="passwordConf"
                                       placeholder="<?php echo $iniLang["FORMS"]["PASSWORD_CONFIRM"];?>"
                                       class="form-control"
                                       required
                                       autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="birthDate" class="col-sm-3 col-md-3 col-lg-2 control-label">Date of Birth</label>
                            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                                <input type="date" id="birthDate" name="birthDate" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>