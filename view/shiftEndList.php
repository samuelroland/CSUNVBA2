<?php
ob_start();
$title = "CSU-NVB - Remise de garde";


?>

    <br>
    <table class="table table-bordered  table-striped" style="text-align: center">
        <thead class="thead-dark">
        <th>Date</th>
        <th>État</th>
        <th>Véhicule</th>
        <th>Résponsable</th>
        <th>Équipage</th>
        <th>Base</th>
        </thead>
        <tr>
            <td><?= $guardsheet['date'] ?></td>
            <td><?php if ($guardsheet['state'] == 'open') { ?>
                    <?= "Ouvert " ?>
                <?php } else { ?>
                    <?= "Fermé " ?>
                <?php } ?></td>
            <td>Jour : <?= $novaday['number'] ?><br>Nuit : <?= $novanight['number'] ?></td>
            <td>Jour : <?= $dayBoss['initials'] ?><br>Nuit : <?= $nightBoss['initials'] ?></td>
            <td>Jour : <?= $dayTeam['initials'] ?><br>Nuit : <?= $nightTeam['initials'] ?></td>
            <td><?= $baseinfo['name'] ?></td>
        </tr>
    </table>


<?= $baseinfo['name'] ?>

<?php
/*var_dump($guardsheetinfo);*/
foreach ($guardsections as $guardsection) { ?>
    <table class="table table-active table-bordered table-striped " style="text-align: center">
        <tr class="table-primary text-secondary ">
            <td class="font-weight-bold "><?= $guardsection['title']; ?></td>
            <td class="font-weight-bold">JOUR</td>
            <td class="font-weight-bold">NUIT</td>
            <td><span class="font-weight-bold">REMARQUE</span>(APPAREIL MANQUANT, ÉTAT DE CHARGE,
                DEFECTUOSITÉS)
            </td>
        </tr>
        <?php foreach ($guardlines as $guardline) {
            if ($guardsection['id'] == $guardline['guard_sections_id']) {
                ?>

                <tr>
                    <td><?= $guardline['text'] ?></td>
                    <td></td>
                    <td></td>
                    <td><textarea cols=100% rows="1"></textarea></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

    <?php /*    foreach ($guardsheets as $guardsheet) {
        if ($guardsheet['id'] == $sheetid) {
            ?>
            <h2><?=$guardsheet['date']?></h2>
            <br>

            <?php
        }
    } */
}
?>
    <a href="ExportPDF" class='btn btn-primary m-1 '>Format PDF</a>
<?php

$content = ob_get_clean();
require "gabarit.php";
?>