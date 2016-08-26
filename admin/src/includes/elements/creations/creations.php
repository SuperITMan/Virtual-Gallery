<?php
if (isset($_GET["c"])){
    switch(true){
        case strcasecmp(htmlspecialchars($_GET["c"]), "all") == 0:
            include $ini["path"]["creations.all"];
            break;
        case strcasecmp(htmlspecialchars($_GET["c"]), "add") == 0:
            include $ini["path"]["creations.add"];
            break;
        default:
            include $ini["path"]["creations.all"];
            break;
    }
} else
    include $ini["path"]["creations.all"];
?>