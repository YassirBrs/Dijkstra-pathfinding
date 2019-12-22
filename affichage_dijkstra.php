<?php
include_once("dijkstra.php");
if(isset($_GET["villedep"]) and !empty($_GET["villedep"]) and isset($_GET["villearr"]) and !empty($_GET["villearr"]) and $_GET["envoie"] == "Calculer"){
	session_start() ;
	//on fait la connexion avec la base de données
	$pdo = new PDO('mysql:host=localhost;dbname=ro;','root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING) ;

	//selectionner les villes
	$pre1 = $pdo->prepare("select * from villes") ;
	$pre1->execute() ;
	$a =  $pre1->fetchAll(PDO::FETCH_OBJ) ;

	$pre2 = $pdo->prepare("select v1.idvilles as id1,v2.idvilles as id2,d.distance from villes v1, villes v2, distances d where v1.idvilles = d.villes_idvilles and v2.idvilles = d.villes_idvilles1") ;
	$pre2->execute() ;
	$b =  $pre2->fetchAll(PDO::FETCH_OBJ) ;

	$n = array();
	//saisir les information de chaque noeud (chaque ville)
	foreach ($a as $k => $v) {
		$n[$v->idvilles] = new Noeud($v->idvilles, $v->nomville);
	}
	$i=0 ;
	$tab_arc = array() ;
	//les informations de chaque arc
	foreach($b as $k => $v){
		$tab_arc[$i] = new Arc($n[$v->id1], $n[$v->id2], $v->distance) ;
		$i++ ;
	}


	$graphe = new Graphe($n, $tab_arc);
	$dij = new Dijkstra($graphe);
	$z =0 ;
	foreach($graphe->getTab_noeud() as $noeud_depart) {
		foreach($graphe->getTab_noeud() as $noeud_arrivee) {
			$rc = $dij->setDepart($noeud_depart);
			$rc = $dij->setArrivee($noeud_arrivee);
			
			if ($dij->recherche()) {
				if($dij->getDepart() == $_GET["villedep"] && $dij->getArrivee() == $_GET["villearr"]){
					$_SESSION["phrase"] = 
					"La distance la plus courte entre la ville <b><i style='color:black;'> " . $dij->getDepart() ."</i></b> et la ville <b><i style='color:black;'> " . $dij->getArrivee() . " </i></b> est <b><i style='color:black;'> " . $dij->getDistance_minimale() ."km</i></b> en passant par <b><i style='color:black;'> ".$dij->get_string_chemin()." </i></b>";
					$z++ ;
					$_SESSION["fd"] = explode(",",$dij->get_string_chemin()) ;
					echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
				}
			}
		}	
	}
	if($z == 0){
		$_SESSION["phrase"] = "Erreur ." ;
		echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
	}
	die() ;
}

?>