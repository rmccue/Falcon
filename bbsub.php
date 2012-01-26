<?php
/**
 * Custom subscriptions for bbPress
 *
 */

/*
Plugin Name: bbPress Subscriptions
Description: Custom subscriptions for bbPress
Version: 0.1
Author: Ryan McCue
Author URI: http://ryanmccue.info/
*/

class bbSub {
	public static function verify() {
		remove_action('all_admin_notices', array('bbSub', 'report_error'));
		//Sputnik::check(__FILE__, array('bbSub', 'load'));
		bbSub::load();
	}

	public static function load() {
		require_once(dirname(__FILE__) . '/bbSubscriptions.php');

		bbSubscriptions::bootstrap();
	}

	public static function report_error() {
		echo '<div class="error"><p>Please install &amp; activate Sputnik to enable bbSubscriptions.</p></div>';
	}

	/**
	 * Register cron event on activation
	 */
	public static function activation() {
		wp_schedule_event(time(), 'bbsub_minutely', 'bbsub_check_inbox');	
	}

	/**
	 * Clear cron event on deactivation
	 */
	public static function deactivation() {
		wp_clear_scheduled_hook('bbsub_check_inbox');
	}
}

register_activation_hook(__FILE__, array('bbSub', 'activation'));
register_deactivation_hook(__FILE__, array('bbSub', 'deactivation'));

add_action('sputnik_loaded', array('bbSub', 'verify'));
add_action('all_admin_notices', array('bbSub', 'report_error'));