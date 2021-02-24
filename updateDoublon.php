<?php
include_once 'includes/bdd.php';
$page = "doublon";
include_once 'includes/header.php';
$id = $_GET['id'];
$req = "SELECT * FROM tag WHERE id_tag = $id";
$res = $pdo->query($req);
$tag = $res->fetchAll();
$req = "SELECT * FROM customer WHERE id_tag = $id";
$res = $pdo->query($req);
$customer = $res->fetchAll();
?>
<form action="" method="POST">
    <p>Regroupement : </p> <input type="text" value="<?php echo $tag[0]['name'] ?>">
</form>
<br>
<p>Doublons :
    <?php for ($i = 0; $i < count($customer); $i++) { ?>
        <?php echo "<br>";
        echo $customer[$i]['name']   ?>
    <?php } ?>
</p>
<?php
include_once 'includes/footer.php';
