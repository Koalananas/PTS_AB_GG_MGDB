<?php
Class Graph{
    function __construct($graph){
        $this->graph = $graph;
        $this->ROW = count($graph);
    }
    function BFS($s, $t){ // $parent : array
        $visited = array();
        for($i = 0; $i< $this->ROW ;$i++){
            array_push($visited, FALSE); // or 0
        }

        $queue = array();
        array_push($queue, $s);
        $visited[$s] = TRUE; // or 1

        while(count($queue)>0){ //problem possible
            $u = array_pop($queue);

            foreach($this->graph[$u] as $ind => $val){
                //echo $ind . " " . $val . '<br>';
                if($visited[$ind] == FALSE && $val >0 ){
                    array_push($queue, $ind);
                    $visited[$ind] = TRUE;
                    $this->parent[$ind] = $u;
                }
            }
        }
        //echo_pre($queue);
        //echo_pre($this->parent);
        if($visited[$t]){
            return TRUE;
        }
        return FALSE;
    }
    function FordFulkerson($source, $sink){
        $results = array();

        $source -= 1;
        $sink -= 1;

        $this->parent = array();
        for($i = 0; $i< $this->ROW ;$i++){
            array_push($this->parent, -1); 
        }

        $max_flow = 0;

        while($this->BFS($source, $sink)){

            $preresult = array();
            $tmp = array();
            $path_flow = INF;
            $s = $sink;
            //echo("aprent ");
            //echo_pre($this->parent);
            while($s != $source){
                $path_flow = min($path_flow, $this->graph[$this->parent[$s]][$s]);
                $s = $this->parent[$s];
                array_push($tmp, $s+1);
            }
            $tmp = array_reverse($tmp);
            array_push($tmp, $sink+1);

            $preresult["points"] = $tmp;
            $preresult["flow"] = $path_flow;
            array_push($results, $preresult);

            $max_flow += $path_flow;

            $v = $sink;
            while($v != $source){
                $u = $this->parent[$v];
                $this->graph[$u][$v] -= $path_flow;
                $this->graph[$v][$u] += $path_flow;
                $v = $this->parent[$v];
            }
        }
        $results["maxFlow"]=$max_flow;
        return $results;
    }
}
?>