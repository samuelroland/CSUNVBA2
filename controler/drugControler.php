<?php
/*
  Author : Samuel Roland
  File : drugControler.php fonction du contrôleur pour les drugs
  Date : 04.02.2020
*/

require_once 'model/drugModel.php';
require_once 'model/basesModel.php';
require_once 'model/novasModel.php';
require_once 'model/stupSheetModel.php';
require_once 'model/batchesModel.php';
require_once 'model/pharmaCheksModel.php';
require_once 'model/logsModel.php';
require_once 'model/novaChecksModel.php';
require "model/restocksModel.php";

function drugdetails($sheetid)  //détails d'une feuille de stups
{
    $stupsheet = getASheetById($sheetid);   //prendre la feuille de stups demandée
    $sheetid = $stupsheet['id'];
    $numweek = substr($stupsheet['week'], 2);    //extraire le numéro de la semaine uniquement.
    $year = substr($stupsheet['week'], 0, 2) + 2000;    //extraire l'année

    $datesoftheweek = getDatesOfAWeekBySheetId($sheetid);
    $drugs = getAllDrugs();
    $batches = getAllBatches();

    $baseinfo = getABase($stupsheet['base_id']);
    $listofchecks = getAllChecksByASheetId($sheetid);

    $novaChecks = getAllNovaChecksByASheetId($sheetid);
    $novas = $stupsheet["novas"];
    unset($stupsheet["novas"]);

    if (isset($novaChecks)) {
        foreach ($drugs as $drug) {
            foreach ($novas as $nova) {
                foreach ($datesoftheweek as $dayindex => $day) {
                    foreach ($novaChecks as $novaCheck) {
                        if ($novaCheck["drug_id"] == $drug['id']) {
                            if ($novaCheck["nova_id"] == $nova['nova_id']) {
                                if ($novaCheck["date"] == date("Y-m-d", $day)) {
                                    $stupsheet['novas'][$drug["name"]][$nova["nova"]][date("Y-m-d", $day)]["start"] = $novaCheck["start"];
                                    $stupsheet['novas'][$drug["name"]][$nova["nova"]][date("Y-m-d", $day)]["end"] = $novaCheck["end"];
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //Grand tableau de restocks trié par date, nova id et batch id:
    $bigSheetOfRestocks = getBigSheetOfRestocks($sheetid);

    require 'view/drugsDetails.php';
}

function logs($sheetid)
{
    $logs = getLogsByItemId($sheetid);
    require 'view/logs.php';
}

function drugHomePage() //page d'accueil du choix des feuilles de stups
{
    $bases = getAllBases();
    $stupsheets = getAllSheets();
    require_once 'view/drugsHome.php';
}

//recevoir les dates d'une semaine avec le numéro de la semaine.
function getDatesOfAWeek($weeknb, $year)
{
    //Tests de tous les jours de l'année demandée jusqu'à trouver la date du premier jour de la semaine demandée.
    $datetest = strtotime("$year-01-01");    //on part du 1 janvier de l'année donnée pour la date de test.
    $dateinrun = null;
    if (empty($weeknb) == false) {  //ne pas executer si la semaine n'est pas donnée, sinon boucle infinie !
        while (empty($dateinrun) == true) {
            if (date("W", $datetest) == $weeknb) {  //si la semaine de cette date est la semaine recherchée donc $weeknb
                $dateinrun = $datetest; //on enregistre cette date
                break;  //on sort du while pour arrêter le processus de recherche.
            } else {
                $datetest = strtotime("+1 day", $datetest); //sinon on teste avec le jour suivant.
            }
        }
    }

    //Enregistrer les valeurs dans un tableau avec les numéros des jours comme index
    for ($j = 1; $j <= 7; $j++) {
        $datesoftheweek[$j] = $dateinrun;   //jour de 1 à 7.

        $dateinrun = strtotime("+1 day", $dateinrun);   //Avancer d'un jour pour avoir la date du jour d'après
    }

    return $datesoftheweek;
}

function getDatesOfAWeekBySheetId($sheetid)
{
    $thesheet = getASheetById($sheetid);

    $year = substr($thesheet['week'], 0, 2) + 2000;
    $weeknb = substr($thesheet['week'], 2);
    //Tests de tous les jours de l'année demandée jusqu'à trouver la date du premier jour de la semaine demandée.
    $datetest = strtotime("$year-01-01");    //on part du 1 janvier de l'année donnée pour la date de test.
    $dateinrun = null;
    if (empty($weeknb) == false) {  //ne pas executer si la semaine n'est pas donnée, sinon boucle infinie !
        while (empty($dateinrun) == true) {
            if (date("W", $datetest) == $weeknb) {  //si la semaine de cette date est la semaine recherchée donc $weeknb
                $dateinrun = $datetest; //on enregistre cette date
                break;  //on sort du while pour arrêter le processus de recherche.
            } else {
                $datetest = strtotime("+1 day", $datetest); //sinon on teste avec le jour suivant.
            }
        }
    }

    //Enregistrer les valeurs dans un tableau avec les numéros des jours comme index
    for ($j = 1; $j <= 7; $j++) {
        $datesoftheweek[$j] = $dateinrun;   //jour de 1 à 7.

        $dateinrun = strtotime("+1 day", $dateinrun);   //Avancer d'un jour pour avoir la date du jour d'après
    }

    return $datesoftheweek;
}

function updatePharmaCheckPage($batch_id, $stupsheet_id, $date)
{
    $batch = getABatcheById($batch_id);
    $stupsheet = getASheetById($stupsheet_id);
    $check = getAChek($date, $batch_id);
    require_once "view/updatePharmaCheck.php";
}

function changePharmaCheck($batch_id, $stupsheet_id, $date, $start, $end)
{
    $check = getAChek($date, $batch_id);
    $newCheck = [
        "date" => $date,
        "start" => $start,
        "end" => $end,
        "batch_id" => $batch_id,
        "user_id" => $_SESSION["user"][0],
        "stupsheet_id" => $stupsheet_id
    ];

    if ($check == null) {
        addAChek($newCheck);
    } else {
        $newCheck["id"] = $check["id"];
        updateAChek($newCheck);
    }

    drugdetails($stupsheet_id);
}

?>
