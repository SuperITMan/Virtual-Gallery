<?php

function connectDB ($host="", $dbName="", $username="", $password="") {
    try
    {
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        return new PDO("mysql:host=$host;dbname=$dbName", $username, $password, $opt);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
        return false;
    }
}

function sendSQLReq ($db, $req, $params){
    if (count($params) > 0){
        if (substr_count($req, ":") == count($params)){
            $stmt = $db -> prepare($req);

            $keys = array_keys($params);
            for ($i=0; $i<count($params); $i++){
                $params[$keys[$i]] = stripslashes(htmlspecialchars($params[$keys[$i]]));
            }

            try {
                if ($stmt -> execute($params))
                    return true;
            }
            catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return false;
        }
        return false;
    }
    return false;
}

/**
 * Fetch a SQL request with PDO method.
 * @param  [type] $db       [description]
 * @param  [type] $req      SQL request
 * @param  [type] $params   Params like that : array(":element1"=>$element1, ":element2"=>$element2,...)
 * @param  [type] $isOneArg If you want only one returnment for your request, put true
 * @param  [type] $isOneRow If you want only one row for your request, put true
 * @return [type]           [description]
 */
function fetchSQLReq ($db, $req, $params=NULL, $isOneArg=false, $isOneRow=false){
    if ($params != NULL && count($params) > 0){
        if (substr_count($req, ":") == count($params)){
            $stmt = $db -> prepare($req);

            $keys = array_keys($params);
            for ($i=0; $i<count($params); $i++){
                $params[$keys[$i]] = stripslashes(htmlspecialchars($params[$keys[$i]]));
            }

            try {
                if ($stmt -> execute($params)) {
                    if ($result = $stmt -> fetchAll()){
                        if (isset($isOneArg)){
                            if ($isOneArg){
                                if (count($result) == 1){
                                    $keys = array_keys($result[0]);
                                    if (count($keys)){
                                        return $result[0][$keys[0]];
                                    }
                                    return false;
                                }
                                return false;
                            }
                        }
                        if (isset($isOneRow)){
                            if ($isOneRow)
                                return $result[0];
                        }
                        return $result;
                    }
                    return false;
                }
                return false;
            }
            catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return false;
        }
        return false;
    }
    if (strpos($req, ":") !== false)
        return false;

    $stmt = $db -> prepare($req);
    $stmt -> execute();
    return $stmt -> fetchAll();
}

?>