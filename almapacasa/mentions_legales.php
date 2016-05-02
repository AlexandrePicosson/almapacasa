<?php
session_start();
	include_once('class/autoload.php');
if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Mentions');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Mentions');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'patient'){
		$site = new page_base_securisee_patient('Mentions');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'personne_de_confiance'){
		$site = new page_base_securisee_personneC('Mentions');
	}
	else 
	{
		$site = new page_base('Mentions');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->mentionsLegales();
	$site->affiche();
	
?>