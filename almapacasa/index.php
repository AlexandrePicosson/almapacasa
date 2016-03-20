<?php
	session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'admin'){
			$site = new page_base_securisee_admin('Accueil');
	}
	elseif (isset($_SESSION['id']) && isset($_SESSION['type']) && $_SESSION['type'] == 'infirmiere'){
			$site = new page_base_securisee_infirmiere('Accueil');
	}
	else 
	{
		$site = new page_base('Accueil');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->AfficheInfosAcc();
	$site->affiche();
?>
