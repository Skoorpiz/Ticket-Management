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
?>
<br>
<center>
    <H2>Evolution de toutes les années</H2>
</center>
<div id="chartdiv3"></div>
<center>
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
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th width="1px;">Intervention</th>
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
                    $req = "SELECT COUNT(*) FROM ticket WHERE id_zone = 1 AND year = $year";
                    $res = $pdo->query($req);
                    $instantanée = $res->fetchAll();

                ?>
                    <td><?php echo $instantanée[0][0]  ?></td>
                <?php } ?>
            </tr>
            <tr>

                <td>Petite</td>
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

                <td>Moyenne</td>
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

                <td>Grande</td>
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
