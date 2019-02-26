<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:36
 */
use app\components\THelper;
?>

<header class="panel-heading font-bold">
    <?= THelper::t('main_partners_graph_1'); ?>
</header>
<div class="panel-body">
    <div id="flot-connect-partners" class="height250"></div>
</div>
<script>
    //-------------------------------
    function DrawPartners() {
        var labelPaid = "<?= THelper::t('paid') ?>";
        var labelRegistrations = "<?= THelper::t('registrations') ?>";

        var arrayConnectPartners = <?=json_encode(array_values($statisticInfo['newRegistrationForMonth']))?>;

        var arrayPaidPartners =  <?=json_encode(array_values($statisticInfo['ofThemPaidForMonth']))?>;

        var dateLabel = <?=json_encode($statisticInfo['dateInterval'])?>;

        $.plot($("#flot-connect-partners"), [
                {
                    data: arrayPaidPartners,
                    label: labelPaid
                },{
                    data: arrayConnectPartners,
                    label: labelRegistrations
                }
            ],
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
                colors: ["#dddddd","#89cb4e"],
                xaxis: {
                    ticks:dateLabel
                },
                yaxis: {
                    ticks: 10,
                    tickDecimals: 0
                },
                tooltip: true,
                tooltipOpts: {
                    content: "'%s' - %y.4 чел",
                    defaultTheme: false,
                    shifts: {
                        x: 0,
                        y: 20
                    }
                }
            }
        );
    }
    //-----------------------
    DrawPartners();
</script>
