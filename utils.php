<?php

function main($start, $end){
    $starttime = microtime(true); //count start

    $rawData = readData("Ressources/data_arcs.txt");
    if($rawData == false){return "Error while reading data file<br>";}

    $points = ExtractFromRaw($rawData);
    if($points == false){return "Error fetching points<br>";}

    $ways = ExtractFromRaw($rawData);
    if($ways == false){return "Error fetching ways<br>";}

    $myways = tellWays($ways, $start, $end); //returning all ways(a way is list of path), the way is composed of numbers paths
    $mywaysAndStats = buildStatforWays($myways, $ways, $points);
    
    $stoptime = microtime(true);
    echo 'Time clapsed : '. ($stoptime - $starttime).' s<br>';
    echo_pre($mywaysAndStats);
    // the parameter 'maxlength of the way' is in the tellWays function
}

function main2($start, $end){
    $rawData = readData("Ressources/data_arcs.txt");
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
        $graph[$ptA][$ptB] = $cost; 
    }
    
    include("dijkstra.php");

    $mydisjkstra = new Dijkstra($graph);
    $res = $mydisjkstra->shortestPaths($start, $end);
    echo_pre($res);
    echo '<br\><br\><br\>';
    echo_pre($graph);
}

function tellWays($ways, $start, $end){//return a string of row, each row contains a list of way, each row is a possible path to go from 'start' to 'end'
    $maxlength = 10; //the maximum length of the way;

    $GLOBALS['ouput'] = array();
    find($ways, $start, $end, array(), 0, $maxlength);
    $temp = $GLOBALS['ouput'];
    unset($GLOBALS['ouput']);

    return $temp;

}

function find($ways, $start, $end, $queue, $stp, $maxlength){//function used by tellWays, return recursivly the possible paths to go from 'start' to 'end'
    if(count($queue)>$maxlength){
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
            $outp = array();
            foreach($queue as $item){
                array_push($outp, $item['nuWay']);
            }
            array_push($GLOBALS['ouput'], $outp);

        }
        //#############CONTINUE###############################
        else{
            foreach($ways as $w){
                
                if($stp ==0){$queue=array();}
                if(intval($w[3]) === intval($actualPoint)){
                    $go = true;
                    foreach($queue as $item){
                        if($item['nuWay']== intval($w[0]) ||  $ways[$item['nuWay']-1][4]==$end){
                            $go =false;
                        }
                    }
                    if($go){
                        //way never used -> push the queue with a new item
                        $indexQueue = count($queue);
                        $queue[$indexQueue]['difficulty'] = $w[2]; //useless here
                        $queue[$indexQueue]['nuWay'] = intval($w[0]);
                        find($ways, $start, $end, $queue, 1, $maxlength);
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

function ExtractFromRaw($rawData){//extract line of a file formated like 'data_arcs.txt'
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

function isFree($noWay, $ways){//return true if the way is free (B, R, N, SURF roads)
    $road = $ways[$noWay-1][2];
    $freeRoad = array('V','B', 'R', 'N', 'SURF', 'KL');
    return in_array($road, $freeRoad);
}
function isPathFree($nosWays, $ways){//return the fact that every ways of the path is free
    foreach($nosWays as $noway){
        if(!isFree($noway, $ways)){return 0;} //if only one way is not free then the path is not free
    }
    return 1;
}

function difficultyWay($nosWays, $ways){//return the highest difficulty of the way
    $diff = 0; //default difficulty is 'up' , it means the ways never go down
    foreach($nosWays as $noway){
        $d = $ways[$noway-1][2];
        if(in_array($d, array('TPH', 'TS', 'TK', 'BUS', 'TSD', 'TC'))){true;}
        elseif($d == 'V' && $diff < 2){ $diff=1;}
        elseif($d == 'B' && $diff <3 ){$diff=2;}
        elseif($d == 'R' && $diff <4 ){$diff=3;}
        elseif($d == 'N' && $diff <5 ){$diff=4;}
        elseif($d == 'KL' && $diff <6 ){$diff=5;}
        elseif($d == 'SURF' && $diff <7 ){$diff=6;}
    }
    return $diff;
}

function diffAltitude($noWay, $ways, $points){//return the difference of altitue between start en end of a way
    $pointA = $ways[$noWay-1][3];
    $pointB = $ways[$noWay-1][4];

    $altA = $points[$pointA-1][2];
    $altB = $points[$pointB-1][2];

    return abs($altA-$altB);
}

function timeForWay($noway, $ways, $points){
    $myway = $ways[$noway-1];
    $diffaltitude = diffAltitude($noway, $ways, $points);

    switch($myway[2]){
        case 'V':
            return ($diffaltitude/100) * 5;
        case 'B':
            return ($diffaltitude/100) * 4;
        case 'R':
            return ($diffaltitude/100) * 3;
        case 'N':
            return ($diffaltitude/100) * 2;
        case 'KL':
            return ($diffaltitude/100) * (10/60); //10 sec
        case 'SURF':
            return ($diffaltitude/100) * 3;
        case 'TPH':
            return ($diffaltitude/100) * 2 + 4;
        case 'TC':
            return ($diffaltitude/100) * 3 + 2;
        case 'TSD':
            return ($diffaltitude/100) * 3 + 1;
        case 'TS':
            return ($diffaltitude/100) * 4 + 1;
        case 'TK':
            return ($diffaltitude/100) * 4 + 1;
        case 'BUS':
            $startPoint = $myway[3];
            $stopPoint = $myway[4];

            $startName = $points[$startPoint-1][1]; //name of the point
            $stopName = $points[$stopPoint-1][1];

            if( ($startName == 'arc2000' && $stopName =='arc1600') || ($startName == 'arc1600' && $stopName =='arc2000')){
                return 40;
            }
            elseif( ($startName == 'arc1600' && $stopName =='arc1800') || ($startName == 'arc1800' && $stopName =='arc1600')){
                return 30;
            }
            else{
                echo "Error while finding BUS station with n° " . $noway . " transport : " . $myway[2]."<br>";
                return false;
            }
        default:
            echo "Error with n° " . $nosWay . " transport : " . $myway[2]."<br>";
            return 0;

    }

}
function timeForWays($nosWays, $ways, $points){
    $total = 0;
    foreach($nosWays as $noway){
        $total += timeForWay($noway, $ways, $points); 
    }
    return $total;
}

function buildStatforway($myway, $ways, $points){
    $free = isPathFree($myway, $ways);
    $difficulty = difficultyWay($myway, $ways);
    $totalTime = timeForWays($myway, $ways, $points);

    return array('free'=>$free, 'difficulty'=>$difficulty, 'totalMinuteTime'=>$totalTime);
}
function buildStatforWays($myways, $ways, $points){
    $results = array();
    foreach($myways as $myway){
        $stats = buildStatforway($myway, $ways, $points);
        array_push($results, array('ways'=>$myway, 'sats'=>$stats));
    }
    return $results;
}
?>