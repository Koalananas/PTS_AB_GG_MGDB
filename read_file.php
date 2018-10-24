<?php

treeFromStartEnd('5','2');

function treeFromStartEnd($start, $end){
    $rawData = readData("Ressources/data_arcs.txt");
    if($rawData == false){return "Error while reading data file";}

    $points = ExtractFromRaw($rawData);
    if($points == false){return "Error fetching points";}

    $ways = ExtractFromRaw($rawData);
    if($ways == false){return "Error fetching ways";}

    find($ways, $start, $end, array());

}

function find($ways, $start, $end, $queue){
    if(count($queue)>12){
        //echo 'arrete les conneries';
    }
    else{
        if(count($queue)>0){
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
            //parcourir la queue et la remplir dans le node
            //re find dans les child tdu node
            foreach($queue as $item){
                echo $item['nuWay'] . " > ";
            }
            echo "<br>";
            $clean =true;
            //return;
        }
        //#############CONTINUE###############################
        else{
            foreach($ways as $w){
                
                //print_r($w);echo '<br>';
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

                        find($ways, $start, $end, $queue);
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