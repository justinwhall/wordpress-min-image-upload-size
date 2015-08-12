<?php
/*
 * Plugin Name:       WP Minimum Image Upload Size
 * Plugin URI:        http://www.justinwhall.com
 * Description:       Adds configurable minimum image upload size settings
 * Version:           0.1.0
 * Author:            Justin W. Hall
 * Author URI:        http://justinwhall.com
 * Text Domain:       wp-admin-validate
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


require_once plugin_dir_path( __FILE__ ) . 'includes/class-mis.php';

function run_wp_mis() {

	$mis = new WP_MIS();
	$mis->run();

}


run_wp_mis();