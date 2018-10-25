<?php
main('5','2'); //start point, end point

function main($start, $end){
    $rawData = readData("Ressources/data_arcs.txt");
    if($rawData == false){return "Error while reading data file";}

    $points = ExtractFromRaw($rawData);
    if($points == false){return "Error fetching points";}

    $ways = ExtractFromRaw($rawData);
    if($ways == false){return "Error fetching ways";}

    echo tellWays($ways, $start, $end); //returning all ways(a way is list of path) separated by line-return, the way is composed of numbers paths separated by comma
    // the parameter 'maxlength of the way' is in the tellWays function
}

function tellWays($ways, $start, $end){
    $maxlength = 15; //the maximum length of the way;

    $GLOBALS['ouput'] = "";
    find($ways, $start, $end, array(), 0, $maxlength);
    $temp = $GLOBALS['ouput'];
    unset($GLOBALS['ouput']);

    return $temp;

}

function find($ways, $start, $end, $queue, $stp, $maxlength){
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
                2,3,29,37,32,69,54,11,17,93 (faux) -> 2,37,69,54,17,93 (vrai)
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
            $GLOBALS['ouput'] .= implode(",",$outp ). "<br>";

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

function readData($path){
    if(file_exists($path)){
        return fopen($path, "r");
    }
    return false;
}

function ExtractFromRaw($rawData){
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

function echo_pre($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
?>