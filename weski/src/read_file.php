<!--
##########################################################################################################
********************************************* DISPLAY ****************************************************
##########################################################################################################
-->
<!DOCTYPE html>
<html>
    <head>
    <title>WeSki</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
        
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
  </head>

  <body data-spy="scroll" data-target=".navbar" data-offset="60">

      
    <nav class="navbar navbar-expand-md navbar-dark sticky-top">
        <a class="navbar-brand" href="#"></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar">
                <i class="fas fa-bars fa-lg"></i>
        </button>
        
        <div class="collapse navbar-collapse justify-content-center" id="myNavbar">
            <ul class="nav nav-pills navbar-nav">
                 <li class="nav-item"><a class="nav-link" href="..\Index.html">Accueil</a></li>   
                <li class="nav-item"><a class="nav-link" href="#saisie">Saisie</a></li>                
                <li class="nav-item"><a class="nav-link" href="#Trajets">Trajets</a></li>
                <li class="nav-item"><a class="nav-link" href="#portfolio">Nous retrouver</a></li>
                <li class="nav-item"><a class="nav-link" href="#recommandations">Avis utilisateurs</a></li>          
                <li class="nav-item"><a class="nav-link" href="#Contact">Contact</a></li>     
            </ul>
        </div>
    </nav>
    
    
        <section id = "saisie" class = "container">
          <div class="row justify-content-center">
            <form class ="form-inline" action = "" method="get">
          
            <div class="form-group col-auto">             
              <label for="start" class = "col-form-label">Départ :&nbsp;&nbsp;&nbsp;<br></label>
              <input type="number" name = "start" id = "start" class="form-control" placeholder="Start">
            </div>
            <div class="form-group  col-auto">
              <label for="end" class = "col-form-label">Arrivée :&nbsp;&nbsp;&nbsp;</label>
              <input type="number" name="end" id="end" class="form-control" placeholder="End">            
            </div>  
            <div class="form-group  col-auto">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
              </form>
          </div>

            <div id="root"></div>
        
        </section>
        
      
       <br>Results : <br><br>
        <section id ="Trajets">
                <?php
                    include('./../../Calcul/utils.php');

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
                    echo json_encode($res);
                    if(isset($_GET["debug"])){
                        echo "<br><br>";
                        echo_pre($res);
                    }
                ?>

        </section>  
            
          
         <section id="portfolio">
            <div class="container">
                <div class="white-divider"></div>
                <div class="heading">
                    <h2>Nous retrouver :</h2>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <a href="http://www.facebook.com" target="_blank">
                            <img class="img-thumbnail" src="images/facebook_share.png" alt="facebook share">
                        </a>
                    </div>
                     <div class="col-md-4">
                        <a href="http://www.google.com" target="_blank">
                            <img class="img-thumbnail" src="images/google_translate.png" alt="google translate">
                        </a>
                    </div>
                     <div class="col-md-4">
                        <a href="http://www.twitter.com" target="_blank">
                            <img class="img-thumbnail" src="images/twitter_video.png" alt="twitter video">
                        </a>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-4">
                        <a href="http://www.google.com" target="_blank">
                            <img class="img-thumbnail" 
                           src="images/youtube.png" alt="youtube">
                        </a>
                    </div>
                     <div class="col-md-4">
                        <a href="http://www.twitter.com" target="_blank">
                            <img class="img-thumbnail" src="images//twitter_retweet.png" alt="twitter retweet">
                        </a>
                    </div>
                     <div class="col-md-4">
                        <a href="http://www.facebook.com" target="_blank">
                            <img class="img-thumbnail" src="images/facebook_video.png" alt="facebook video">
                        </a>
                    </div>
                </div>
            
            </div>
            
        </section>
      
      
       <section id="recommandations">
            <div class="container">
                <div class="red-divider"></div>
                <div class="heading">
                    <h2>Recommandations</h2>
                </div>
                <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
                    <ol class="carousel-indicators">
                         <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                         <li data-target="#myCarousel" data-slide-to="1"></li>
                         <li data-target="#myCarousel" data-slide-to="2"></li>  
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <h3>"Super site, vraiment génial !"</h3>
                            <h4>Candide thovex</h4>       
                        </div>
                          <div class="carousel-item">
                            <h3>"Je trouve mon chemin beaucoup plus <br>facilement grace à WeSki !"</h3>
                            <h4>Le Yéti</h4>       
                        </div>
                          <div class="carousel-item">
                            <h3>"J'ai l'impression de redécouvrir ma station<br> sans skieur "</h3>
                            <h4>Un utilisateur anonyme surpayé</h4>       
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev" role="button">
                        <span class="fas fa-chevron-left fa-2x"></span>
                    </a>
                    <a class="carousel-control-next" href="#myCarousel" data-slide="next" role="button">
                        <span class="fas fa-chevron-right fa-2x"></span>
                    </a>
                
                </div>
            
            </div>
        
                </section>  
        

        
       
   <section id ="Contact">
       <footer  class="text-center">
            <a href="#about">                    
                <span class="fas fa-chevron-up"></span>
            </a>
            <h3>Contactez-Nous</h3>
                <p>Chez We Ski nous savons que skier et profiter sont vos priorités.<br>N'hésitez pas à nous poser la moindre question par rapport à notre outils et nous proposer des évolutions</p>
                
         <form id ="form_1">
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">    
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Nom</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Nom">
              </div>
             <div class="form-group">
                    <label for="exampleFormControlTextarea1">Votre avis</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
             </div>
              <div id = "div_footer" class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
              </div>
            <br>
              <button type="submit" class="btn btn-primary">Submit</button>
             
              
             </div>
            
       </form>
       <br>

            <h5>© WE-SKI.COM</h5>
        </footer
       </section>
    </body>
</html>
<!--
##########################################################################################################
*********************************************** CODE *****************************************************
##########################################################################################################
-->