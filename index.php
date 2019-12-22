<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Yassir Bouras">

    <title>Théorie des graphes</title>

    <!-- hada howa Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- les fonts dyal l page Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet" type="text/css">

    <!-- style dyale l page -->
    <link href="css/coming-soon.min.css" rel="stylesheet">
  </head>

  <body>
    <?php  
      $pdo = new PDO('mysql:host=localhost;dbname=ro;','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
      $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING) ;

      $pre3 = $pdo->prepare("select * from villes") ;
      $pre3->execute() ;
      $a3 =  $pre3->fetchAll(PDO::FETCH_OBJ) ;
      session_start() ;
    ?>
    
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop" style="transform: translateX(-35%) translateY(-50%);">
      <source src="video/video.mp4" type="video/mp4">
    </video>

    <div class="masthead">
      <div class="masthead-bg"></div>
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-12 my-auto">
            <div class="masthead-content text-white py-5 py-md-0">
              <h1 class="mb-3">Théorie des graphes</h1>
              <p class="mb-5">Vous pouvez savoir le plus cours chemin entre deux villes selon leurs positions géographiques en utilisant l'algorithme de Dijkstra.</p>
              <p>Vous pouvez selectionner deux villes dans la carte puis cliquer sur calculer pour voir le chemin des villes le plus cours.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="hadi" class="container-fluid" 
    style="position:absolute;top:0px;width:100%;<?= isset($_SESSION["phrase"])?"display:block;":"display: none;" ; ?>height:auto;background-color:grey;z-index:5555;"
    >
      <div class="row">
        <img src="img/imd.png" class="col-md-8">
        <div style="margin: auto;margin-top: 50px;width:100%;" class="container col-md-4">
          <h3>Choisissez les deux villes: </h3>
          <br>
          <form method="get" action="affichage_dijkstra.php">
            <div class="form-group">
              <select name="villedep" class="form-control" id="sel1">
                <option value="0">selectionner la ville de départ : </option>
                <?php foreach ($a3 as $k => $v) { ?>
                  <option value="<?= $v->nomville ; ?>"><?= $v->nomville ; ?></option>
                 <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <select name="villearr" class="form-control" id="sel2">
                <option value="0">selectionner la ville d'arriver : </option>
                <?php foreach ($a3 as $k => $v) { ?>
                  <option value="<?= $v->nomville ; ?>"><?= $v->nomville ; ?></option>
                 <?php } ?>
              </select>
            </div>
            <input type="submit" name="envoie" class="btn btn-success" value="Calculer">
          </form>
          <br>
          <div class="container-fluid">
            <p style="color: white;font-size:21px;">
              <?= isset($_SESSION["phrase"])?$_SESSION["phrase"]:"" ; unset($_SESSION["phrase"]) ;?>
            </p>
            <table class="table" style="background-color: #232020; color: white;">
              <?php if(isset($_SESSION["fd"])){ ?>
                <tr><th>Ville de départ</th><th>Distance</th><th>Ville d'arrivée</th></tr>
                <?php for($i = 0;$i<sizeof($_SESSION["fd"])-1;$i++){ 
                  $pre0 = $pdo->prepare("select * from ville where nville1 = '".trim($_SESSION["fd"][$i])."' and nville2 = '".trim($_SESSION["fd"][$i+1])."'") ;
                  $pre0->execute() ;
                  $a0 =  $pre0->fetchAll(PDO::FETCH_OBJ) ;
                  foreach($a0 as $k => $v){ ?>
                    <tr>
                      <td><?= $v->nville1 ; ?></td>
                      <td><?= $v->distance." km" ; ?></td>
                      <td><?= $v->nville2 ; ?></td>
                    </tr>
                  <?php }
                }
              } unset($_SESSION["fd"]) ; ?>
            </table>
          </div>
        </div>
      </div>
    </div>



    <div class="social-icons" style="z-index: 99999999;position:fixed;bottom:5px;right:5px;">
      <ul class="list-unstyled text-center mb-0">
        <li class="list-unstyled-item">
          <a href="#">
            <i>R</i>
          </a>
        </li>
      </ul>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/coming-soon.min.js"></script>

  </body>

</html>
<script type="text/javascript">
  var a = $("#hadi").height() ;
  $("#hadi img").height(a) ;
  $(".list-unstyled-item a").click(function(){
    $("#hadi").toggle(700) ;
    return false ;
  }) ;
</script>