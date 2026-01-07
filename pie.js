function highcharts_pie(data) {
    Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'BCIT Course Platform Preferences'
        },
        tooltip: {
            pointFormat: '{point.label}'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.label}',
                    distance: -30,
                    style: {
                        fontWeight: 'bold',
                        color: 'white'
                    }
                },
                showInLegend: false
            }
        },
        series: [{
            name: 'Votes',
            colorByPoint: true,
            data: data,
            colors: ['#7cb5ec', '#434348', '#90ed7d'] // Different colors for each segment
        }]
    });
}
