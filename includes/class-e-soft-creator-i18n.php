<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://esoftcreator.com
 * @since      1.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 * @author     E-soft Creator <esoft.creator@gmail.com>
 */
class Sales_Order_Report_For_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		/*load_plugin_textdomain(
			'e-soft-creator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);*/
		$plugin_rel_path = dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/';
		$domain = 'e-soft-creator';
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );

		$mofile = 'sales-order-report-for-woocommerce' . '-' . $locale . '.mo';
		$path = WP_PLUGIN_DIR . '/' . trim( $plugin_rel_path, '/' );
		return load_textdomain( $domain, $path . '/' . $mofile );
	}
}