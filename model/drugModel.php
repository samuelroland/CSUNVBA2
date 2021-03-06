<?php
/*
  Author : Christopher Pardo
  File : drugModel.php fonctions du modèle pour les drugs
  Date : 05.03.2020
*/

//Retourne tous les items dans un tableau indexé de tableaux associatifs
function getAllDrugs()
{
    $badArray = json_decode(file_get_contents("model/dataStorage/drugs.json"), true); //Prend les éléments d'un fichier Json

    //Ajoute une id aux différantes parties du tableau
    foreach ($badArray as $p) {
        $goodArray[$p["id"]] = $p;
    }

    return $goodArray; //Retourne le tableau indexé avec ses id
}

/**
 * Retourne un item précis, identifié par son id
 * ...
 */

function getADrug($id)
{
    $items = getAllDrugs(); //Récupère les Drogues

    //Vérifie l'id choisi et retourne la valeur du tableau ou si non retourne "NULL"
    if (isset($items[$id])) {
        return $items[$id];
    } else {
        return null;
    }
}

/**
 * Sauve l'ensemble des items dans le fichier json
 * ...
 */

function saveDrugs($items)
{
    file_put_contents("model/dataStorage/drugs.json", json_encode($items)); //Écrit les éléments d'une variable dans un fichier Json
}

/**
 * Modifie un item précis
 * Le paramètre $item est un item complet (donc un tableau associatif)
 * ...
 */

function updateADrug($drugToUpdate)
{

    $items = getAllDrugs();

    $items[$drugToUpdate["id"]] = $drugToUpdate;

    saveDrugs($items);
}

/**
 * Ajoute un nouvel item
 * Le paramètre $item est un item complet (donc un tableau associatif), sauf que la valeur de son id n'est pas valable
 * puisque le modèle ne l'a pas encore traité
 * ...
 */
  function addADrug($newDrug)
  {
      $items = getAllDrugs();
      $test = 0;
      foreach ($items as $item){
          if ($item["id"] > $test){
            $test = $item["id"];
          }
      }

      $id = $test + 1;
      $items[] = [
          "id" => $id,
          "name" => $newDrug
      ];

      saveDrugs($items);
  }

  function delADrug($id)
  {
    $items = getAllDrugs();

    unset($items[$id]);

    saveDrugs($items);
  }



?>
