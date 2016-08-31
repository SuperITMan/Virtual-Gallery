<?php
session_start();
if (empty($_SESSION["token"])) {
    header("Location: login.php");
}

/* ------------------------------------------------------------------------------
   ------- Chargement des fichiers nécessaires au fonctionnement du site --------
   ------------------------------------------------------------------------------ */
$ini = parse_ini_file("../assets/config/conf.ini.php", true);
$iniMsg = parse_ini_file($ini['path']['confMsgFR'], true);
$iniLang = json_decode(file_get_contents("../assets/translations/fr.json"), true);
$sql = json_decode(file_get_contents("../assets/config/sqlRequests.json"), true);

include_once("../config/config.php");

include($ini["path"]["functions"]);
include($ini["path"]["functionsSQL"]);
include($ini["path"]["functions.html"]);

$db = connectDB(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD);

if (fetchSQLReq($db, $sql["user"]["select"]["firstUser"])) {
    header("Location: login.php");
}
require "../vendor/autoload.php";

$db = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Virtual Gallery CSS -->
    <link href="../dist/css/virtual-gallery-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- FONTAWESOME Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- FAVICON -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">


    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo str_replace("%titlesite%", SITE_TITLE, $iniLang["LOGIN"]["CONNECTING_TO"]);?></title>
</head>
<body>
<!-- jQuery -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<section id="connection">
    <div class="container">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br /><br />
                <h2><?php echo str_replace("%titlesite%", SITE_TITLE, $iniLang["LOGIN"]["CONNECTING_TO"]);?></h2>
                <br />
            </div>
        </div>
        <div class="row ">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><?php echo $iniLang["LOGIN"]["FILL_CONNECTION_DETAILS"];?></strong>
                    </div>
                    <div class="panel-body">
                        <!-- <form role="form">-->
                        <?php
                        if (isset($connectOK) && !$connectOK) :
                            displayErrorMessage($iniLang["LOGIN"]["ERROR_BAD_PWD_OR_USERNAME"]);
                        else :?>
                            <br />
                        <?php endif;?>

                        <form action="login.php" method="POST" name="connectionForm" id="connectionForm">
                            <div class="form-group input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-tag"></i>
                                        </span>
                                <input type="text"
                                       required=""
                                       autocapitalize="off"
                                       autocorrect="off"
                                       name="username"
                                       title="Nom d'utilisateur"
                                       class="form-control"
                                       id="username"
                                       value="<?php echo isset($_POST["username"]) ? $_POST["username"] : "";?>"
                                       placeholder="Nom d'utilisateur">
                            </div>

                            <div class="form-group input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                <input type="password"
                                       required=""
                                       autocapitalize="off"
                                       autocorrect="off"
                                       name="password"
                                       title="Mot de passe"
                                       class="form-control"
                                       id="password"
                                       placeholder="Mot de passe">
                            </div>

                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input name="rememberMe"
                                           value="remember"
                                           title="<?php echo $iniLang["LOGIN"]["REMEMBER_ME"];?>" type="checkbox">
                                    <?php echo $iniLang["LOGIN"]["REMEMBER_ME"];?>
                                </label>
                                <span class="pull-right">
                                            <a href="#"><?php echo $iniLang["LOGIN"]["LOST_PASSWORD"];?></a>
                                        </span>
                            </div>

                            <input type="submit" class="btn btn-primary " value="<?php echo $iniLang["LOGIN"]["LOGIN"]; ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap Core JavaScript -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
</body>
</html>

