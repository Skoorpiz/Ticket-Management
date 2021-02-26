<?php
include_once '../includes/bdd.php';
$customer = $_POST['customer'];
$tag = $_POST['tag'];
$tag = $pdo->quote($tag);
$req = "INSERT INTO tag (name) VALUES ($tag)";
$res = $pdo->query($req);
$req = "SELECT id_tag FROM tag WHERE name = $tag";
$res = $pdo->query($req);
$idTag = $res->fetchColumn();
for ($i = 0; $i < count($customer); $i++) {
    $idCustomer = $customer[$i];
    $req = "UPDATE customer set customer.id_tag = $idTag WHERE id_customer = $idCustomer;";
    $res = $pdo->query($req);
    $req = "UPDATE ticket set ticket.id_tag = $idTag WHERE id_customer = $idCustomer;";
    $res = $pdo->query($req);
    echo $req;
header('Location: ../doublon.php');
}