<?php
session_start();
include_once ('class/autoload.php');
    $controleur = new ControleurAndroid();
    $mypdo = new mypdo();
    $file = 'test.txt';
    $data = $_POST['data'];
    $identifiant = $_POST['id'];
    $mdp = $_POST['mdp'];
    $result = $mypdo->loginAndroid($identifiant, $mdp);
    if($result && $result != null)
    {
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            $idInfirmiere = $row['id'];
        }
        $json = array();
        $json = json_decode($data, true);
        $lenght = count($json);
        $i = 0;
        while($i < $lenght)
        {
            $id = $json[$i]['id'];
            if(!isset($json[$i]['timestamp'])){
                $leRDVBDD = $mypdo->trouveRDV($id);
                if($leRDVBDD['dateModif'] < $json[$i]['timestamp']){
                    $tab = array();
                    $tab['id'] = $id;
                    $tab['heureD'] = $json[$i]['heureDebut'];
                    $tab['heureF'] = $json[$i]['heureFin'];
                    $verif = $mypdo->update_rdv_android();
                    if($verif)
                    {
                        $lenghtSoin = count($json[$i]['soinsRealise']);
                        if($lenghtSoin > 0)
                        {
                            $y = 0;
                            while($y < $lenghtSoin)
                            {
                                $soin = $json[$i]['soinsRealise'][$y];
                                $verif = $mypdo->update_soins_android($id, $soin);
                            }
                            $commentaire = $json[$i]['Commentaire'];
                            $mypdo->add_commentaire_android($id,$idInfirmiere,$commentaire);
                        }
                    }
                }
            }
        }
    }
    $jsonRep = array();
    $jsonRep[] = true;
    echo json_encode($jsonRep);