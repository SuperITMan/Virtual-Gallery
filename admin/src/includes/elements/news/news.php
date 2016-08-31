<?php
if (isset($_GET["c"])){
    switch(true){
        case strcasecmp(htmlspecialchars($_GET["c"]), "all") == 0:
            include $ini["path"]["news.all"];
            break;
        case strcasecmp(htmlspecialchars($_GET["c"]), "add") == 0:
            include $ini["path"]["news.add"];
            break;
        case strcasecmp(htmlspecialchars($_GET["c"]), "edit") == 0:
            include $ini["path"]["news.edit"];
            break;
        default:
            include $ini["path"]["news.all"];
            break;
    }
} else
    include $ini["path"]["news.all"];
?>