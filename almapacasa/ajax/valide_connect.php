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


$resultat = $mypdo->connecte_toi($tab);

if(isset($resultat))
{
	$_SESSION['id']=$tab['id'];
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
		$data['message'] = "vous �tes connect�";
	}
}


/*if ( ! empty($errors)) {
	$data['success'] = false;
	$data['errors']  = $errors;
} else {
	if($data['success'])
	{
		$data['message'] = "erreur";
		
	}
}*/

echo json_encode($data);
?>