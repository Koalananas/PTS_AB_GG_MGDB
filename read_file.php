<!--
##########################################################################################################
********************************************* DISPLAY ****************************************************
##########################################################################################################
-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Path teller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
<form action="" method="get">
        start : 
        <input type="number" name="start" id="start">
        end : 
        <input type="number" name="end" id="end">
        <button type="submit">Ok</button>
        <br>Results : <br><br>
    </form>
</body>
</html>
<!--
##########################################################################################################
*********************************************** CODE *****************************************************
##########################################################################################################
-->
<?php
include('utils.php');
$s = 5;
$e = 1;
if(isset($_GET['start']) && isset($_GET['end'])){
    $s = $_GET['start'];
    $e = $_GET['end'];
}

#echo_pre(brut_force($s,$e));

echo_pre(dijkstra($s,$e));

echo_pre(FordFulkerson($s,$e));
?>