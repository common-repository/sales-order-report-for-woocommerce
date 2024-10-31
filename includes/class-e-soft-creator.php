<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://esoftcreator.com
 * @since      1.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/includes
 * @author     E-soft Creator <esoft.creator@gmail.com>
 */
class Sales_Order_Report_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sales_Order_Report_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'E_SOFT_CREATOR_VERSION' ) ) {
			$this->version = E_SOFT_CREATOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sales-order-report-for-woocommerce';
		add_action( 'admin_enqueue_scripts', array( $this, 'esoftcreator_register_required_options_page_scripts' ) );
		$this->load_dependencies();
		$this->esoftcreator_load_plugin_textdomain();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_filter( 'plugin_action_links_'.plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name.'.php' ), array($this,'esoftcreator_plugin_action_links'),10 );


	}

	public function esoftcreator_register_required_options_page_scripts(){
		?>
		<script>
      var esoftc_ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    </script>
		<?php
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sales_Order_Report_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Sales_Order_Report_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Sales_Order_Report_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Sales_Order_Report_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-e-soft-creator-loader.php';

		/**
		 * esponsible for languages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-e-soft-creator-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-e-soft-creator-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/helpers/class-esoftcreator-ajax-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-esoftcreator-public.php';

		$this->loader = new Sales_Order_Report_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sales_Order_Report_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function esoftcreator_load_plugin_textdomain() {
		$plugin_i18n = new Sales_Order_Report_For_Woocommerce_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Sales_Order_Report_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		new Esoftcreator_Public( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sales_Order_Report_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function esoftcreator_plugin_action_links($links){
		 $deactivate_link = $links['deactivate'];
     unset($links['deactivate']);
     $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=esc-sales-analysis') . '">Sales Analysis</a>';
     $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=esc-sales-data-analysis') . '">Sales Data Analysis</a>';
     $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=esc-report-download') . '">Report Download</a>';
     $links['deactivate'] = $deactivate_link;
        return $links;
	}

}
