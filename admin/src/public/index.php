<?php
session_start();
if (empty($_SESSION["token"])) {
    header("Location: login.php");
}
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

        <!-- DataTables CSS -->
        <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

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

        <!-- FileInput Fonts -->
<!--        <link href="../vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />-->

        <!-- Google Web Fonts -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

<!--        <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.-->
<!--            This must be loaded before fileinput.min.js -->
<!--        <script src="../vendor/kartik-v/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>-->
<!--        <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files.-->
<!--             This must be loaded before fileinput.min.js -->
<!--        <script src="../vendor/kartik-v/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>-->
<!--        <!-- the main fileinput plugin file -->
<!--        <script src="../vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js"></script>-->
<!---->
<!--        <!-- optionally if you need translation for your language then include-->
<!--            locale file as mentioned below -->
<!--        <script src="../vendor/kartik-v/bootstrap-fileinput/js/locales/fr.js"></script>-->

        <!-- FAVICON -->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">


        <meta name="description" content="">
        <meta name="author" content="">


        <?php
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
        require "../vendor/autoload.php";


        //$iniField = parse_ini_file($ini['path']['confFieldname'], true);
        //            session_start();

        /*if ((isset($_SESSION['language']) AND (strcmp(htmlspecialchars($_SESSION['language']),"FRA") == 0)) OR (isset($_COOKIE['language']) AND (strcmp(htmlspecialchars($_COOKIE['language']),"FRA") == 0))){
            $iniLang = parse_ini_file($ini['path']['confLangFR'], true);
            $iniMsg = parse_ini_file($ini['path']['confMsgFR'], true);
        }
        elseif ((isset($_SESSION['language']) AND (strcmp(htmlspecialchars($_SESSION['language']),"FRA") == 0)) OR (isset($_COOKIE['language']) AND (strcmp(htmlspecialchars($_COOKIE['language']),"FRA") == 0))){
            $iniLang = parse_ini_file($ini['path']['confLangFR'], true);
            $iniMsg = parse_ini_file($ini['path']['confMsgFR'], true);
        }
        else {
            $iniLang = parse_ini_file($ini['path']['confLangFR'], true);
            $iniMsg = parse_ini_file($ini['path']['confMsgFR'], true);
        }*/

        if($userInfos = verifyJWT(htmlspecialchars($_SESSION["token"]))) {
            if (!empty($userInfos->data->userId) && !empty($userInfos->data->username) && !empty($userInfos->data->displayedName)) {
                define("USER_USERID", htmlspecialchars($userInfos->data->userId));
                define("USER_USERNAME", htmlspecialchars($userInfos->data->username));
                define("USER_DISPLAYED_NAME", htmlspecialchars($userInfos->data->displayedName));
                define("USER_IS_ADMIN", empty(htmlspecialchars($userInfos->data->isAdmin))?false:htmlspecialchars($userInfos->data->isAdmin));
                define("USER_IS_SUPER_ADMIN", empty(htmlspecialchars($userInfos->data->isSuperAdmin))?false:htmlspecialchars($userInfos->data->isSuperAdmin));
            } else {
                session_destroy();
                define("USER_USERID", 0);
                define("USER_USERNAME", "unknown");
                define("USER_DISPLAYED_NAME", "Unknown");
                define("USER_IS_ADMIN", false);
                define("USER_IS_SUPER_ADMIN", false);
                displayErrorMessage($iniLang["ERROR_MESSAGES"]["INVALID_TOKEN_DISCONNECTED"]);
            }
        } else {
            session_destroy();
            define("USER_USERID", 0);
            define("USER_USERNAME", "unknown");
            define("USER_DISPLAYED_NAME", "Unknown");
            define("USER_IS_ADMIN", false);
            define("USER_IS_SUPER_ADMIN", false);
            displayErrorMessage($iniLang["ERROR_MESSAGES"]["INVALID_TOKEN_DISCONNECTED"]);
        }

        $db = connectDB(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD);



            /* ------------------------------------------------------------------------------ */
            ?>

        <title><?php echo SITE_TITLE;?></title>


    </head>

    <body>
        <!-- jQuery -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>

        <div id="wrapper">
        <?php include($ini["path"]["navigationBar"]);?>

        <?php
        if (isset($_GET["p"])) {
            switch(true){
                case strcasecmp(htmlspecialchars($_GET["p"]), "users") == 0:
                    include $ini["path"]["users"];
                    break;
                case strcasecmp(htmlspecialchars($_GET["p"]), "creations") == 0:
                    include $ini["path"]["creations"];
                    break;
            }
        } else
            include $ini["path"]["dashboard"];
        ?>
        </div>


<!--        --><?php
//        if (isset($_SESSION["userId"])) {
//            include($ini["path"]["site"]);
//        } else {
//            include($ini["path"]["login"]);
//        }

        /*if (isset($_SESSION['userId']) or (isset($_COOKIE['username']) AND isset($_COOKIE['session']))){
                if (sessionValidator($db)) :
                    include($ini["path"]["site"]);
                else :
                    include($ini["path"]["connection"]);
                endif;
            }
            else {
                include($ini["path"]["connection"]);
            }*/

            $db = null;
        ?>



        <!-- Bootstrap Core JavaScript -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <script src="../bower_components/raphael/raphael.min.js"></script>
        <script src="../bower_components/morrisjs/morris.min.js"></script>
<!--        <script src="../js/morris-data.js"></script>-->

        <!-- DataTables JavaScript -->
        <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
        <script src="../bower_components/datatables-responsive/js/dataTables.responsive.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../dist/js/sb-admin-2.js"></script>
    </body>
</html>