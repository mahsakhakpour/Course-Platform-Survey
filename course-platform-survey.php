<?php
/*
Template Name: Course Platform Survey
Description: Collects BCIT course platform preferences from users and visualizes results using Highcharts.
*/

get_header();

$prefix = 'A00690683';
?>

<div class="wrap">
  <h1>BCIT</h1>
  <p>Here is BCIT course app</p>

  <form method="POST">
    <?php wp_nonce_field('bcit_platform_form', 'bcit_nonce'); ?>

    <p>
      <label>Your name:</label><br>
      <input type="text" name="<?php echo esc_attr($prefix); ?>_name" required>
    </p>

    <p>
      <label>BCIT course:</label><br>
      <label><input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="MySQL" required> MySQL</label><br>
      <label><input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="Android" required> Android</label><br>
      <label><input type="radio" name="<?php echo esc_attr($prefix); ?>_platform" value="Javascript" required> Javascript</label>
    </p>

    <input type="submit" value="SUBMIT">
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
<?php } ?>

  <div id="container"></div>

</div>

<?php
wp_enqueue_script('highcharts', 'https://code.highcharts.com/highcharts.js', [], null, true);
wp_enqueue_script('bcit-pie', get_stylesheet_directory_uri() . '/pie.js', ['highcharts'], null, true);
?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    highcharts_pie(<?php echo get_platforms_data($prefix); ?>);
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
  
  // Calculate percentages
  $percentages = [];
  if ($total > 0) {
    $percentages = [
      'MySQL'      => round(($counts['MySQL'] / $total) * 100, 1),
      'Android'    => round(($counts['Android'] / $total) * 100, 1),
      'Javascript' => round(($counts['Javascript'] / $total) * 100, 1),
    ];
  } else {
    $percentages = [
      'MySQL'      => 0.0,
      'Android'    => 0.0,
      'Javascript' => 0.0,
    ];
  }

  // Return formatted data matching the image format
  return wp_json_encode([
    ['name' => 'Javascript', 'y' => $percentages['Javascript'], 'label' => 'Javascript: ' . $percentages['Javascript'] . ' %'],
    ['name' => 'Android', 'y' => $percentages['Android'], 'label' => 'Android: ' . $percentages['Android'] . ' %'],
    ['name' => 'MySQL', 'y' => $percentages['MySQL'], 'label' => 'MySQL: ' . $percentages['MySQL'] . ' %']
  ]);
}
?>
