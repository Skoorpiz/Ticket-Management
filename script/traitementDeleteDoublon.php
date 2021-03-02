<?php
include_once '../includes/bdd.php';
$id = $_GET['id'];
$req = "UPDATE customer set id_tag = NULL WHERE id_tag = $id";
$res = $pdo->query($req);
$req = "DELETE FROM tag WHERE id_tag = $id";
$res = $pdo->query($req);
header('Location: ../gestionDoublon.php');
