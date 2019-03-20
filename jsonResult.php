<?php
    include('Calcul/utils.php');
    header('Content-type: application/JSON; charset=ANSI');

    if(isset($_GET['start']) && isset($_GET['end']) && isset($_GET['methode'])){
        $s = $_GET['start'];
        $e = $_GET['end'];
        $m = $_GET['methode'];
    }
    else{
        echo '{"wrong input"}';
        die();
    }
    $r = ["V", "B", "R", "N", "KL", "SURF", "P"];
    if(isset($_GET['restriction']) ){
        $r = explode(",", $_GET['restriction']);
    }
    $res = null;
    switch($m){
        case "d":
            $res = dijkstra($s,$e, $r);
            $res["methode"] = "Dijkstra";
            break;
        case "f":
            $res = FordFulkerson($s,$e, $r);
            $res["methode"] = "FordFulkerson";
            break;
        default:
            $res = brut_force($s,$e, $r);
            $res["methode"] = "Brute Force";
            break;
    }
    $res["query"] = $_GET;
    echo json_encode($res,JSON_NUMERIC_CHECK);
    if(isset($_GET["debug"])){
        echo "<br><br>";
        echo_pre($res);
    }
?>