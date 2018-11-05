<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 12:44
 */
use app\components\THelper;
?>

<section class="panel panel-default">
    <header class="panel-heading font-bold">
        <?= THelper::t('turnover_schedule'); ?>
    </header>
    <div class="panel-body">
        <div id="flot-profit" class="height250"></div>
    </div>
</section>
<script type="text/javascript">
    var arrayProfit = <?=json_encode(array_values($statisticInfo['generalReceiptMoneyMonth']))?>;
    var dateLabel = <?=json_encode($statisticInfo['dateInterval'])?>;
    $("#flot-profit").length && $.plot($("#flot-profit"), [{
            data: arrayProfit
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
            colors: ["#65bc76"],
            xaxis: {
                ticks:dateLabel
            },
            yaxis: {
                ticks: 10,
                tickDecimals: 0
            },
            tooltip: true,
            tooltipOpts: {
                content: "%y.4 euro",
                defaultTheme: false,
                shifts: {
                    x: 0,
                    y: 20
                }
            }
        }
    );
</script>
