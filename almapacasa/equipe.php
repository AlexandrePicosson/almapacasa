<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Présentation de l\'équipe');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Présentation de l\'équipe');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'patient'){
		$site = new page_base_securisee_patient('Présentation de l\'équipe');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'personne_de_confiance'){
		$site = new page_base_securisee_personneC('Présentation de l\'équipe');
	}
	else 
	{
		$site = new page_base('Présentation de l\'équipe');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->returnPageEquipe();
	$site->affiche();
	
?>