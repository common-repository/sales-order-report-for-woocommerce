<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * Sales Order Report for WooCommerce Main Helper
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('Esoftcreator_Helper')):
	class Esoftcreator_Helper {
		protected $currency_symbol;
		public function get_val_from_obj($obj, $key, $prefix = null){
			if(isset($obj[$key]) && $obj[$key]){
				return esc_attr($prefix.$obj[$key]);
			}else{
				return esc_attr($prefix."0");
			}
		}

		public function get_woocommerce_currency_symbol(){
			if(!empty($this->user_currency_symbol)){
				return $this->user_currency_symbol;
			}else{
				$code = get_woocommerce_currency();
				return get_woocommerce_currency_symbol($code);
			}
		}

		public function set_cookie($key, $value){
			setcookie($key, $value, strtotime('+1 day'));
		}

		public function delete_cookie($key){
			if (isset($_COOKIE[$key])) {
			  unset($_COOKIE[$key]); 
			  setcookie($key, "", strtotime('-1 day'));
			}
		}
		/**
     * Chart Attributes
     *
     * @since    1.3.0
     */
		public function get_ChartAttributes() {
	    $chart_attr = [	    
				"total_sale"=>[
					"id"=>"total_sale",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"line",
						"chart_title"=>__("Total sales - Net Sales","e-soft-creator"),
						"chart_id"=>"total_sale_chart",
						"tension"=> "0.4",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Total sales","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"order_total",
								"borderColor"=> "#878743"
							],
							"1"=>[
								"label"=>__("Net Sales","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"line_subtotal",
								"borderColor"=> "#8BBFEC"
							]
						]
					]
				],"net_sale"=>[
					"id"=>"net_sale",
					"type"=>"currency",
				],"total_orders"=>[
					"id"=>"total_orders",
					"type"=>"number",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Total Order","e-soft-creator"),
						"chart_id"=>"total_orders_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Total Order","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"total_orders"
							]
						]
					]
				],"average_order_value"=>[
					"id"=>"average_order_value",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"line",
						"chart_title"=>__("Average order value","e-soft-creator"),
						"chart_id"=>"average_order_value_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Average order value","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"average_order_value"
							]
						]
					]
				],"refund_order"=>[
					"id"=>"refund_order",
					"type"=>"number",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Refund orders","e-soft-creator"),
						"chart_id"=>"refund_order_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Refund orders","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"refund_order"
							]
						]
					]
				],"refund_order_value"=>[
					"id"=>"refund_order_value",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"line",
						"chart_title"=>__("Refund","e-soft-creator"),
						"chart_id"=>"refund_order_value_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Refund","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"refund_order_value"
							]
						]
					]
				],"discount_amount"=>[
					"id"=>"discount_amount",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Discount","e-soft-creator"),
						"chart_id"=>"discount_amount_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Discount","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"discount_amount"
							]
						]
					]
				],"total_tax"=>[
					"id"=>"total_tax",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"line",
						"chart_title"=>__("Total TAX","e-soft-creator"),
						"chart_id"=>"total_tax_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Total TAX","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"total_tax"
							]
						]
					]
				],"order_tax"=>[
					"id"=>"order_tax",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Order TAX","e-soft-creator"),
						"chart_id"=>"order_tax_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("TAX","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"order_tax"
							]
						]
					]
				],"shipping_tax"=>[
					"id"=>"shipping_tax",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Shipping TAX","e-soft-creator"),
						"chart_id"=>"shipping_tax_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Shipping TAX","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"shipping_tax"
							]
						]
					]
				],"shipping"=>[
					"id"=>"shipping",
					"type"=>"currency",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"line",
						"chart_title"=>__("Shipping","e-soft-creator"),
						"chart_id"=>"shipping_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Shipping","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"shipping"
							]
						]
					]
				],"total_users"=>[
					"id"=>"total_users",
					"type"=>"number",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"bar",
						"chart_title"=>__("Total users - Unique users","e-soft-creator"),
						"chart_id"=>"total_users_chart",
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Total users","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"total_users",
								"borderColor"=> "#0080F7",
								"backgroundColor"=> "#0080F7"
							],
							"1"=>[
								"label"=>__("Unique users","e-soft-creator"),
								"dimensions"=>"order_date",
								"metrics"=>"unique_users",
								"borderColor"=> "#8BBFEC",
								"backgroundColor"=> "#8BBFEC"
							]
						]
					]
				],"unique_users"=>[
					"id"=>"unique_users",
					"type"=>"number",
				],"order_status"=>[
					"id"=>"total_orders",
					"type"=>"number",
					"is_chart"=>true,
					"chart_info"=>[
						"chart_type"=>"pie",
						"chart_title"=>__("Order status","e-soft-creator"),
						"chart_id"=>"order_status_chart",
						"backgroundColor" =>array('#FF6384','#22CFCF','#0ea50b','#FF9F40','#FFCD56'),
						"chart_metrics"=>[
							"0"=>[
								"label"=>__("Order status","e-soft-creator"),
								"dimensions"=>"order_status",
								"borderColor"=> "#f3f3f3",
								"metrics"=>"total_orders"
							]
						]
					]
				]
			
	    ];	    
	    return (!empty($chart_attr)) ? json_encode($chart_attr) : "";
	  }

	}
endif; // class_exists