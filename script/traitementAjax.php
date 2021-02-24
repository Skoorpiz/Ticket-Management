<?php
include '../includes/bdd.php';
if (isset($_POST['datum'])) {
    $datum = $_POST['datum'];
    // die(json_encode(['reponse' => $_POST['datum']]));
    $req = "SELECT * FROM customer WHERE id_tag = $datum";
    $res = $pdo->query($req);
    $customerDisplay = $res->fetchAll();
    // die(json_encode($customerDisplay));
    $retour = NULL;
    foreach ($customerDisplay as $data) {
        $retour .= ' <option value="'. $data['id_customer'] .'">' . $data['name'] . '</option>';
    }
    die($retour);
}
