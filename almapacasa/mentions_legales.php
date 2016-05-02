<?php
session_start();
	include_once('class/autoload.php');
if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Mentions légales');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Mentions légales');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'patient'){
		$site = new page_base_securisee_patient('Mentions légales');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'personne_de_confiance'){
		$site = new page_base_securisee_personneC('Mentions légales');
	}
	else 
	{
		$site = new page_base('Mentions légales');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->mentionsLegales();
	$site->affiche();
	
?>