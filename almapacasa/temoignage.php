<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Temoignage');
	}
	else 
	{
		$site = new page_base('Temoignage');
	}
	$controleur = new controleur();
	$site->affiche();
?>