<script>
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv1", am4charts.XYChart);
        var value;
        var open;
        var close;
        var date;
        var data = [];
        <?php
        $compteur = 0;
        for ($i = 1; $i <= 12; $i++) {
            if (isset($operator)) {
        ?>
                value = <?php
                        echo $moyMinute[$i]
                        ?>;
                open = <?php
                        echo $maxMinute[$i]
                        ?>;
                close = <?php
                        echo $minMinute[$i]
                        ?>;
                date = new Date();
                date.setFullYear(0, <?php
                                    echo $compteur
                                    ?>);
                data.push({

                    date: date,
                    value: value,
                    open: open,
                    close: close,
                });
            <?php
                $compteur++;
            }
            if (isset($customer)) {
            ?>
                value = <?php
                        echo $moyMinute[$i]
                        ?>;
                open = <?php
                        echo $maxMinute[$i]
                        ?>;
                close = <?php
                        echo $minMinute[$i]
                        ?>;
                date = new Date();
                date.setFullYear(0, <?php
                                    echo $compteur
                                    ?>);
                data.push({

                    date: date,
                    value: value,
                    open: open,
                    close: close,
                });
        <?php
                $compteur++;
            }
        }

        ?>
        console.log(chart.data);
        chart.data = data;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Minutes";

        var series1 = chart.series.push(new am4charts.LineSeries());
        series1.dataFields.valueY = "value";
        series1.dataFields.dateX = "date";
        series1.yAxis = valueAxis;
        series1.name = "Moyenne";
        series1.tooltipText = "{name} : {value}";

        var series2 = chart.series.push(new am4charts.ColumnSeries());
        series2.dataFields.openValueY = "open";
        series2.dataFields.valueY = "close";
        series2.tooltipText = "Max: {openValueY.value} Min: {valueY.value}";
        series2.dataFields.dateX = "date";
        series2.yAxis = valueAxis;
        series2.name = "Maximum et Minimum";
        series2.tooltip.pointerOrientation = "horizontal";
        series2.sequencedInterpolation = true;
        series2.fillOpacity = 0;
        series2.strokeOpacity = 1;
        series2.columns.template.width = 0.01;

        var openBullet = series2.bullets.create(am4charts.CircleBullet);
        openBullet.locationY = 1;

        var closeBullet = series2.bullets.create(am4charts.CircleBullet);

        closeBullet.fill = chart.colors.getIndex(4);
        closeBullet.stroke = closeBullet.fill;

        chart.legend = new am4charts.Legend();
        chart.legend.position = "top";

        chart.cursor = new am4charts.XYCursor();

        chart.scrollbarY = new am4core.Scrollbar();
        chart.scrollbarX = new am4core.Scrollbar();


    }); // end am4core.ready()
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        var chart = am4core.create("chartdiv2", am4charts.XYChart);

        var data = [];
        var value;
        var name = [];

        <?php
        for ($i = 0; $i < count($yearDisplay); $i++) {
            if (isset($operator)) { ?>
                names = [<?php echo '"' . $yearDisplay[$i]['year'] . '",'; ?>];
                value = <?php echo $nbMinute[$i]  ?>;
                data.push({
                    category: names[0],
                    value: value
                });
            <?php
            }
            if (isset($customer)) { ?>
                names = [<?php echo '"' . $yearDisplay[$i]['year'] . '",'; ?>];
                value = <?php echo $nbMinute[$i]; ?>

                data.push({
                    category: names[0],
                    value: value
                });

        <?php }
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


        // var series = chart.series.push(new am4charts.LineSeries());
        // series.dataFields.categoryX = "category";
        // series.dataFields.valueY = "value";
        // series.sequencedInterpolation = true;
        // series.fillOpacity = 0;
        // series.strokeOpacity = 1;
        // series.columns.template.width = 0.01;
        // series.tooltip.pointerOrientation = "horizontal";

        // var openBullet = series.bullets.create(am4charts.CircleBullet);
        // openBullet.locationY = 1;

        // var closeBullet = series.bullets.create(am4charts.CircleBullet);

        // closeBullet.fill = chart.colors.getIndex(4);
        // closeBullet.stroke = closeBullet.fill;

        chart.cursor = new am4charts.XYCursor();

        chart.scrollbarX = new am4core.Scrollbar();
        chart.scrollbarY = new am4core.Scrollbar();

    }); // end am4core.ready()
</script>