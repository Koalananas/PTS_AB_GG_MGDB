<?php
 /**
 *  Dijkstra's algorithm in PHP by zairwolf
 * 
 *  Demo: http://www.you4be.com/dijkstra_algorithm.php
 *
 *  Source: https://github.com/zairwolf/Algorithms/blob/master/dijkstra_algorithm.php
 *
 *  Author: Hai Zheng @ https://www.linkedin.com/in/zairwolf/
 *
 */

include('utils.php');   
$rawData = readData("Ressources/data_arcs.txt");
if($rawData == false){return "Error while reading data file<br>";}

$points = ExtractFromRaw($rawData);
if($points == false){return "Error fetching points<br>";}

$ways = ExtractFromRaw($rawData);
if($ways == false){return "Error fetching ways<br>";}

$_distArr = array();
foreach($ways as $way){
    $ptA = $way[3];
    $ptB = $way[4];
    $cost = timeForWay($way[0], $ways, $points);
    $_distArr[$ptA][$ptB] = doubleval($cost); 
}

echo_pre($_distArr);

//the start and the end
$a = 5;
$b = 2;

//initialize the array for storing
$S = array();//the nearest path with its parent and weight
$Q = array();//the left nodes without the nearest path
foreach(array_keys($_distArr) as $val) $Q[$val] = 99999;
$Q[$a] = 0;

if (!array_key_exists($b, $S)) {
    echo "Found no way.";
    return;
}
//start calculating
while(!empty($Q)){
    $min = array_search(min($Q), $Q);//the most min weight
    if($min == $b) break;
    foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
        $Q[$key] = $Q[$min] + $val;
        $S[$key] = array($min, $Q[$key]);
    }
    unset($Q[$min]);
}

//list the path
$path = array();
$pos = $b;
while($pos != $a){
    $path[] = $pos;
    $pos = $S[$pos][0];
}
$path[] = $a;
$path = array_reverse($path);

//print result
echo "<img src='http://www.you4be.com/dijkstra_algorithm.png'>";
echo "<br />From $a to $b";
echo "<br />The length is ".$S[$b][1];
echo "<br />Path is ".implode('->', $path);

