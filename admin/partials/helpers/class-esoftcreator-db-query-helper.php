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
if(!class_exists('Esoftcreator_DB_Query_Helper')){
	class Esoftcreator_DB_Query_Helper{
		public function __construct(  ) {
			
		}	

		public function esc_add_row($table, $t_data, $format){
			if($table =="" || $t_data == ""){
				return;
			}else{
				global $wpdb;
				$tablename = $wpdb->prefix .$table;
				$wpdb->insert($tablename, $t_data);
				return $wpdb->insert_id;
				//echo $wpdb->last_query;
				//echo $wpdb->show_errors;
			}
		}

		public function esc_update_row($table, $t_data, $where){
			if($table =="" || $t_data == "" ||  $where == ""){
				return;
			}else{
				global $wpdb;
				$tablename = $wpdb->prefix .$table;
				$wpdb->update($tablename, $t_data, $where);
				//echo $wpdb->last_query;
				//echo $wpdb->show_errors;
			}
		}

		public function esc_check_row($table, $where){
			global $wpdb;
			$tablename = $wpdb->prefix .$table;
			if($table =="" ||  $where == ""){
				return;
			}else{
				$sql = "select count(*) from ".$tablename." where ".$where;
				return $wpdb->get_var($sql);
			}
		}


		public function esc_get_results($table, $where = null, $fields = array()){
			global $wpdb;
			$tablename = $wpdb->prefix .$table;
			if($table =="" ){
				return;
			}else {
				$p_where ="";
				if($where != ""){
					$where = " where ".$where;
				}
				if( !empty($fields) )	{			
					$fields = implode(',', $fields);
					$sql = "select ".$fields." from ".$tablename." ".$where;
					return $wpdb->get_results($sql);
				}else{
					$sql = "select * from ".$tablename." ".$where;
					return $wpdb->get_results($sql);
				}							
			}
		}

		public function esc_get_row($table, $fields=null, $where =null){
			if($table ==""){
				return;
			}else{
				global $wpdb;
				$tablename = $wpdb->prefix .$table;
				if($where != ""){
					$where = " where ".$where;
				}
				$sql = "select * from ".$tablename.$where;
				if($fields){
					$fields = implode(',', $fields);
					$sql = "select ".$fields." from ".$tablename.$where;
				}
				return $wpdb->get_row($sql,ARRAY_A);
			}
		}

		public function esc_get_last_row($table, $fields=null){
			if($table ==""){
				return;
			}else{
				global $wpdb;
				$tablename = $wpdb->prefix .$table;
				$sql = "select * from ".$tablename." ORDER BY id DESC LIMIT 1";
				if($fields){
					$fields = implode(',', $fields);
					$sql = "select ".$fields." from ".$tablename." ORDER BY id DESC LIMIT 1";
				}
				
				return $wpdb->get_row($sql,ARRAY_A);
			}
		}

		public function esc_get_counts_groupby($table, $fields_by){
			global $wpdb;
			$tablename = $wpdb->prefix .$table;
			if($table =="" ||  $fields_by == ""){
				return;
			}else{
				$sql = "select ".$fields_by.", count(*) as count from ".$tablename." GROUP BY ".$fields_by." ORDER BY count DESC ";
				return $wpdb->get_results($sql, ARRAY_A);
			}
		}	


	}
}