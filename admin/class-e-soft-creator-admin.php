<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://esoftcreator.com
 * @since      1.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/admin
 * @author     E-soft Creator <esoft.creator@gmail.com>
 */
class Sales_Order_Report_For_Woocommerce_Admin {
	private $plugin_name;
	private $version;
	protected $screen_id;
	public function __construct( $plugin_name, $version ) {
		$this->includes();
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->screen_id = isset($_GET['page'])?$_GET['page']:"";
		add_action( 'admin_menu', array($this,'admin_menu'));
	}

	/**
	 * includes required fils
	 *
	 * @since    1.0.0
	 */
	public function includes() {
		if (!class_exists('Esoftcreator_Migrate_DB_Helper')) {
      require_once(E_SOFT_CREATOR_PLUGIN_DIR . 'admin/partials/helpers/class-esoftcreator-migrate-db-helper.php');
    }
    if (!class_exists('Esoftcreator_Header')) {
      require_once(E_SOFT_CREATOR_PLUGIN_DIR . 'admin/partials/common/class-esoftcreator-header.php');
    }
    if (!class_exists('Esoftcreator_Footer')) {
      require_once(E_SOFT_CREATOR_PLUGIN_DIR . 'admin/partials/common/class-esoftcreator-footer.php');
    }   
  }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(strpos($this->screen_id, 'esc') !== false){			
			if( in_array( $this->screen_id, array('esc-sales-analysis','esc-sales-data-analysis','esc-report-download')) ){	
				wp_enqueue_style('esoftcreator-daterangepicker-css',  E_SOFT_CREATOR_PLUGIN_URL.'/admin/css/daterangepicker.css');
			}			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/esoftcreator-admin.css', array(), $this->version, 'all' );

			if(is_rtl()){
				wp_enqueue_style( $this->plugin_name.'-rtl', plugin_dir_url( __FILE__ ) . 'css/esoftcreator-rtl.css', array(), $this->version, 'all' );
			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(strpos($this->screen_id, 'esc') !== false){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/esoftcreator-admin.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Add Menu for the admin area.
	 * @since    1.0.0
	 */
	public function admin_menu(){
		add_menu_page(
      __('ESC Order Report','e-soft-creator'), __('ESC Order Report','e-soft-creator'), 'manage_options', 'esc-sales-analysis', array($this, 'show_page'), esc_url(E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/icon.png'), 56
  	);
  	add_submenu_page('esc-sales-analysis', __('Sales Analysis','e-soft-creator'), __('Sales Analysis','e-soft-creator'), 'manage_options', 'esc-sales-analysis' );
  	add_submenu_page('esc-sales-analysis', __('Sales Data Analysis','e-soft-creator'), __('Sales Data Analysis','e-soft-creator'), 'manage_options', 'esc-sales-data-analysis', array($this, 'show_page'));
  	add_submenu_page('esc-sales-analysis', __('Sales Report Download','e-soft-creator'), __('Report Download','e-soft-creator'), 'manage_options', 'esc-report-download', array($this, 'show_page'));
  	add_submenu_page('esc-sales-analysis', __('Settings','e-soft-creator'), __('Settings','e-soft-creator'), 'manage_options', 'esc-settings', array($this, 'show_page'));
	}

	/**
	 * Load page for the admin area.
	 * @since    1.0.0
	 */
	public function show_page() {
		do_action('esoftcreator_header');
		$get_action = "e-soft-creator";
   	if(isset($_GET['page'])) {
      $get_action = str_replace("-", "_", $_GET['page']);
    }
    if(method_exists($this, $get_action)){
      $this->$get_action();
    }
    do_action('esoftcreator_footer');
  }

  /**
	 * Load dashboard page for the admin area.	 *
	 * @since    1.0.0
	 */
  public function esc_sales_analysis(){
  	require_once( 'partials/pages/class-esoftcreator-sales-analysis.php');
  	new Esoftcreator_Sales_Analysis();
  	
  }

  /**
	 * Load dashboard page for the admin area.	 *
	 * @since    1.0.0
	 */
  public function esc_sales_data_analysis(){
  	require_once( 'partials/pages/class-esoftcreator-data-analysis.php');
  	new Esoftcreator_Sales_Overview();
  }

  /**
	 * Load dashboard page for the admin area.	 *
	 * @since    1.0.0
	 */
  public function esc_report_download(){
  	require_once( 'partials/pages/class-esoftcreator-reports-download.php');
  	new Esoftcreator_Reports_Download();
  }

  /**
	 * Load Setting page for the admin area.	 *
	 * @since    2.0.0
	 */
  public function esc_settings(){
  	require_once( 'partials/pages/class-esoftcreator-settings.php');
  	new Esoftcreator_Settings();  	
  }

}