$(function () {
    var graphOptions = {
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
        colors: ["#dddddd", "#89cb4e"],
        xaxis: {
            ticks: null
        },
        yaxis: {
            ticks: 10,
            tickDecimals: 0,
            min: 0
        },
        tooltip: true,
        tooltipOpts: {
            content: "%y.4 %s",
            defaultTheme: false,
            shifts: {
                x: 0,
                y: 20
            }
        }
    };

    function showRegistrationsStatisticsPerMoths() {
        var floatChart = $("#flot-chart");
        $.ajax({
            type: 'GET',
            url: '/' + LANG + '/business/default/get-registrations-statistics-per-moths',
            success: function(data) {
                data = JSON.parse(data);
                if (data) {
                    floatChart.height(240);
                    var d2 = [];
                    for (var i = 0; i < data.length; i++) {
                        d2.push([i, parseInt(data[i]['paid'])]);
                    }
                    var d3 = [];
                    for (var i = 0; i < data.length; i++) {
                        d3.push([i, parseInt(data[i]['registrations'])]);
                    }
                    var dates = [];
                    for (var i = 0; i < data.length; i++) {
                        var date_splited = data[i]['date'].split('/');
                        var date;
                        if (window.innerWidth >= 768) {
                            date = months[date_splited[0]];
                        } else {
                            date = date_splited[0];
                        }
                        dates.push([i, date]);
                    }
                    graphOptions.xaxis.ticks = dates;
                    floatChart.length && $.plot(floatChart, [{
                            data: d2,
                            label: labelPaid
                        }, {
                            data: d3,
                            label: labelRegistrations
                        }],
                        graphOptions
                    );
                }
            }
        });
    }

    //showRegistrationsStatisticsPerMoths();

    var floatIncome = $("#flot-income");

    if (floatIncome.length) {

        graphOptions.tooltipOpts.content = "%s: %y.4 ";

        (function() {
            var data = checksStatisticsPerMoths;

            floatIncome.height(240);
            var dates = [];
            var incomes = [];
            var dataLength = data.length;

            if (dataLength > 13) {
                dataLength = 13;
                data = data.slice(-dataLength);
            }

            for (var i = 0; i < dataLength; i++) {
                var date_splited = data[i]['date'].split('/');
                var date;
                if (window.innerWidth >= 768) {
                    date = months[date_splited[0]];
                } else {
                    date = date_splited[0];
                }
                dates.push([i, date]);
                incomes.push([i, parseInt(data[i]['sum'])]);
            }

            graphOptions.xaxis.ticks = dates;
            $.plot(floatIncome, [{
                    data: incomes,
                    label: labelCheck
                }],
                graphOptions
            );
        })();
    }

    var flotIncomeStatistic = $("#flot-income-statistic");

    if (flotIncomeStatistic.length) {
        graphOptions.tooltipOpts.content = "%s: %y.4 ";

        (function() {
            var data = incomeStatisticsPerMoths;

            flotIncomeStatistic.height(240);
            var dates = [];
            var incomes = [];

            function calculate_prognos(past, now, n) {
                return (past * Math.log(n) + now) / 2;
            }

            var userCreated = new Date(user.created);
            var userCreatedMonth = new Date(userCreated.getFullYear() + '-' + ('0' + (userCreated.getMonth() + 1)).slice(-2) + '-01')

            var n = 0;
            for (var i = 0; i < data.length; i++) {
                var date_splited = data[i]['date'].split('/');
                var date;
                var dataCreated = new Date('20' + date_splited[1] + '-' + date_splited[0] + '-01');
                if (userCreatedMonth <= dataCreated) {
                    if (window.innerWidth >= 768) {
                        date = months[date_splited[0]];
                    } else {
                        date = date_splited[0];
                    }
                    dates.push([n, date]);
                    incomes.push([n, parseInt(data[i]['income'])]);
                    n++;
                }
            }

            var incomesLength = incomes.length;
            var incomes_prognos = [[0, '0']];

            var prognos = incomes[1] ? incomes[1][1] : incomes[0][1];
            var sum = 0; //incomes[0][1];
            for (n = 1; n < incomesLength; n++) {
                if (n == 1) {
                    sum += incomes[n][1];
                    incomes_prognos.push([1, incomes[1][1]]);
                } else {
                    sum += incomes[n][1];
                    incomes_prognos.push([n, prognos]);
                    prognos = calculate_prognos(sum / n, incomes[n][1], n + 1);
                }
            }

            for (var y = 1, lastYear = new Date().getFullYear(); y <= 5; y++) {
                dates.push([n, lastYear + y]);
                prognos = calculate_prognos(sum / incomesLength, incomes[incomesLength - 1][1], (y+1)*12);
                incomes_prognos.push([n, prognos]);
                n++;
            }

            graphOptions.xaxis.ticks = dates;
            $.plot(flotIncomeStatistic, [{
                    data: incomes_prognos,
                    label: labelProjectedIncoming
                }, {
                    data: incomes,
                    label: labelIncoming
                }],
                graphOptions
            );
        })();
    }
});
