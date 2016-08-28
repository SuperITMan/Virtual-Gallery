<?php
if (isset($_GET["c"])){
    switch(true){
        case strcasecmp(htmlspecialchars($_GET["c"]), "all") == 0:
            include $ini["path"]["users.all"];
            break;
        case strcasecmp(htmlspecialchars($_GET["c"]), "add") == 0:
            include $ini["path"]["users.add"];
            break;
        case strcasecmp(htmlspecialchars($_GET["c"]), "me") == 0:
            include $ini["path"]["users.me"];
            break;
        default:
            include $ini["path"]["users.all"];
            break;
    }
} else
    include $ini["path"]["users.all"];
?>