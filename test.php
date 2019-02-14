<?php
include('utils.php');


$rawData = readData("Ressources/data_arcs.txt");

$points = extractFromRaw($rawData);
$ways = extractFromRaw($rawData);

$graph = array();
for($i =0; $i< count($ways); $i++){
    for($j =0; $j< count($ways); $j++){
        //$graph[intval($i)][intval($j)] = -1;
    }
}

foreach($ways as $way){
    $ptA = $way[3];
    $ptB = $way[4];
    $cost = timeForWay($way[0], $ways, $points);
    $difficulty = $way[2];
    //$graph[intval($ptA)][intval($ptB)] =  array("cost"=>intval($cost), "difficulty"=>$difficulty);
    $graph[intval($ptA)][intval($ptB)] =  intval($cost);

}
//echo_pre($graph);
echo(json_encode($graph));
sort($graph);
//echo_pre($graph);
//echo(json_encode($graph));
//echo_pre($graph);

?>