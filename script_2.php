<?php
    @session_start();
    date_default_timezone_set("Europe/Moscow");
    if (!isset($_SESSION["tablerow"])) $_SESSION["tablerow"] = array();
    $x = $_POST["x"];
    $y = $_POST["y"];
    $R = $_POST["R"];
    if ($x == null || $y == null || $R == null){exit;}

    for ($i = 0; $i < count($y); $i++){
        for ($j = 0; $j < count($R); $j++){
            $result = "&#10060";

            //validation of x
            $result = check_area($x, $y[$i], $R[$j]);

            $work_time = round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])*1000000, 1);
            $currentTime = date("H : i : s");

            array_push($_SESSION["tablerow"], "{ \"x\":\"$x\", \"y\": $y[$i], \"R\": $R[$j], \"result\": \"$result\",
             \"work_time\": $work_time, \"currentTime\": \"$currentTime\"}");
        }
    }


    header("Content-type: application/json; charset=utf-8");
    echo "[ ";
    for ($i = 0; $i < count($_SESSION['tablerow']) - 1; ++$i){
        echo $_SESSION['tablerow'][$i].', ';
    }
    echo $_SESSION['tablerow'][count($_SESSION['tablerow']) - 1];
    echo " ]";

    //@session_destroy();
    // check if the point in the area
    function check_area($x, $y, $R){
        
        $res = "&#10060";
        
        //for circle
        if (floor($x) >= 0 && $y >= 0 && ceil($x*$x + $y*$y) <= $R*$R/4){
            $res = "&#9989";
        }
    
        //for rectangle
        if (floor($x) >= 0 && ceil($x) <= $R && $y <= 0 && $y >= -$R){
            $res = "&#9989";
        }
    
        //for triangle
        if (ceil($x) <= 0 && $y <= 0 && floor($x + $y) >= -$R){
            $res = "&#9989";
        }
        
        return $res;
    }
?>