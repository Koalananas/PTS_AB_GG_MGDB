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
        <link href = "../styles.css" rel = "stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="script.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class = "wrapper">
                <h1>We Ski<span class = "blue">.</span></h1>
                <nav>
                    <ul>
                        <li><a href = "#main-image">Accueil</a></li>
                        <li><a href = "#steps">Destinations</a></li>                        
                        <li><a href = "#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <form action="" method="get">
            start : 
            <input type="number" name="start" id="start">
            end : 
            <input type="number" name="end" id="end">
            <button type="submit">Ok</button>
            <br>Results : <br><br>
        </form>

        <?php
            include('utils.php');
            $s = 5;
            $e = 1;
            if(isset($_GET['start']) && isset($_GET['end'])){
                $s = $_GET['start'];
                $e = $_GET['end'];
            }

            brut_force($s,$e);

            echo '<br\><br\><br\>';

            dijkstra($s,$e);
        ?>

        <section id = "contact">
            <div class = "wrapper">
                <h3>Contactez-Nous</h3>
                <p>Chez We Ski nous savons que skier et profiter sont vos priorités.<br>N'hésitez pas à nous poser la moindre question par rapport à notre outils et nous proposer des évolutions</p>
                <form>
                    <label for= "name">Nom </label>
                    <input type ="text" id = "name" placeholder="Votre Nom">
                    <label for="email">Email </label>
                    <input type ="text" id = "email" placeholder="Votre email">
                    <input type = "submit" value = "OK" class = "button4">
                </form>
            </div>
        </section>
            
        <footer>
                <div class = "wrapper">
                    <h1>We Ski<span class = "blue">.</span></h1>
                    <div class = "copyright">Copyright © Tous droits réservés.   </div>
                </div>
        </footer>
        
    </body>
</html>
<!--
##########################################################################################################
*********************************************** CODE *****************************************************
##########################################################################################################
-->