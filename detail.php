<?php
include_once 'includes/bdd.php';
include_once 'includes/functions.php';
$page = "détails";
include_once 'includes/header.php';

$req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year DESC";
$res = $pdo->query($req);
$yearDisplay = $res->fetchAll();
$req = "SELECT * FROM operator";
$res = $pdo->query($req);
$operatorDisplay = $res->fetchAll();
$Choice = [];
$req = "SELECT * FROM tag ORDER BY `tag`.`name` ASC";
$res = $pdo->query($req);
$tag = $res->fetchAll();
if (isset($_POST['customer']) && isset($_POST['year'])) {
    $customer = $_POST['customer'];
    $year = $_POST['year'];
    $moyMinute = moyMinuteCustomer($pdo, $customer, $year);
    $maxMinute = maxMinuteCustomer($pdo, $customer, $year);
    $minMinute = minMinuteCustomer($pdo, $customer, $year);
    $nbMinute = nbMinuteCustomer($pdo, $customer);
    $req = "SELECT tag.name,year,tag.id_tag FROM tag,ticket WHERE tag.id_tag = $customer AND year = $year";
    $res = $pdo->query($req);
    $Choice = $res->fetchAll();
}
if (isset($_POST['operator']) && isset($_POST['year'])) {
    $operator = $_POST['operator'];
    $year = $_POST['year'];
    $moyMinute = moyMinuteOperator($pdo, $operator, $year);
    $maxMinute = maxMinuteOperator($pdo, $operator, $year);
    $minMinute = minMinuteOperator($pdo, $operator, $year);
    $nbMinute = nbMinuteOperator($pdo, $operator);
    $req = "SELECT name,year,operator.id_operator FROM operator,ticket WHERE operator.id_operator = $operator AND year = $year";
    $res = $pdo->query($req);
    $Choice = $res->fetchAll();
}
?>
<br>
<div class="col-1">
    <select class="form-control" onchange="Change(this.value)">
        <option>Choisir une option..</option>
        <option <?php if (isset($customer)) { ?> selected <?php } ?> value="client">Client</option>
        <option <?php if (isset($operator)) { ?> selected <?php } ?> value="operateur">Operateur</option>
    </select>
</div>
<br>
<form method="POST" action="">
    <div id="customer" class="col-2 hidden">
        <select class=" form-control" name="customer">
            <option selected><?php if (isset($Choice) && isset($customer)) {
                                    echo $Choice[0]['name'];
                                } else { ?> Choisir un regroupement <?php } ?></option>
            <?php for ($i = 0; $i < count($tag); $i++) { ?>
                <option value="<?php echo $tag[$i][0] ?>"><?php echo $tag[$i][1] ?></option>
            <?php } ?>
        </select>
        <br>
        <select class="form-control" name="year">
            <option selected><?php if (isset($Choice) && isset($customer)) {
                                    echo $Choice[0]['year'];
                                } else { ?> Choisir une année <?php } ?></option>
            <?php
            for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                <option><?php echo $yearDisplay[$i]['year'] ?></option>
            <?php
            }
            ?>
        </select>
        <br>
        <button class="btn btn-primary" type="submit">Valider</button>
    </div>
    <br>
</form>

<form id="operator" class="hidden" method="POST" action="">
    <div class="col-2">
        <select class="form-control" name="operator">
            <option <?php if (isset($Choice) && isset($operator)) { ?> value="<?php echo $Choice[0]['id_operator'] ?>" <?php } ?> selected><?php if (isset($Choice) && isset($operator)) {
                                                                                                                                                echo $Choice[0]['name'];
                                                                                                                                            } else { ?> Choisir un opérateur <?php } ?></option>
            <?php
            for ($i = 0; $i < count($operatorDisplay); $i++) {
            ?>
                <option value="<?php echo $operatorDisplay[$i]['id_operator'] ?>"><?php echo $operatorDisplay[$i]['name'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-2">
        <select class="form-control" name="year">
            <option selected><?php if (isset($Choice) && isset($operator)) {
                                    echo $Choice[0]['year'];
                                } else { ?> Choisir une année <?php } ?></option>
            <?php
            for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                <option><?php echo $yearDisplay[$i]['year'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <br><br>
    <button class="btn btn-primary" type="submit">Valider</button>
</form>
<?php
if (isset($Choice)) {
?>
    <center>
        <?php if (isset($customer)) { ?>
            <H2>Evolution mensuel de <?php
                                        echo $Choice[0]['name'];
                                        ?>
                pour l'année de <?php echo  $Choice[0]['year'] ?>
            <?php
        } else if (isset($operator)) { ?>
                <H2>Evolution mensuel de <?php echo $Choice[0]['name'] ?></H2><?php } ?>
        <?php
    }
        ?>
        <br>
        <div id="chartdiv1"></div>
        <?php
        if (isset($Choice)) {
        ?>
            <?php if (isset($customer)) { ?>
                <H2>Evolution annuel de <?php
                                        echo $Choice[0]['name'];
                                        ?>
                <?php
            } else if (isset($operator)) { ?>
                    <H2>Evolution annuel de <?php echo $Choice[0]['name'] ?></H2><?php } ?>
            <?php
        }
            ?>
            <br>
            <div id="chartdiv2"></div>
            <?php if (isset($customer) || isset($operator)) { ?>
                <table class="table table-bordered w-50">
                    <thead>
                        <tr>
                            <th width="1px;"></th>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                                <th><?php echo $yearDisplay[$i]['year'] ?></th>
                            <?php } ?>
                        </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td>Instantanée</td>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) {
                                $year = $yearDisplay[$i]['year'];
                                if (isset($customer)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 1 AND year = $year AND id_tag = $customer";
                                } else if (isset($operator)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 1 AND year = $year AND id_operator = $operator";
                                }
                                $res = $pdo->query($req);
                                $instantanée = $res->fetchAll();
                            ?>
                                <td><?php echo $instantanée[0][0]  ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Petite intervention</td>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) {
                                $year = $yearDisplay[$i]['year'];
                                if (isset($customer)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 2 AND year = $year AND id_tag = $customer";
                                } else if (isset($operator)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 2 AND year = $year AND id_operator = $operator";
                                }
                                $res = $pdo->query($req);
                                $petiteIntervention = $res->fetchAll();

                            ?>
                                <td><?php echo $petiteIntervention[0][0]  ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Moyenne intervention</td>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) {
                                $year = $yearDisplay[$i]['year'];
                                if (isset($customer)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 3 AND year = $year AND id_tag = $customer";
                                } else if (isset($operator)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 3 AND year = $year AND id_operator = $operator";
                                }
                                $res = $pdo->query($req);
                                $moyenneIntervention = $res->fetchAll();
                            ?>
                                <td><?php echo $moyenneIntervention[0][0]  ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Grande intervention</td>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) {
                                $year = $yearDisplay[$i]['year'];
                                if (isset($customer)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 4 AND year = $year AND id_tag = $customer";
                                } else if (isset($operator)) {
                                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 4 AND year = $year AND id_operator = $operator";
                                }
                                $res = $pdo->query($req);
                                $grandeIntervention = $res->fetchAll();
                            ?>
                                <td><?php echo $grandeIntervention[0][0]  ?></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>
    </center>
    <script>
        <?php if (isset($customer)) { ?>
            document.getElementById("customer").classList.remove("hidden");
        <?php } else if (isset($operator)) { ?>
            document.getElementById("operator").classList.remove("hidden");
        <?php } ?>

        function resetValue() {
            document.getElementById("customer").classList.add("hidden");
            document.getElementById("operator").classList.add("hidden");
        }

        function Change(val) {
            resetValue();
            switch (val) {
                case "client":
                    document.getElementById("customer").classList.remove("hidden");
                    break;
                case "operateur":
                    document.getElementById("operator").classList.remove("hidden");
                    break;
            }
        }
    </script>
    <?php
    if (isset($operator) && isset($year) || isset($customer) && isset($year)) {
        include_once 'includes/traitementAmcharts.php';
    }
    include_once 'includes/footer.php';
