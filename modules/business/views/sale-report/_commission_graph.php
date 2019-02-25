<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31.10.18
 * Time: 12:22
 */
?>
<section class="panel panel-default">
    <header class="panel-heading font-bold">
        График товарооборотов / выданных комиссионных
    </header>
    <div class="panel-body">
        <div id="flot-trade-turnover" class="height250"></div>
    </div>
</section>
<script type="text/javascript">
    var arrayProfit = <?=json_encode(array_values($statisticInfo['generalReceiptMoneyMonth']))?>;
    var arrayFeesCommission = <?=json_encode(array_values($statisticInfo['feesCommissionMonth']))?>;
    var dateLabel = <?=json_encode($statisticInfo['dateInterval'])?>;
        console.log('arrayProfit ',arrayProfit);
        console.log('arrayFeesCommission ',arrayFeesCommission);
    $("#flot-trade-turnover").length && $.plot($("#flot-trade-turnover"), [
        {
            data: arrayProfit,
            label: 'Товарооборотов'
        }, {
            data: arrayFeesCommission,
            label: 'Начисленных комиссионных'
        }],
        {
            series: {
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.2
                        }, {
                            opacity: 0.1
                        }]
                    }
                },
                points: {
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f0f0f0",
                borderWidth: 0
            },
            colors: ["#dddddd","#ff6b3f"],
            xaxis: {
                ticks:dateLabel
            },
            yaxis: {
                ticks: 10,
                tickDecimals: 0
            },
            tooltip: true,
            tooltipOpts: {
                content: "%s - %y.4 euro",
                defaultTheme: false,
                shifts: {
                    x: 0,
                    y: 20
                }
            }
        }
    );
</script>

