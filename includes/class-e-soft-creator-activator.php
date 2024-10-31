<?php

/**
 * Fired during plugin activation
 *
 * @link       https://esoftcreator.com
 * @since      1.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 * @author     E-soft Creator <esoft.creator@gmail.com>
 */
class Sales_Order_Report_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if (!is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
      wp_die('Hey, It seems WooCommerce plugin is not active on your wp-admin. this plugin can only be activated if you have active WooCommerce plugin in your wp-admin. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
	}

}
