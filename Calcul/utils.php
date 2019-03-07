<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE); // | E_NOTICE

error_reporting(E_ERROR | E_WARNING | E_PARSE);

function brut_force($start, $end){
    $startTime = microtime(true); //count start

    $rawData = readData("../Ressources/data_arcs.txt");
    if($rawData == false){return "Error while reading data file<br>";}

    $points = extractFromRaw($rawData);
    if($points == false){return "Error fetching points<br>";}

    $ways = extractFromRaw($rawData);
    if($ways == false){return "Error fetching ways<br>";}

    $myWays = tellWays($ways, $start, $end); //returning all ways(a way is list of path), the way is composed of numbers paths
    $myWaysAndStats = buildStatforWays($myWays, $ways, $points);

    $stopTime = microtime(true);
    echo 'Time clapsed : '. ($stopTime - $startTime).' s<br>';
    echo_pre($myWaysAndStats);
    // the parameter 'maxlength of the way' is in the tellWays function
}

function dijkstra($start, $end)
{
    require("dijkstra.php");
    $rawData = readData("../Ressources/data_arcs.txt");
    if($rawData == false){return "Error while reading data file<br>";}

    $points = ExtractFromRaw($rawData);
    if($points == false){return "Error fetching points<br>";}

    $ways = ExtractFromRaw($rawData);
    if($ways == false){return "Error fetching ways<br>";}

    $graph = array();
    foreach($ways as $way){
        $ptA = $way[3];
        $ptB = $way[4];
        $cost = timeForWay($way[0], $ways, $points);
        $graph[intval($ptA)][intval($ptB)] = intval($cost); 
    }


    $mydisjkstra = new Dijkstra($graph);
    $res = $mydisjkstra->shortestPaths($start, $end)[0];
    $sol = array();
    for($i =0; $i<count($res)-1; $i++){
        $s = $res[$i];
        $e = $res[$i+1];

        $lowercost = -1;
        $w = -1;
        foreach($ways as $way){
            if(intval($s) == intval($way[3]) && intval($e) == intval($way[4]) && ($lowercost>timeForWay($way[0], $ways, $points) || $lowercost == -1)){
                $lowercost = timeForWay($way[0], $ways, $points);
                $w = $way[0];
            }
        }
        array_push($sol, $w);
    }

    print_r($sol);
    $stopTime = microtime(true);
    echo 'Time clapsed : '. ($stopTime - $startTime).' s<br>';
}

function tellWays($ways, $start, $end){//return a string of row, each row contains a list of way, each row is a possible path to go from 'start' to 'end'
    $maxLength = 10; //the maximum length of the way;

    $GLOBALS['ouput'] = array();
    findWays($ways, $start, $end, array(), 0, $maxLength);
    $temp = $GLOBALS['ouput'];
    unset($GLOBALS['ouput']);

    return $temp;

}

function findWays($ways, $start, $end, $queue, $step, $maxLength){//function used by tellWays, return recursivly the possible paths to go from 'start' to 'end'
    if(count($queue)>$maxLength){
        //echo 'queue to long';
        $queue =array();
    }
    else{
        if(count($queue)!=0){
            $actualPoint = $ways[$queue[count($queue)-1]['nuWay']-1][4];
        }
        else{
            $actualPoint = $start;
        }
        $start = intval($start);
        $end = intval($end);
        $actualPoint  =intval($actualPoint);
        //###################BREAK#############################
        if($actualPoint == $end){ //check if the number of way of the last element of the queue gives to the end
            //###################CLEAN QUEUE#############################
            $continue = true;
            /*
                2,3,29,37,32,69,54,11,17,93 (wrong) -> 2,37,69,54,17,93 (right)
            */
            for($i = 0; $i < count($queue); $i++){
                if($i+1<count($queue)){
                    $now1 =$queue[$i]['nuWay']-1;
                    $now2 = $queue[$i+1]['nuWay']-1;
                    if(intval($ways[$now1][3]) == intval($ways[$now2][3])){
                        array_splice($queue, $i, 1);
                        $i = 0;
                    }
                }
            }
            $output = array();
            foreach($queue as $item){
                array_push($output, $item['nuWay']);
            }
            array_push($GLOBALS['ouput'], $output);

        }
        //#############CONTINUE###############################
        else{
            foreach($ways as $way){

                if($step ==0){$queue=array();}
                if(intval($way[3]) === intval($actualPoint)){
                    $go = true;
                    foreach($queue as $item){
                        if($item['nuWay']== intval($way[0]) ||  $ways[$item['nuWay']-1][4]==$end){
                            $go =false;
                        }
                    }
                    if($go){
                        //way never used -> push the queue with a new item
                        $indexQueue = count($queue);
                        $queue[$indexQueue]['difficulty'] = $way[2]; //useless here
                        $queue[$indexQueue]['nuWay'] = intval($way[0]);
                        findWays($ways, $start, $end, $queue, 1, $maxLength);
                    }

                }
            }
        }
    }
}

function readData($path){//return a file from the path
    if(file_exists($path)){
        return fopen($path, "r");
    }
    return false;
}

function extractFromRaw($rawData){//extract line of a file formated like 'data_arcs.txt'
    $points = array();
    $nbPoints = fgets($rawData);
    for($i = 0; $i< $nbPoints; $i++){
        $line = fgets($rawData);
        $infoLine = explode ("\t",$line);
        if(count($infoLine) == 3){
            array_push($points, array($infoLine[0], $infoLine[1], $infoLine[2]));
        }
        if(count($infoLine) == 5){
            array_push($points, array($infoLine[0], $infoLine[1], $infoLine[2], $infoLine[3], $infoLine[4]));
        }
        else {return false;}

    }
    return $points;
}

function echo_pre($arr){//print an array for html page
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function isFree($numberWay, $ways){//return true if the way is free (B, R, N, SURF roads)
    $road = $ways[$numberWay-1][2];
    $freeRoad = array('V','B', 'R', 'N', 'SURF', 'KL');
    return in_array($road, $freeRoad);
}
function isPathFree($numbersWays, $ways){//return the fact that every ways of the path is free
    foreach($numbersWays as $numberWay){
        if(!isFree($numberWay, $ways)){return 0;} //if only one way is not free then the path is not free
    }
    return 1;
}

function difficultyWay($numbersWays, $ways){//return the highest difficulty of the way
    $difficulty = 0; //default difficulty is 'up' , it means the ways never go down
    foreach($numbersWays as $numberWay){
        $typeSlope= $ways[$numberWay-1][2];
        if(in_array($d, array('TPH', 'TS', 'TK', 'BUS', 'TSD', 'TC'))){true;}
        elseif($typeSlope== 'V' && $difficulty < 2){ $difficulty=1;}
        elseif($typeSlope== 'B' && $difficulty <3 ){$difficulty=2;}
        elseif($typeSlope== 'R' && $difficulty <4 ){$difficulty=3;}
        elseif($typeSlope== 'N' && $difficulty <5 ){$difficulty=4;}
        elseif($typeSlope== 'KL' && $difficulty <6 ){$difficulty=5;}
        elseif($typeSlope== 'SURF' && $difficulty <7 ){$difficulty=6;}
    }
    return $difficulty;
}

function diffAltitude($numberWay, $ways, $points){//return the difference of altitue between start en end of a way
    $pointA = $ways[$numberWay-1][3];
    $pointB = $ways[$numberWay-1][4];

    $altitudeA = $points[$pointA-1][2];
    $altitudeB = $points[$pointB-1][2];

    return abs($altitudeA-$altitudeB);
}

function timeForWay($numberWay, $ways, $points){
    $myWay = $ways[$numberWay-1];
    $denivele = diffAltitude($numberWay, $ways, $points);

    switch($myWay[2]){
        case 'V':
            return ($denivele/100) * 5;
        case 'B':
            return ($denivele/100) * 4;
        case 'R':
            return ($denivele/100) * 3;
        case 'N':
            return ($denivele/100) * 2;
        case 'KL':
            return ($denivele/100) * (10/60); //10 sec
        case 'SURF':
            return ($denivele/100) * 3;
        case 'TPH':
            return ($denivele/100) * 2 + 4;
        case 'TC':
            return ($denivele/100) * 3 + 2;
        case 'TSD':
            return ($denivele/100) * 3 + 1;
        case 'TS':
            return ($denivele/100) * 4 + 1;
        case 'TK':
            return ($denivele/100) * 4 + 1;
        case 'BUS':
            $startPoint = $myWay[3];
            $stopPoint = $myWay[4];

            $startName = $points[$startPoint-1][1]; //name of the point
            $stopName = $points[$stopPoint-1][1];

            if( ($startName == 'arc2000' && $stopName =='arc1600') || ($startName == 'arc1600' && $stopName =='arc2000')){
                return 40;
            }
            elseif( ($startName == 'arc1600' && $stopName =='arc1800') || ($startName == 'arc1800' && $stopName =='arc1600')){
                return 30;
            }
            else{
                echo "Error while findWaysing BUS station with n° " . $numberWay . " transport : " . $myWay[2]."<br>";
                return false;
            }
        default:
            echo "Error with n° " . $numberWay . " transport : " . $myWay[2]."<br>";
            return 0;

    }

}
function timeForWays($numbersWays, $ways, $points){
    $total = 0;
    foreach($numbersWays as $numberWay){
        $total += timeForWay($numberWay, $ways, $points);
    }
    return $total;
}

function buildStatforway($myWay, $ways, $points){
    $free = isPathFree($myWay, $ways);
    $difficulty = difficultyWay($myWay, $ways);
    $totalTime = timeForWays($myWay, $ways, $points);

    return array('free'=>$free, 'difficulty'=>$difficulty, 'totalMinuteTime'=>$totalTime);
}

function buildStatforWays($myWays, $ways, $points){
    $results = array();
    foreach($myWays as $myWay){
        $stats = buildStatforway($myWay, $ways, $points);
        array_push($results, array('ways'=>$myWay, 'stats'=>$stats));
    }
    return $results;
}
?>