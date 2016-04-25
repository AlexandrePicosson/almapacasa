<?php
session_start();

include_once('../class/autoload.php');

$errors         = array();
$data 			= array();
$data['success']=false;

$tab=array();
$mypdo=new mypdo();
$tab1 = array();

$tab1["id_her"] = $_POST["id_her"];
$tab1["prenom"] = $_POST["prenom"];
$tab1["nom"] = $_POST["nom"];
$tab1["login"] = $_POST["login"];
$tab1["mdp"] = $_POST["mdp"];
$tab1["dateNaiss"] = $_POST["dateNaiss"];
$tab1["sexe"] = $_POST["sexe"];
$tab1["rue"] = $_POST["rue"];
$tab1["cp"] = $_POST["cp"];
$tab1["ville"] = $_POST["ville"];
$tab1["tel"] = $_POST["tel"];

$mypdo->AddPatientDB($tab1);

?>