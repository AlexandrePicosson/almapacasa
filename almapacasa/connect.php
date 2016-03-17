<?php
include_once('class/autoload.php');
$site = new page_base('Connect');

$site->js='jquery.validate.min';
$site->js='messages_fr';
$site->js='jquery.tooltipster.min';
$site->css='tooltipster';
$site->css='modal';
$controleur=new controleur();
$site-> left_sidebar=$controleur->retourne_formulaire_login();

/* ajout d'un commentaire */
$site->affiche();
?>