<?php
session_start();

include_once('../class/autoload.php');

$errors         = array();  	
$data 			= array(); 		
$data['success']=false;



$tab=array();
$mypdo=new mypdo();


$tab['id']=$_POST['id'];
$tab['mdp']=$_POST['mdp'];


$resultat = $mypdo->connect($tab);

if(isset($resultat))
{
	$_SESSION['id']=$tab['id'];
	$stuff = $resultat['result']->fetch(PDO::FETCH_ASSOC);
	$_SESSION['type']= $resultat['type'];
	$_SESSION['nom']= $stuff['NOM'];
	$_SESSION['prenom']= $stuff['PRENOM'];
	$data['success']=true;
}
else
{
	$errors['message']="erreur";
}

if( ! empty($errors))
{
	$data['success'] = false;
	$data['errors'] = $errors;
}
else 
{
	if ($data['success'])
	{
		$data['message'] = "vous êtes connecté";
	}
}

echo json_encode($data);
?>