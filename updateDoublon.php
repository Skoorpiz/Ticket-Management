<?php
include_once 'includes/bdd.php';
$page = "doublon";
include_once 'includes/header.php';
$id = $_GET['id'];
if (isset($_POST['newTag'])) {
    $newTag = $_POST['newTag'];
    $newTag = $pdo->quote($newTag);
    $req = "UPDATE tag set name = $newTag WHERE id_tag = $id";
    $res = $pdo->exec($req);
}
$req = "SELECT * FROM tag WHERE id_tag = $id";
$res = $pdo->query($req);
$tag = $res->fetchAll();
$req = "SELECT * FROM customer WHERE id_tag = $id";
$res = $pdo->query($req);
$customer = $res->fetchAll();
?>
<p>Edition de regroupement > Modification du regroupement</p>
<form action="" method="POST">
    <p>Regroupement : </p> <input name="newTag" type="text" value="<?php echo $tag[0]['name'] ?>"><br><br>
    <button type="submit" class="btn btn-primary">Valider</button>
</form>
<br>
<table class="table table-bordered w-25">
    <thead>
        <tr>
            <th width="1px">Identifiant</th>
            <th>Nom</th>
            <th width="1px">Actions</th>
        </tr>

    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($customer); $i++) { ?>
            <tr>
                <td><?php echo $customer[$i]['id_tag'] ?></td>
                <td><?php echo $customer[$i]['name'] ?></td>
                <td>
                    <a href="script/traitementDeleteFilsDoublon.php?id=<?php echo $customer[$i]['id_customer'] ?>"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
include_once 'includes/footer.php';
