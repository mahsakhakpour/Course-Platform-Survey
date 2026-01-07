function highcharts_pie(data) {
    Highcharts.chart('container', {
        chart: { type: 'pie' },
        title: { text: 'BCIT Course Platform Preferences' },
        series: [{
            name: 'Votes',
            colorByPoint: true,
            data: data
        }]
    });
}
