<?php
/*
Template Name: Course Platform Survey
Description: Collects BCIT course platform preferences from users and visualizes results using Highcharts.
*/

get_header();

$prefix = 'A00690683';
?>

<div class="wrap" style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2c3e50; text-align: center; margin-bottom: 10px;">BCIT</h1>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Here is BCIT course app</p>

    <form method="POST" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <?php wp_nonce_field('bcit_platform_form', 'bcit_nonce'); ?>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Your name:</label>
            <input type="text" name="<?php echo esc_attr($prefix); ?>_name" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" 
                   placeholder="Enter your name" required>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 12px; color: #333;">BCIT course:</label>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="MySQL" style="margin-right: 10px;" required> 
                    <span>MySQL</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="Android" style="margin-right: 10px;"> 
                    <span>Android</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="Javascript" style="margin-right: 10px;"> 
                    <span>Javascript</span>
                </label>
            </div>
        </div>

        <input type="submit" value="SUBMIT" 
               style="background: #3498db; color: white; border: none; padding: 12px 30px; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%;">
    </form>

    <?php
    if (
        isset($_POST['bcit_nonce']) &&
        wp_verify_nonce($_POST['bcit_nonce'], 'bcit_platform_form') &&
        !empty($_POST[$prefix.'_name']) &&
        !empty($_POST[$prefix.'_platform'])
    ) {
        $data = [
            'name'     => sanitize_text_field($_POST[$prefix.'_name']),
            'platform' => sanitize_text_field($_POST[$prefix.'_platform']),
        ];

        add_option(
            $prefix . '_' . date('ymdHis'),
            wp_json_encode($data)
        );
        ?>
        <script>
            alert("Thanks for submitting your data!");
            window.location.href = "<?php echo esc_url(site_url('/platform/')); ?>";
        </script>
        <?php
    }
    ?>

    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div id="pie-chart-container" style="width: 100%; height: 400px;"></div>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <p style="color: #888; font-size: 14px;">BCIT Course Platform Preferences</p>
    </div>

</div>

<?php
wp_enqueue_script('highcharts', 'https://code.highcharts.com/highcharts.js', [], null, true);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = <?php echo get_platforms_data($prefix); ?>;
    
    Highcharts.chart('pie-chart-container', {
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
            data: chartData,
            colors: ['#36A2EB', '#90ed7d', '#FF6384']
        }],
        credits: {
            enabled: false
        }
    });
});
</script>

<?php get_footer(); ?>

<?php
function get_platforms_data($prefix) {
    global $wpdb;

    $sql = $wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
        $wpdb->esc_like($prefix) . '%'
    );

    $results = $wpdb->get_results($sql);

    $counts = [
        'MySQL'      => 0,
        'Android'    => 0,
        'Javascript' => 0,
    ];

    foreach ($results as $row) {
        $data = json_decode($row->option_value, true);
        if (!empty($data['platform']) && isset($counts[$data['platform']])) {
            $counts[$data['platform']]++;
        }
    }

    $total = array_sum($counts);
    
    if ($total > 0) {
        $data_array = [
            ['name' => 'MySQL', 'y' => $counts['MySQL'], 'percentage' => round(($counts['MySQL'] / $total) * 100, 1)],
            ['name' => 'Android', 'y' => $counts['Android'], 'percentage' => round(($counts['Android'] / $total) * 100, 1)],
            ['name' => 'Javascript', 'y' => $counts['Javascript'], 'percentage' => round(($counts['Javascript'] / $total) * 100, 1)]
        ];
    } else {
        $data_array = [
            ['name' => 'MySQL', 'y' => 0, 'percentage' => 33.3],
            ['name' => 'Android', 'y' => 0, 'percentage' => 66.7],
            ['name' => 'Javascript', 'y' => 0, 'percentage' => 0.0]
        ];
    }

    usort($data_array, function($a, $b) {
        return $b['percentage'] <=> $a['percentage'];
    });

    return wp_json_encode($data_array);
}
?>
