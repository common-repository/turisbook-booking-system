<?php



// Add a new interval of 180 seconds
// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
add_filter( 'cron_schedules', 'isa_add_every_thirty_minutes' );
function isa_add_every_thirty_minutes( $schedules ) {
	$schedules['every_thirty_minutes'] = array(
		'interval'  => 1800,
		'display'   => __( 'Every 30 Minutes', 'turisbook' )
	);
	return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'isa_add_every_thirty_minutes' ) ) {
	wp_schedule_event( time(), 'every_thirty_minutes', 'isa_add_every_thirty_minutes' );
}

// Hook into that action that'll fire every thirty minutes
add_action( 'isa_add_every_thirty_minutes', 'every_thirty_minutes_event_func' );
function every_thirty_minutes_event_func() {
	define('WP_USE_THEMES', false);
	require_once(ABSPATH . 'wp-load.php');
	$tbs = new Turisbook_Booking_System();

	$tbs->syncAmenities();
	$tbs->syncEstablishments();

	$pluginlog = plugin_dir_path(__FILE__).'debug.log';
	// error_log('Entrou '. date('Y-m-d H:i:s') .PHP_EOL, 3, $pluginlog);

	wp_die();
}
?>