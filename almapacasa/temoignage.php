<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Témoignage');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Témoignage');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'patient'){
		$site = new page_base_securisee_patient('Témoignage');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'personne_de_confiance'){
		$site = new page_base_securisee_personneC('Témoignage');
	}
	else 
	{
		$site = new page_base('Temoignage');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->afficheTemoignage();	
	$site->affiche();
?>