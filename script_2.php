<?php
    @session_start();
    date_default_timezone_set("Europe/Moscow");
    if (!isset($_SESSION["tablerow"])) $_SESSION["tablerow"] = array();
    $x = $_POST["x"];
    $y = $_POST["y"];
    $R = $_POST["R"];
    if ($x == null || $y == null || $R == null){exit;}

    if (!validation_x($x)){exit;}

    for ($i = 0; $i < count($y); $i++){
        if (!validation_y($y[$i])){continue;}
        for ($j = 0; $j < count($R); $j++){
            if (!validation_R($R[$j])){continue;}
            $result = "&#10060";

            //check if the point is in the area
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

    function validation_x($x){
        if (!is_numeric($x)){return 0;}
        $x = substr($x, 0, strpos($x, "."));
        if ($x <= -3 || $x >= 3){return 0;}
        return 1;
    }

    function validation_y($y){
        if (is_numeric($y) == 0){return 0;}

        //valid data for y
        $i = -5;
        while ($i < 4){
            if ($i == $y){break;}
            $i++;
        }
        if ($i > 3){return 0;}
        return 1;
    }

    function validation_R($R){
        if (is_numeric($R) == 0){return 0;}

        //valid data for R
        $i = 1;
        while ($i <= 3){
            if ($i == $R){break;}
            $i += 0.5;
        }
        if ($i > 3){return 0;}
        return 1;
    }

    // check if the point in the area
    function check_area($x, $y, $R){
        
        $res = "&#10060";
        
        //for circle
        if (floor($x) >= 0 && $y >= 0 && $x*$x + $y*$y <= $R*$R/4){
            $res = "&#9989";
        }
    
        //for rectangle
        if (floor($x) >= 0 && ceil($x) <= $R && $y <= 0 && $y >= -$R){
            $res = "&#9989";
        }
    
        //for triangle
        if (ceil($x) <= 0 && $y <= 0 && $x + $y >= -$R){
            $res = "&#9989";
        }
        
        return $res;
    }
?>