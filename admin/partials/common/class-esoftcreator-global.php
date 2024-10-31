<?php
/**
 * @since      1.1.0
 * Description: Header Section for Sales Order Report for WooCommerce
 */
if ( ! class_exists( 'Esoftcreator_Global' ) ) {
	class Esoftcreator_Global extends Esoftcreator_Helper{
		public function __construct( ){
			$this->site_url = "admin.php?page=";			
			//add_action('esoftcreator_header',array($this, 'global_chart_json'));
		}
		
	  /**
     * header section
     *
     * @since    1.1.0
     */
	  public function get_global_chart_json(){	  	
	  	return $this->get_ChartAttributes();
	  	
	  	/*$menu_list = array(
	  		'esc-sales-analysis' => array(
	  			'title'=>'Sales Analysis',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'esc-sales-report'=>array(
	  			'title'=>'Sales Report',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'esc-report-download'=>array(
	  			'title'=>'Report Download',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		)
	  	);
	  	return apply_filters('global_chart_json', $menu_list, $menu_list);*/
	  }
		
	}
}
//new Esoftcreator_Global();