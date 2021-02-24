<?php
include_once 'includes/bdd.php';
$page = "doublon";
include_once 'includes/header.php';
$req = "SELECT * FROM `tag` ORDER BY `tag`.`name` ASC";
$res = $pdo->query($req);
$tag = $res->fetchAll();
?>


<form>
<table class="table table-bordered ">
    <thead>
        <tr>
            <th>Identifiant</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>

    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($tag); $i++) { ?>
            <tr>
                <td><?php echo $tag[$i]['id_tag'] ?></td>
                <td><?php echo $tag[$i]['name'] ?></td>
                <td>
                    <a href="script/traitementDeleteDoublon.php?id=<?php echo $tag[$i]['id_tag'] ?>"><i class="fas fa-trash"></i></a>
                    &nbsp;
                    <a href="updateDoublon.php?id=<?php echo $tag[$i]['id_tag'] ?>"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
include_once 'includes/footer.php';
