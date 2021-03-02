<?php
include_once 'includes/bdd.php';
include_once 'includes/functions.php';
$page = "home";
include_once 'includes/header.php';
$req = "SELECT DISTINCT year FROM ticket ORDER BY `ticket`.`year` ASC";
$res = $pdo->query($req);
$yearDisplay = $res->fetchAll();
$nbMinuteTotal = nbMinuteTotal($pdo);
$nbIntervention = nbInterventionTotal($pdo);

$yearOperator = array(date("Y") - 1, date("Y"));

$req = "SELECT operator.name, ticket.id_operator,SUM(time_minute) 
FROM ticket
INNER JOIN operator ON ticket.id_operator = operator.id_operator
WHERE year = YEAR(CURDATE()) OR year = YEAR(CURDATE()) -1
GROUP BY ticket.id_operator
ORDER BY SUM(time_minute)  DESC
LIMIT 4";
$res = $pdo->query($req);
$maxOperator = $res->fetchAll();

$req = "SELECT operator.name, ticket.id_operator,SUM(time_minute) 
FROM ticket
INNER JOIN operator ON ticket.id_operator = operator.id_operator
WHERE year = YEAR(CURDATE()) 
AND time_minute > 0 
OR year = YEAR(CURDATE()) -1
AND time_minute > 0 
GROUP BY ticket.id_operator
ORDER BY SUM(time_minute)  ASC
LIMIT 4";
$res = $pdo->query($req);
$minOperator = $res->fetchAll();

$req = "SELECT tag.name, ticket.id_tag,SUM(time_minute) AS SUMTIME 
FROM ticket
INNER JOIN tag ON ticket.id_tag = tag.id_tag
GROUP BY ticket.id_tag 
ORDER BY `SUMTIME`  DESC 
LIMIT 10";
$res = $pdo->query($req);
$maxCustomer = $res->fetchAll();

$req = "SELECT tag.name, ticket.id_tag,SUM(time_minute) AS SUMTIME 
FROM ticket
INNER JOIN tag ON ticket.id_tag = tag.id_tag
WHERE time_minute > 0 
GROUP BY ticket.id_tag  
ORDER BY `SUMTIME`  ASC
LIMIT 10";
$res = $pdo->query($req);
$minCustomer = $res->fetchAll();
?>
<br>
<center>
    <H2>Evolution annuel</H2>
    <div id="chartdiv3"></div>
    <H2>Nombre d'heures, de clients et d'interventions annuel</H2>
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

                <td>Nombre de clients</td>
                <?php for ($i = 0; $i < count($yearDisplay); $i++) {
                    $year = $yearDisplay[$i]['year'];
                    $req = "SELECT DISTINCT id_tag FROM ticket WHERE year = $year";
                    $res = $pdo->query($req);
                    $idTag = $res->fetchAll();
                    $nbCustomer = count($idTag);
                ?>
                    <td><?php echo $nbCustomer  ?></td>
                <?php } ?>
            </tr>
            <tr>

                <td>Nombre d'heures</td>
                <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                    <td><?php echo $nbMinuteTotal[$i]  ?></td>
                <?php } ?>
            </tr>
            <tr>

                <td>Nombre d'interventions</td>
                <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                    <td><?php echo $nbIntervention[$i]  ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
    <H2>Nombre d'interventions par zone annuel</H2>
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
                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 1 AND year = $year";
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
                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 2 AND year = $year";
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
                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 3 AND year = $year";
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
                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 4 AND year = $year";
                    $res = $pdo->query($req);
                    $grandeIntervention = $res->fetchAll();

                ?>
                    <td><?php echo $grandeIntervention[0][0]  ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
    <H2>Clients les plus importants en intervention (heures/interventions)</H2>
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th width="1px;">Clients</th>
                <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                    <th><?php echo $yearDisplay[$i]['year'] ?></th>
                <?php } ?>
            </tr>
            <?php for ($i = 0; $i < count($maxCustomer); $i++) { ?>
                <tr>
                    <td><?php echo $maxCustomer[$i]['name'];  ?></td>
                    <?php for ($n = 0; $n < count($yearDisplay); $n++) {
                        $idCustomer = $maxCustomer[$i]['id_tag'];
                        $year = $yearDisplay[$n]['year'];
                        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_tag = $idCustomer AND year = $year;";
                        $res = $pdo->query($req);
                        $sumTime[$n] = $res->fetchColumn();
                        $sumTime[$n] = $sumTime[$n] / 60;
                        $sumTime[$n] = number_format($sumTime[$n], 0, '.', ' ');
                        if (empty($sumTime[$n])) {
                            $sumTime[$n] = 0;
                        }
                        $req = "SELECT COUNT(*) FROM ticket WHERE id_tag = $idCustomer AND year = $year;";
                        $res = $pdo->query($req);
                        $nbInterventionCustomer[$n] =  $res->fetchColumn();
                    ?>
                        <td><?php echo  $sumTime[$n] . "H" . "<br>";
                            echo   $nbInterventionCustomer[$n] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>


        </thead>
        <tbody>
        </tbody>
    </table>
    <H2>Clients les moins importants en intervention (heures/interventions)</H2>
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th width="1px;">Clients</th>
                <?php for ($i = 0; $i < count($yearDisplay); $i++) { ?>
                    <th><?php echo $yearDisplay[$i]['year'] ?></th>
                <?php } ?>
            </tr>
            <?php for ($i = 0; $i < count($minCustomer); $i++) { ?>
                <tr>
                    <td><?php echo $minCustomer[$i]['name'];  ?></td>
                    <?php for ($n = 0; $n < count($yearDisplay); $n++) {
                        $idCustomer = $minCustomer[$i]['id_tag'];
                        $year = $yearDisplay[$n]['year'];
                        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_tag = $idCustomer AND year = $year;";
                        $res = $pdo->query($req);
                        $sumTime[$n] = $res->fetchColumn();
                        $sumTime[$n] = $sumTime[$n] / 60;
                        $sumTime[$n] = round($sumTime[$n], 2);
                        if (empty($sumTime[$n])) {
                            $sumTime[$n] = 0;
                        }
                        $req = "SELECT COUNT(*) FROM ticket WHERE id_tag = $idCustomer AND year = $year;";
                        $res = $pdo->query($req);
                        $nbInterventionCustomer[$n] =  $res->fetchColumn();
                    ?>
                        <td><?php echo  $sumTime[$n] . "H" . "<br>";
                            echo   $nbInterventionCustomer[$n] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </thead>
        <tbody>
        </tbody>
    </table>
    <H2>Opérateur les plus importants en intervention (heures/interventions)</H2>
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th width="1px;">Clients</th>
                <?php for ($i = 0; $i < count($yearOperator); $i++) { ?>
                    <th><?php echo $yearOperator[$i] ?></th>
                <?php } ?>
            </tr>
            <?php for ($i = 0; $i < count($maxOperator); $i++) { ?>
                <tr>
                    <td><?php echo $maxOperator[$i]['name'];  ?></td>
                    <?php for ($n = 0; $n < count($yearOperator); $n++) {
                        $idOperator = $maxOperator[$i]['id_operator'];
                        $year = $yearOperator[$n];
                        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_operator = $idOperator AND year = $year;";
                        $res = $pdo->query($req);
                        $sumTime[$n] = $res->fetchColumn();
                        $sumTime[$n] = $sumTime[$n] / 60;
                        $sumTime[$n] = number_format($sumTime[$n], 0, '.', ' ');
                        if (empty($sumTime[$n])) {
                            $sumTime[$n] = 0;
                        }
                        $req = "SELECT COUNT(*) FROM ticket WHERE id_operator = $idOperator AND year = $year;";
                        $res = $pdo->query($req);
                        $nbInterventionOperator[$n] =  $res->fetchColumn();
                    ?>
                        <td><?php echo  $sumTime[$n] . "H" . "<br>";
                            echo   $nbInterventionOperator[$n] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </thead>
        <tbody>
        </tbody>
    </table>
    <H2>Opérateur les moins importants en intervention (heures/interventions)</H2>
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th width="1px;">Clients</th>
                <?php for ($i = 0; $i < count($yearOperator); $i++) { ?>
                    <th><?php echo $yearOperator[$i] ?></th>
                <?php } ?>
            </tr>
            <?php for ($i = 0; $i < count($minOperator); $i++) { ?>
                <tr>
                    <td><?php echo $minOperator[$i]['name'];  ?></td>
                    <?php for ($n = 0; $n < count($yearOperator); $n++) {
                        $idOperator = $minOperator[$i]['id_operator'];
                        $year = $yearOperator[$n];
                        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_operator = $idOperator AND year = $year;";
                        $res = $pdo->query($req);
                        $sumTime[$n] = $res->fetchColumn();
                        $sumTime[$n] = $sumTime[$n] / 60;
                        $sumTime[$n] = round($sumTime[$n], 2);
                        if (empty($sumTime[$n])) {
                            $sumTime[$n] = 0;
                        }
                        $req = "SELECT COUNT(*) FROM ticket WHERE id_operator = $idOperator AND year = $year;";
                        $res = $pdo->query($req);
                        $nbInterventionOperator[$n] =  $res->fetchColumn();
                    ?>
                        <td><?php echo  $sumTime[$n] . "H" . "<br>";
                            echo   $nbInterventionOperator[$n] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </thead>
        <tbody>
        </tbody>
    </table>
</center>

<script>
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        var chart = am4core.create("chartdiv3", am4charts.XYChart);

        var data = [];
        var value;
        var name = [];

        <?php
        for ($i = 0; $i < count($yearDisplay); $i++) { ?>
            names = [<?php echo '"' . $yearDisplay[$i]['year'] . '",'; ?>];
            value = <?php echo $nbMinuteTotal[$i]  ?>;
            nbIntervention = <?php echo $nbIntervention[$i]   ?>;
            data.push({
                category: names[0],
                value: value,
                nbIntervention: nbIntervention
            });
        <?php
        }
        ?>

        chart.data = data;
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "category";


        var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis1.title.text = "Heures";

        var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis2.title.text = "Interventions";
        valueAxis2.renderer.opposite = true;

        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.categoryX = "category";
        series.name = "Nombre d'heures";
        series.tooltipText = "{name} : {value}";
        series.yAxis = valueAxis1;

        var series2 = chart.series.push(new am4charts.LineSeries());
        series2.dataFields.valueY = "nbIntervention";
        series2.dataFields.categoryX = "category";
        series2.name = "Nombre d'interventions";
        series2.tooltipText = "{name} : {nbIntervention}";
        series2.yAxis = valueAxis2;

        chart.cursor = new am4charts.XYCursor();



    }); // end am4core.ready()
</script>
<?php include_once 'includes/footer.php';
