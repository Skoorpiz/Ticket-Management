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
    $tagForm = $_POST['tagForm'];
    $moyMinute = moyMinuteCustomer($pdo, $customer, $year);
    $maxMinute = maxMinuteCustomer($pdo, $customer, $year);
    $minMinute = minMinuteCustomer($pdo, $customer, $year);
    $nbMinute = nbMinuteCustomer($pdo, $customer);
    for ($b = 0; $b < count($customer); $b++) {
        $req = "SELECT DISTINCT name,year,customer.id_customer FROM customer,ticket WHERE customer.id_customer = $customer[$b] AND year = $year";
        $res = $pdo->query($req);
        $Choice[] = $res->fetchAll();
    }
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
    <div class="col-1">
        <select id="tag" class="hidden form-control" onchange="Change2(this.value)" name="tagForm">
            <option selected>Choisir un tag </option>
            <?php for ($i = 0; $i < count($tag); $i++) { ?>
                <option value="<?php echo $tag[$i][0] ?>"><?php echo $tag[$i][1] ?></option>
            <?php } ?>
        </select>
    </div>
    <br>
    <div class="hidden" id="customer">
        <div class="col-2">
            <select id="multipleSelect" multiple data-style="bg-white rounded-pill px-4 py-3 shadow-sm " name="customer[]" class="selectpicker">
            </select>
        </div>
        <br>
        <div class="col-2">
            <select class="form-control" name="year">
                <option selected><?php if (isset($Choice) && isset($customer)) {
                                        echo $Choice[0][0]['year'];
                                    } else { ?> Choisir une année <?php } ?></option>
                <?php
                for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                    <option><?php echo $yearDisplay[$i]['year'] ?></option>

                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <br><br>
    <button class="btn btn-primary" type="submit">Valider</button>
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
            <H2>Evolution mensuel de <?php for ($b = 0; $b < count($customer); $b++) {
                                            echo $Choice[$b][0]['name'] . ", ";
                                        } ?>
                pour l'année de <?php echo  $Choice[0][0]['year'] ?>
            <?php
        } else if (isset($operator)) { ?>
                <H2>Evolution mensuel de <?php echo $Choice[0]['name'] ?></H2><?php } ?>
    </center>
<?php
}
?>
<br>
<div id="chartdiv1"></div>
<?php
if (isset($Choice)) {
?>
    <center>
        <?php if (isset($customer)) { ?>
            <H2>Evolution annuel de <?php for ($b = 0; $b < count($customer); $b++) {
                                        echo $Choice[$b][0]['name'] . ", ";
                                    } ?>
            <?php
        } else if (isset($operator)) { ?>
                <H2>Evolution annuel de <?php echo $Choice[0]['name'] ?></H2><?php } ?>
    </center>
<?php
}
?>
<br>
<div id="chartdiv2"></div>
<script>
    $(function() {
        $('.selectpicker').selectpicker();
    });
    <?php if (isset($customer)) { ?>
        document.getElementById("customer").classList.remove("hidden");
        document.getElementById("tag").classList.remove("hidden");
    <?php } else if (isset($operator)) { ?>
        document.getElementById("operator").classList.remove("hidden");
    <?php } ?>

    function resetValue() {
        document.getElementById("customer").classList.add("hidden");
        document.getElementById("operator").classList.add("hidden");
        document.getElementById("tag").classList.add("hidden");

    }

    function Change(val) {
        resetValue();
        switch (val) {
            case "client":
                // document.getElementById("customer").classList.remove("hidden");
                document.getElementById("tag").classList.remove("hidden");
                break;
            case "operateur":
                document.getElementById("operator").classList.remove("hidden");
                break;
        }
    }

    function Change2(val) {
        document.getElementById("customer").classList.remove("hidden");
        var formData = new FormData();
        formData.append('datum', val);
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                // console.log(xmlHttp.responseText);
                //    var afficherTag = $("#multipleSelect").text(xmlHttp.responseText);
                //    console.log(afficherTag);
                document.getElementById("multipleSelect").innerHTML = xmlHttp.responseText;
                $('.selectpicker').selectpicker('refresh');
            }
        }
        xmlHttp.open("post", "script/traitementAjax.php");
        xmlHttp.send(formData);
    }
</script>
<?php
if (isset($operator) || isset($customer) && isset($year)) {
    include_once 'includes/traitementAmcharts.php';
}
include_once 'includes/footer.php';
