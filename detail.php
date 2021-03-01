<?php
include_once 'includes/bdd.php';
include_once 'includes/functions.php';
$page = "détails";
include_once 'includes/header.php';

$req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year ASC";
$res = $pdo->query($req);
$yearDisplay = $res->fetchAll();
$req = "SELECT * FROM operator ORDER BY `operator`.`name` ASC";
$res = $pdo->query($req);
$operatorDisplay = $res->fetchAll();
$Choice = [];
$req = "SELECT * FROM tag ORDER BY `tag`.`name` ASC";
$res = $pdo->query($req);
$tag = $res->fetchAll();
if (isset($_GET['customer']) && isset($_GET['year'])) {
    $customer = $_GET['customer'];
    $year = $_GET['year'];
    $moyMinute = moyMinuteCustomer($pdo, $customer, $year);
    $maxMinute = maxMinuteCustomer($pdo, $customer, $year);
    $minMinute = minMinuteCustomer($pdo, $customer, $year);
    $nbMinute = nbMinuteCustomer($pdo, $customer);
    $req = "SELECT tag.name,year,tag.id_tag FROM tag,ticket WHERE tag.id_tag = $customer AND year = $year";
    $res = $pdo->query($req);
    $Choice = $res->fetchAll();
}
if (isset($_GET['operator']) && isset($_GET['year'])) {
    $operator = $_GET['operator'];
    $year = $_GET['year'];
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
<style>
    .bold {
        font-weight: bold;
    }
</style>
<div class="col-1">
    <select class="form-control" onchange="Change(this.value)">
        <option>Choisir une option..</option>
        <option <?php if (isset($_GET['customer'])) { ?> selected <?php } ?> value="client">Client</option>
        <option <?php if (isset($_GET['operator'])) { ?> selected <?php } ?> value="operateur">Operateur</option>
    </select>
</div>
<br>
<form id="customer" class="hidden" method="GET" action="">
    <div class="col-2">
        <select onchange="Change2(this.value)" class="form-control" name="customer">
            <?php if (isset($_GET['customer'])) {
                $req = "SELECT * FROM tag WHERE id_tag = $customer";
                $res = $pdo->query($req);
                $tagSelected = $res->fetchAll();
            ?>
                <option class="bold" selected value="<?php echo $customer ?>" selected><?php echo $tagSelected[0]['name'] ?></option>
            <?php } else { ?>
                <option selected>Choisir un regroupement</option>
            <?php } ?>
            <?php for ($i = 0; $i < count($tag); $i++) : ?>
                <option value="<?php echo $tag[$i][0] ?>"><?php echo $tag[$i][1] ?></option>
            <?php endfor; ?>
        </select>
        <br>
        <?php if (isset(($_GET['customer']))) {
            $req = "SELECT DISTINCT year FROM ticket WHERE id_tag = $customer ORDER BY `ticket`.`year` ASC";
            $res = $pdo->query($req);
            $yearSelected = $res->fetchAll();
        ?>
            <select id="year" class="form-control" name="year">
                <option class="bold" selected><?php echo $year ?></option>
                <?php for ($i = 0; $i < count($yearSelected); $i++) : ?>
                    <option><?php echo $yearSelected[$i]['year'] ?></option>
                <?php endfor; ?>
            </select>
        <?php } else { ?>
            <select id="year" class="form-control" name="year">
                <option selected> Choisir une année</option>
            </select>
        <?php } ?>
        <br>
        <button class="btn btn-primary" type="submit">Valider</button>
    </div>
    <br>
</form>

<form id="operator" class="hidden" method="GET" action="">
<div class="col-2 ">
        <select onchange="Change3(this.value)" class="form-control" name="operator">
            <?php if (isset(($_GET['operator']))) {
                $req = "SELECT * FROM operator WHERE id_operator = $operator";
                $res = $pdo->query($req);
                $operatorSelected = $res->fetchAll();
            ?>
                <option class="bold" selected value="<?php echo $operator ?>" selected><?php echo $operatorSelected[0]['name'] ?></option>
            <?php } else { ?>
                <option selected>Choisir un opérateur</option>
            <?php } ?>
            <?php
            for ($i = 0; $i < count($operatorDisplay); $i++) {
            ?>
                <option value="<?php echo $operatorDisplay[$i]['id_operator'] ?>"><?php echo $operatorDisplay[$i]['name'] ?></option>
            <?php
            }
            ?>
        </select>
        <br>
    <?php if (isset(($_GET['operator']))) { 
            $req = "SELECT DISTINCT year FROM ticket WHERE id_operator = $operator ORDER BY `ticket`.`year` ASC";
            $res = $pdo->query($req);
            $yearSelected = $res->fetchAll();
            ?>
             <select id="year2" class="form-control" name="year">
                <option class="bold" selected><?php echo $year ?></option>
                <?php for ($i = 0; $i < count($yearSelected); $i++) : ?>
                    <option><?php echo $yearSelected[$i]['year'] ?></option>
                <?php endfor; ?>
            </select>
        <?php } else { ?>
        <select id="year2" class="form-control" name="year">
            <option selected>Choisir une année</option>
        </select>
        <?php } ?>
    <br>
    <button class="btn btn-primary" type="submit">Valider</button>
    </div>
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
            </H2>
            <?php
        } else if (isset($operator)) { ?>
                <H2>Evolution mensuel de <?php echo $Choice[0]['name'] ?> pour l'année de <?php echo  $Choice[0]['year'] ?></H2><?php } ?>
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
                </H2>
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
                            <th width="1px;">Interventions</th>
                            <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                                <th><?php echo $yearDisplay[$i]['year'] ?></th>
                            <?php } ?>
                        </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td>Instantanées</td>
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
                            <td>Petite interventions</td>
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
                            <td>Moyenne interventions</td>
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
                            <td>Grande interventions</td>
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
        // var year = <?php
                        // echo isset($_GET['customer']) ? $_GET['customer'] : 0
                        ?>;
        // function init() {
        //     var customer =  document.getElementById("customer").options[e.selectedIndex].text;
        //     if (customer) {
        //         Change2(customer);
        //     }
        // }

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

        function Change2(val) {
            var formData = new FormData();
            formData.append('datum', val);
            // formData.append('year', year);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    document.getElementById("year").innerHTML = xmlHttp.responseText;
                }
            }
            xmlHttp.open("post", "script/traitementCustomer.php");
            xmlHttp.send(formData);
        }

        function Change3(val) {
            var formData = new FormData();
            formData.append('datum', val);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    document.getElementById("year2").innerHTML = xmlHttp.responseText;
                }
            }
            xmlHttp.open("post", "script/traitementOperator.php");
            xmlHttp.send(formData);
        }
    </script>
    <?php
    if (isset($operator) && isset($year) || isset($customer) && isset($year)) {
        include_once 'includes/traitementAmcharts.php';
    }
    include_once 'includes/footer.php';
