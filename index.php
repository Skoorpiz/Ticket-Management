<?php 
include_once 'includes/bdd.php';
include_once 'includes/functions.php';
include_once 'includes/header.php';
$req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year DESC";
$res = $pdo->query($req);
$yearDisplay = $res->fetchAll();
$nbMinuteTotal = nbMinuteTotal($pdo);
?>
<br>
<center>
    <H2>Evolution du nombre d'heures total par années</H2>
</center>
<div id="chartdiv3"></div>

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
    data.push({
        category: names[0],
        value: value
    });
<?php
}
?>

chart.data = data;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";


var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Heures";

var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "value";
series.dataFields.categoryX = "category";
series.name = "Nombre d'heures";
series.tooltipText = "{name} : {value}";

chart.cursor = new am4charts.XYCursor();

chart.scrollbarX = new am4core.Scrollbar();
chart.scrollbarY = new am4core.Scrollbar();

}); // end am4core.ready()
</script>
<?php include_once 'includes/footer.php';
