<?php
include_once '../includes/bdd.php';
$id = $_GET['id'];
$req = "UPDATE customer set id_tag = NULL WHERE id_customer = $id";
echo $req;
$res = $pdo->query($req);
header('Location: ../gestionDoublon.php');
