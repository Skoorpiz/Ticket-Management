<?php
include '../includes/bdd.php';
if (isset($_POST['datum'])) {
    $datum = $_POST['datum'];
    // die(json_encode(['reponse' => $_POST['datum']]));
    $req = "SELECT DISTINCT year FROM ticket WHERE id_tag = $datum ORDER BY `ticket`.`year` ASC";
    $res = $pdo->query($req);
    $year = $res->fetchAll();
    // die(json_encode($customerDisplay));
    $retour = NULL;
    foreach ($year as $data) {
        $retour .= ' <option>' . $data['year'] . '</option>';
    }
    die($retour);
}
