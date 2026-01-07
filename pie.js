function highcharts_pie(data) {
    // Calculate total for percentages
    const total = data.reduce((sum, item) => sum + item.y, 0);
    
    // Add percentages to data
    const dataWithPercentages = data.map(item => {
        return {
            ...item,
            percentage: total > 0 ? parseFloat(((item.y / total) * 100).toFixed(1)) : 0
        };
    });
    
    // Default data if no submissions
    if (total === 0) {
        dataWithPercentages[0].percentage = 33.3; // MySQL
        dataWithPercentages[1].percentage = 66.7; // Android
        dataWithPercentages[2].percentage = 0.0;  // Javascript
    }
    
    // Sort by percentage (highest first)
    dataWithPercentages.sort((a, b) => b.percentage - a.percentage);
    
    Highcharts.chart('container', {
        chart: {
            type: 'pie',
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'BCIT Course Platform Preferences',
            align: 'center',
            style: {
                fontSize: '16px',
                color: '#2c3e50',
                fontWeight: 'bold'
            }
        },
        tooltip: {
            pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'default',
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.percentage:.1f} %',
                    distance: -40,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        fontSize: '14px',
                        textShadow: '1px 1px 2px rgba(0,0,0,0.5)'
                    }
                },
                showInLegend: false,
                size: '85%',
                innerSize: '0%'
            }
        },
        series: [{
            name: 'Courses',
            colorByPoint: true,
            data: dataWithPercentages,
            colors: ['#36A2EB', '#90ed7d', '#FF6384']
        }],
        credits: {
            enabled: false
        }
    });
}
