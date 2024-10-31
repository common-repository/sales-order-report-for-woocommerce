<?php
/**
 * @link              
 * @since             1.0.0
 * @package           Sales_Order_Report_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Sales Order Report for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/sales-order-report-for-woocommerce
 * Description:       Sales order report for WooCommerce plugin is helpful to WooCommerce order sales analysis and WooCommerce order reporting with various important metrics data, given the order sales summary, cart abandoned orders and download WooCommerce orders report.
 * Version:           2.1.1
 * Author:            E-soft Creator
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sales-order-report-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 1.4.1
 * WC tested up to: 5.9.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'E_SOFT_CREATOR_VERSION', '2.1.1' );
if ( ! defined( 'E_SOFT_CREATOR_PLUGIN_DIR' ) ) {
    define( 'E_SOFT_CREATOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'E_SOFT_CREATOR_PLUGIN' ) ) {
    define( 'E_SOFT_CREATOR_PLUGIN', basename(__DIR__) );
}
if ( ! defined( 'E_SOFT_CREATOR_PLUGIN_URL' ) ) {
    define( 'E_SOFT_CREATOR_PLUGIN_URL', plugins_url() . '/'.E_SOFT_CREATOR_PLUGIN );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-e-soft-creator-activator.php
 */
function activate_Sales_Order_Report_For_Woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-e-soft-creator-activator.php';
	Sales_Order_Report_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-e-soft-creator-deactivator.php
 */
function deactivate_Sales_Order_Report_For_Woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-e-soft-creator-deactivator.php';
	Sales_Order_Report_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Sales_Order_Report_For_Woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_Sales_Order_Report_For_Woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-e-soft-creator.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_Sales_Order_Report_For_Woocommerce() {

	$plugin = new Sales_Order_Report_For_Woocommerce();
	$plugin->run();

}
run_Sales_Order_Report_For_Woocommerce();
