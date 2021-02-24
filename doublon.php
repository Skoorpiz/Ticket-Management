<?php
include_once 'includes/bdd.php';
include_once 'includes/header.php';
$req = "SELECT * FROM `customer` WHERE id_tag IS NULL ORDER BY `customer`.`name` ASC";
$res = $pdo->query($req);
$customerDisplay = $res->fetchAll();
?>
<br>
<form method="POST" action="script/traitementDoublon.php">
    <div class="col-2">
        <select id="multipleSelect" multiple data-style="bg-white rounded-pill px-4 py-3 shadow-sm " name="customer[]" class="selectpicker ">
            <?php
            for ($i = 0; $i < count($customerDisplay); $i++) {
            ?>
                <option value="<?php echo $customerDisplay[$i]['id_customer'] ?>"><?php echo $customerDisplay[$i]['name'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <br>
    <div class="col-2">
        <p>&nbsp;Libelle de regroupement</p>
        <input class="form-control" name="tag" type="text">
    </div>
    <br><br>
    <button class="btn btn-primary" type="submit">Valider</button>
</form>

<?php
include_once 'includes/footer.php';
