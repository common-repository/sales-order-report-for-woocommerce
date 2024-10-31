<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * Sales Order Report for WooCommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('Esoftcreator_Migrate_DB_Helper')){
	class Esoftcreator_Migrate_DB_Helper{
		public function __construct(  ) {
			$this->includes();
			$this->migrate_db();
		}	
		public function includes() {
	  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );    
		}
		/**
	   * migrate db
	   * @since    2.0.0
	   */
		public function migrate_db(){
			$migration_db = $this->get_migration_db();
			if(!is_array($migration_db)){
				$migration_db = array();
			}
			$migrate_db_list = $this->migrate_db_list();
			if(!empty($migrate_db_list)){
				foreach ($migrate_db_list as $key => $add_migrate_db) {
					if(!in_array($key, $migration_db)){
						$this->$add_migrate_db();
						$migration_db[$key]=$key;
						$this->set_migration_db($migration_db);
					}
				}
			}
		}
		/**
	   * migrate db list
	   * @since    2.0.0
	   */
		public function migrate_db_list(){
			return array(
				"v_2_0_0" =>"add_migrate_db_v_2_0_0"
			);
		}
		/**
     * Add migration for add table for track to abandoned cart and other user behaviour 
     * @since    2.0.0
     */
		public function add_migrate_db_v_2_0_0(){
			global $wpdb;
			/*** add table esc_user_behavior ***/
			$tablename = $wpdb->prefix ."esc_user_behavior";
      $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tablename ) );   
      if ( $wpdb->get_var( $query ) === $tablename ) {          
      }else{     
        $sql_create = "CREATE TABLE ".$tablename." ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT , `woo_cart_id` BIGINT(20) NOT NULL , `user_id` INT(20) NOT NULL, `user_type` VARCHAR(30) NULL, `ip_address` VARCHAR(30) NULL, `user_country` VARCHAR(30) NULL, `user_state` VARCHAR(30) NULL, `billing_country` VARCHAR(30) NULL, `billing_state` VARCHAR(30) NULL, `currency` VARCHAR(5) NULL,`sub_total` FLOAT(10) NULL, `order_total` FLOAT(10) NULL, `order_quantity` INT(5) NULL, `view_cart` INT(1) NULL DEFAULT 0, `checkout_step_1` INT(1) NULL DEFAULT 0, `checkout_step_2` INT(1) NULL DEFAULT 0, `checkout_step_3` INT(1) NULL DEFAULT 0, `purchase` INT(1) NULL DEFAULT 0, `purchase_at` DATE NULL, `last_event` VARCHAR(10) NULL, `week_day` VARCHAR(10) NULL, `user_data` LONGTEXT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`) );";         
        if(maybe_create_table( $tablename, $sql_create )){
        }
      }
      /*** add table esc_user_cart_event ***/
      $tablename = $wpdb->prefix ."esc_user_cart_event";
      $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tablename ) );   
      if ( $wpdb->get_var( $query ) === $tablename ) {          
      }else{     
        $sql_create = "CREATE TABLE ".$tablename." ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT, `esc_user_behavior_id` BIGINT(20) NOT NULL, `woo_cart_id` INT(20) NOT NULL, `product_id` INT(20) NOT NULL, `variation_id` INT(20) NULL, `item_quantity` INT(5) NULL,`item_price` INT(10) NULL, `line_total` FLOAT(10) NULL, `add_cart_page` VARCHAR(30) NULL, `add_cart_page_type` VARCHAR(10) NULL,  `remove_cart` INT(1) NULL, `event_data` LONGTEXT NULL, `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`) );";         
        if(maybe_create_table( $tablename, $sql_create )){
        }
      }
		}

		/*
	   * set migrate data in DB
	   */
		public function set_migration_db($esc_migration_db){
			update_option("esc_migration_db", serialize($esc_migration_db));
		}
		/*
	   * get migrate data from DB
	   */
		public function get_migration_db(){
			return unserialize(get_option('esc_migration_db'));			
		}
	}
}
new Esoftcreator_Migrate_DB_Helper();