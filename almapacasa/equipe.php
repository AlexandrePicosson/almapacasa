<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Accueil');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Accueil');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'patient'){
		$site = new page_base_securisee_patient('Accueil');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'personne_de_confiance'){
		$site = new page_base_securisee_personneC('Accueil');
	}
	else 
	{
		$site = new page_base('Equipe');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->returnPageEquipe();
	$site->affiche();
	
?>