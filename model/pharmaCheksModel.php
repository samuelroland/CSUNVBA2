<?php
/*
  Author : Christopher Pardo
  File : pharmaChecksModel.php fonctions du modèle pour les pharmachecks
  Date : 05.03.2020
*/

function getAllCheks()
{
    $badArray = json_decode(file_get_contents("model/dataStorage/pharmachecks.json"), true); //Prend les éléments d'un fichier Json

    //Ajoute une id aux différantes parties du tableau
    foreach ($badArray as $p) {
        $goodArray[$p["id"]] = $p;
    }

    return $goodArray; //Retourne le tableau indexé avec ses id
}

function getAChek($date, $batch_id)
{
    $cheks = getAllCheks();

    foreach ($cheks as $chek) {
        if ($chek["date"] == $date) {
            if ($chek["batch_id"] == $batch_id)
                return $chek;
        }
    }
    return null;
}

function getAllChecksByASheetId($id)
{
    $checks = getAllCheks();
    $listofchecks = null;   //liste de checks venant d'une feuille $id
    foreach ($checks as $check) {
        if ($check["stupsheet_id"] == $id) {
            $listofchecks[] = $check;   //on enregistre au bout de la liste
        }
    }
    return $listofchecks;
}

function saveCheks($items)
{
    file_put_contents("model/dataStorage/pharmachecks.json", json_encode($items)); //Écrit les éléments d'une variable dans un fichier Json
}

function addAChek($chek)
{
    $items = getAllCheks();
    $test = 0;
    foreach ($items as $item) {
        if ($item["id"] > $test) {
            $test = $item["id"];
        }
    }

    $id = $test + 1;
    $chek = array_merge(["id" => $id], $chek);
    $items[] = $chek;

    saveCheks($items);
}

function updateAChek($chekToUpdate)
{
    $items = getAllCheks();

    foreach ($items as $item) {
        if ($item["id"] == $chekToUpdate["id"]) {
            $item = array_merge($item, $chekToUpdate);
            $items[$item["id"]] = $item;
        }
    }

    saveCheks($items);
}

function delAChek($id)
{
    $items = getAllCheks();

    unset($items[$id]);

    saveCheks($items);
}

?>