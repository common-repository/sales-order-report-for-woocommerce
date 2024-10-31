<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       
 * @since      2.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @subpackage Sales_Order_Report_For_Woocommerce/public
 */
if(!class_exists('Esoftcreator_Public')){
	class Esoftcreator_Public {
		private $plugin_name;
		private $version;
		protected $esc_options;
		protected $db_obj;
		protected $Esoftcreator_Helper;
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->Esoftcreator_Helper = new Esoftcreator_Helper();
			$this->req_int();
			$this->esc_call_hooks();

			$this->db_obj = new Esoftcreator_DB_Query_Helper();			
			if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	      // Put your plugin code here
	      add_action('woocommerce_init' , function (){
	      	$currency = get_woocommerce_currency();
	      	$current_user = wp_get_current_user();
					$user_id = "";
					$user_data = array();
			    $user_type = "guest_user";
			    if ( isset($current_user->ID) && $current_user->ID != 0 ) {
			      $user_id = $current_user->ID;
			      $user_type = 'register_user';
			      $user_data["user_email"] = (isset($current_user->data->user_email))?$current_user->data->user_email:"";
						$user_data["user_login"] = (isset($current_user->data->user_login))?$current_user->data->user_login:"";
			    }
			    $esc_woo_cart_id = strtotime("now");
			    if (!isset($_COOKIE['esc_woo_cart_id'])){
			      $this->Esoftcreator_Helper->set_cookie('esc_woo_cart_id', $esc_woo_cart_id);
			    }else{
			    	$esc_woo_cart_id = $_COOKIE['esc_woo_cart_id'];
			    }
	      	$this->esc_options = array(
	      		"woo_cart_id"=>$esc_woo_cart_id,
	          "is_admin"=>is_admin(),
	          "ip_address"=>$this->get_client_ip(),
	          "currency"=>$currency,
	          "user_id"=>$user_id,
	          "user_type"=>$user_type,
	          "day_type"=>$this->add_day_type(),
	          "user_data"=>$user_data,
	          "esc_ajax_url"=>admin_url( 'admin-ajax.php' ),
	          "esc_ajax_nonce"=>wp_create_nonce( 'esc_analytics_nonce' )
	      	);

	      	/*?>
	      	<script>
		      	window.addEventListener('load', call_esc_analytics_option,true);
		      	function call_esc_analytics_option(){
		      		esc_js = new ESC_Analytics(<?php echo json_encode($this->esc_options); ?>);
		      	}
	      	</script>
	      	<?php*/
	      });
	    }
		}

		public function req_int(){
			if(!class_exists('Esoftcreator_Public')){
				require_once(E_SOFT_CREATOR_PLUGIN_DIR . 'admin/partials/helpers/class-esoftcreator-db-query-helper.php');
			}
		}
		/**
		 * Call hooks for the public facing user behavior of the site.
		 * @since    2.0.0
		 */
		public function esc_call_hooks(){
			add_action("wp_head", array($this, "enqueue_scripts"));
			/***  Add to cart event  ***/
			// define the woocommerce_add_to_cart callback
			add_action( 'woocommerce_add_to_cart', array($this,'add_to_cart'), 10, 4 );
			//add_action("woocommerce_after_add_to_cart_button", array($this, "add_to_cart"));

			/***  Add to remove cart event  ***/
			add_action( 'woocommerce_remove_cart_item', array($this,'esc_woocommerce_remove_cart_item'), 10, 2 );

			/*** update cart ***/
			add_action( 'woocommerce_after_cart_item_quantity_update', array($this,'esc_woocommerce_after_cart_item_quantity_update'), 10, 3 );
			//add_action("woocommerce_after_cart",array($this, "remove_cart_tracking"));
			/***  Add to checkout steps event  ***/
			add_action("woocommerce_before_checkout_form", array($this, "esc_woocommerce_before_checkout_form"));
			add_action( 'woocommerce_checkout_update_order_review', array($this,'action_woocommerce_checkout_update_order_review') ); 
			
			/***  Add purchase event  ***/
			//add_action("woocommerce_thankyou", array($this, "esc_thankyou_page"));
			add_action("woocommerce_checkout_order_created", array($this, "esc_woocommerce_checkout_order_created"));

			add_action('wp_ajax_update_checkout_data', array($this,'update_checkout_data') );
      add_action("wp_ajax_nopriv_update_checkout_data" , array($this,"update_checkout_data") );

		}
		protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
			// only return results when the user is an admin with manage options
			if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
				return true;
			} else {
				return false;
			}
		}

		public function action_woocommerce_checkout_update_order_review(  ){
			$post_data = isset($_POST['post_data']) ? wp_unslash($_POST['post_data']) : '';
			$post_data = explode('&', $post_data);
			$post = array();
			foreach ($post_data as $k => $value){
        $v = explode('=', urldecode($value));
        $post[$v[0]] = $v[1];
    	}
    	$shipping_methods = isset( $_POST['shipping_method'] ) ? wc_clean( wp_unslash( $_POST['shipping_method'] ) ) : array();
    	if(isset($shipping_methods[0]) && $shipping_methods[0] != "" ){
    		$post['shipping_method'] = $shipping_methods[0];

    		$where = "woo_cart_id = ".$this->esc_options["woo_cart_id"];
				$check_row = $this->db_obj->esc_get_row('esc_user_behavior', "", $where);
				//$this->esc_options['order_user_data']=$post;
				$row = array();
				if(!empty($check_row)){
					$user_data = json_decode($check_row['user_data'], true);
					$user_data['order_user_data'] = $post;
					$row['checkout_step_2']=1;
					if(isset($post['billing_country']) && $post['billing_country']){
						$row['billing_country'] = $post['billing_country'];
					}
					if(isset($post['billing_state']) && $post['billing_state']){
						$row['billing_state'] = $post['billing_state'];
					}
					if(isset($user_data['order_user_data']) && !empty($user_data)){
						$row['user_data'] = json_encode($user_data);
					}

					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
					$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
				}
    	}
    	//$current_shipping_method = WC()->session->get( 'chosen_shipping_methods' );

		}
		public function update_checkout_data(){

			$ajax_nonce = (isset($_POST['esc_ajax_nonce']))?$_POST['esc_ajax_nonce']:"";
			if($this->admin_safe_ajax_call($ajax_nonce, 'esc_analytics_nonce')){				
				$where = "woo_cart_id = ".$this->esc_options["woo_cart_id"];
				$check_row = $this->db_obj->esc_get_row('esc_user_behavior', "", $where);
				if(!empty($check_row)){
					$user_data = json_decode($check_row['user_data'], true);
					$row = array();
					$first_name = (isset($_POST['first_name']))?$_POST['first_name']:"";
					if($first_name!=""){
						$this->esc_options['order_user_data']['billing_first_name']=$first_name;
						$user_data['order_user_data']['billing_first_name']=$first_name;
					}
					$last_name = (isset($_POST['last_name']))?$_POST['last_name']:"";
					if($last_name!=""){
						$this->esc_options['order_user_data']['billing_last_name']=$last_name;
						$user_data['order_user_data']['billing_last_name']=$last_name;
					}
					$billing_email = (isset($_POST['billing_email']))?$_POST['billing_email']:"";
					if($billing_email!=""){
						$this->esc_options['order_user_data']['billing_email']=$billing_email;
						$user_data['order_user_data']['billing_email']=$billing_email;
					}
					if(isset($user_data['order_user_data']) && !empty($user_data)){
						$row['user_data'] = json_encode($user_data);
					}
					//$row['user_data'] = json_encode($this->esc_options);

					$step_temp = "";
					$checkout_step_1 = (isset($_POST['checkout_step_1']))?$_POST['checkout_step_1']:"";
					if($checkout_step_1!=""){
						$row['checkout_step_1']=1;
						$step_temp = "checkout_step_1";
					}
					$checkout_step_2 = (isset($_POST['checkout_step_2']))?$_POST['checkout_step_2']:"";
					if($checkout_step_2!=""){
						$row['checkout_step_2']=1;
						$step_temp = "checkout_step_2";
					}
					$checkout_step_3 = (isset($_POST['checkout_step_3']))?$_POST['checkout_step_3']:"";
					if($checkout_step_3!=""){
						$row['checkout_step_3']=1;
						$step_temp = "checkout_step_3";
					}
					
					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
					$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
					$data = array('error' => false,'checkout_step' => $step_temp);
	    		echo wp_send_json($data);
	    		wp_die();
				}
				
			}else{
				$data = array('error' => true,'checkout_step' => "");
	    		echo wp_send_json($data);
	    		wp_die();
			}			
		}
		/**
			* Check out step
			*/
		public function	esc_woocommerce_before_checkout_form(){
			?>
			<script>
				window.addEventListener('load', call_esc_analytics_option,true);
      	function call_esc_analytics_option(){
      		esc_js = new ESC_Analytics(<?php echo json_encode($this->esc_options); ?>);
      		esc_js.addCheckoutEventBindings();
      	}				
			</script>
			<?php
		}
		public function esc_woocommerce_after_cart_item_quantity_update($cart_item_key, $quantity, $old_quantity){			
			$cart_obj = WC()->cart->get_cart();
			if ( sizeof( $cart_obj ) > 0 ) {
				$sub_total = 0;
				$row_cart_event = array();
				foreach ( $cart_obj as $cart_i_key => $values ) {
					$product_id = (isset($values['product_id']))?$values['product_id']:"";
					$variation_id = (isset($values['variation_id']))?$values['variation_id']:"";
					$quantity = $values['quantity'];
					$price = get_post_meta($values['product_id'] , '_price', true);	
					if($variation_id != "" && $variation_id != 0){
						$price = get_post_meta($variation_id , '_price', true);
					}				
					$sub_total+= number_format($quantity * $price,2);	
					if($cart_item_key == $cart_i_key){
						$where = "woo_cart_id = ".$this->esc_options["woo_cart_id"]." and product_id =".$product_id;
			      if($variation_id != "" && $variation_id != "0"){
			      	$where = $where ." and variation_id=".$variation_id;
			      }
						$check_row = $this->db_obj->esc_get_row('esc_user_cart_event', "", $where);
			      if(!empty($check_row)){				
							$line_total = number_format($quantity * $price,2);

							$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"], "product_id"=>$product_id);
							if($variation_id != "" && $variation_id != "0"){
								$where["variation_id"]= $variation_id;
							}
							$row_cart_event = array(
				        "item_quantity"=>$quantity,
				        "line_total"=>$line_total
				      );
							$this->db_obj->esc_update_row('esc_user_cart_event', $row_cart_event, $where);
						}
					}
				}

				/*esc_user_behavior */
				$row = array(
	        "sub_total"=>$sub_total,
	        "order_quantity"=>WC()->cart->get_cart_contents_count()
	      );
	      $where = "woo_cart_id = ".$this->esc_options["woo_cart_id"];
				$check_row = $this->db_obj->esc_get_row('esc_user_behavior', "", $where);
				//print_r($check_row);
				$esc_user_behavior_id ="";
				if(!empty($check_row)){
					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
					$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
				}
			}
		}
		public function esc_woocommerce_remove_cart_item($cart_item_key, $cart){
			$cart_obj = WC()->cart->get_cart();
			if ( sizeof( $cart_obj ) > 0 ) {
				$sub_total = 0;
				$quantity_total = 0;
				foreach ( $cart_obj as $cart_i_key => $values ) {

					if($cart_item_key == $cart_i_key){
						$product_id = (isset($values['product_id']))?$values['product_id']:"";
						$variation_id = (isset($values['variation_id']))?$values['variation_id']:"";

						$where = "woo_cart_id = ".$this->esc_options["woo_cart_id"]." and product_id =".$product_id;
			      if($variation_id != "" && $variation_id != "0"){
			      	$where = $where ." and variation_id=".$variation_id;
			      }
						$check_row = $this->db_obj->esc_get_row('esc_user_cart_event', "", $where);
			      if(!empty($check_row)){	
							$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"], "product_id"=>$product_id);
							if($variation_id != "" && $variation_id != "0"){
								$where["variation_id"]= $variation_id;
							}
							$row_cart_event = array(
								"remove_cart"=>1
							);
							$this->db_obj->esc_update_row('esc_user_cart_event', $row_cart_event, $where);
						}
					}else{
						$product_id = (isset($values['product_id']))?$values['product_id']:"";
						$variation_id = (isset($values['variation_id']))?$values['variation_id']:"";
						$quantity_total+= $values['quantity'];
						$quantity= $values['quantity'];
						$price = get_post_meta($values['product_id'] , '_price', true);	
						if($variation_id != "" && $variation_id != 0){
							$price = get_post_meta($variation_id , '_price', true);
						}				
						$sub_total+= number_format($quantity * $price,2);
					}
				}

				/*esc_user_behavior */
				$row = array(
	        "sub_total"=>$sub_total,
	        "order_quantity"=>$quantity_total
	      );
	      $where = "woo_cart_id = ".$this->esc_options["woo_cart_id"];
				$check_row = $this->db_obj->esc_get_row('esc_user_behavior', "", $where);
				if(!empty($check_row)){
					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
					$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
				}
			}

		}

		public function esc_woocommerce_checkout_order_created($order){
			$woo_cart_id =$this->esc_options["woo_cart_id"];
			//print_r($order);
			//print_r($order->get_id());

			$order_data = $order->get_data();
			//print_r($order->data['billing']['state']);
			
			$billing_country = (isset($order_data['billing']['country']))?$order_data['billing']['country']:"";
			$billing_state = (isset($order_data['billing']['state']))?$order_data['billing']['state']:"";

			$item_quantity=0; $item_subtotal=0; $item_total=0;
			if(!empty($order->get_items())){
				foreach ($order->get_items() as $item_id => $item ) {
					$item_quantity+= $item->get_quantity();
					$item_subtotal+= $item->get_subtotal();
					$item_total+= $item->get_total();
				}
			}
			$row = array(
    		"order_total"=>$order->data['total'],        
        "sub_total"=>$item_subtotal,
        "order_quantity"=>$item_quantity,
        "billing_country"=>$billing_country,
        "billing_state"=>$billing_state,
        "order_quantity"=>WC()->cart->get_cart_contents_count(),
        "week_day"=>$this->add_day_type(),
        "purchase"=>"1",
        "purchase_at"=>date("Y-m-d H:i", current_time( 'timestamp'))
      );
      $where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
			$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
			$this->Esoftcreator_Helper->delete_cookie('esc_woo_cart_id');
		}
		/**
		 * Add to cart event
		 * @since    2.0.0
		 *//*
		public function add_to_cart1($array, $int, $test){
			?>
	    <script>           
	      window.addEventListener('load', call_esc_analytics,true);
	      function call_esc_analytics(){
	        esc_js = new ESC_Analytics(<?php echo json_encode($this->esc_options); ?>);
	        esc_js.add_to_cart(<?php echo json_encode($array); ?>);
	      }
	    </script>
	    <?php
		}*/
		public function add_to_cart($cart_key, $product_id, $quantity, $variation_id){
			
			$cart_obj = WC()->cart->get_cart();
			if ( sizeof( $cart_obj ) > 0 ) {
				$sub_total = 0;
				$row_cart_event = array();
				foreach ( $cart_obj as $cart_item_key => $values ) {
					$product_id = (isset($values['product_id']))?$values['product_id']:"";
					$variation_id = (isset($values['variation_id']))?$values['variation_id']:"";
					$quantity = $values['quantity'];
					$price = get_post_meta($values['product_id'] , '_price', true);	
					if($variation_id != "" && $variation_id != 0){
						$price = get_post_meta($variation_id , '_price', true);
					}				
					$sub_total+= number_format($quantity * $price,2);					
					if($cart_item_key == $cart_key){
						$line_total = number_format($quantity * $price,2);
						$row_cart_event = array(
			    		"woo_cart_id"=>$this->esc_options["woo_cart_id"],
			        "product_id"=>$product_id,
			        "variation_id"=>$variation_id,
			        "item_quantity"=>$quantity,
			        "item_price"=>$price,
			        "line_total"=>$line_total,
			        "add_cart_page"=>"",
			        "add_cart_page_type"=>"",
			        "remove_cart"=>"0",
			        "event_data"=>""
			      );
					}
					
				}					
	      /*esc_user_behavior */
				$row = array(
	    		"woo_cart_id"=>$this->esc_options["woo_cart_id"],
	        "user_id"=>$this->esc_options["user_id"],
	        "user_type"=>$this->esc_options["user_type"],
	        "ip_address"=>$this->esc_options["ip_address"],
	        "currency"=>$this->esc_options["currency"],
	        "sub_total"=>$sub_total,
	        "purchase"=>"0",
	        "order_quantity"=>WC()->cart->get_cart_contents_count(),
	        "week_day"=>$this->add_day_type(),
	        "user_data"=>json_encode(array("user_data"=>$this->esc_options["user_data"]))
	      );

				$format = array('%d','%d','%s','%s','%s','%d','%d','%s','%s');
				$where = "woo_cart_id = ".$this->esc_options["woo_cart_id"];
				$check_row = $this->db_obj->esc_get_row('esc_user_behavior', "", $where);
				//print_r($check_row);
				$esc_user_behavior_id ="";
				if(!empty($check_row)){
					$esc_user_behavior_id = (isset($check_row['id']))?$check_row['id']:""; 
					unset($row["woo_cart_id"]);
					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"]);
					$this->db_obj->esc_update_row('esc_user_behavior', $row, $where);
				}else{
					$esc_user_behavior_id = $this->db_obj->esc_add_row('esc_user_behavior', $row, $format);
				}
				/*esc_user_behavior */

				/*esc_user_cart_event*/
				$row_cart_event["esc_user_behavior_id"]=$esc_user_behavior_id;
				$format = array('%d','%d','%s','%s','%s','%d','%d','%s','%s');

				$product_id = isset($row_cart_event['product_id'])?$row_cart_event['product_id']:"";
				$variation_id = isset($row_cart_event['variation_id'])?$row_cart_event['variation_id']:"";
	      $where = "woo_cart_id = ".$this->esc_options["woo_cart_id"]." and product_id =".$product_id;	      
	      if($variation_id != "" && $variation_id != "0"){
	      	$where = $where ." and variation_id=".$variation_id;
	      }
				$check_row = $this->db_obj->esc_get_row('esc_user_cart_event', "", $where);
	      if(!empty($check_row)){
					unset($row["woo_cart_id"]);
					$where = array("woo_cart_id" => $this->esc_options["woo_cart_id"], "product_id"=>$product_id);
					if($variation_id != "" && $variation_id != "0"){
						$where["variation_id"]= $variation_id;
					}
					$this->db_obj->esc_update_row('esc_user_cart_event', $row_cart_event, $where);
				}else{
					$this->db_obj->esc_add_row('esc_user_cart_event', $row_cart_event, $format);
				}
				/*esc_user_cart_event*/
							
			}
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 * @since    2.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script("esc-analytics.js", E_SOFT_CREATOR_PLUGIN_URL . '/public/js/esc-analytics.js', array('jquery'), $this->version, false);
		}
		/**
	   * Day type
	   * @since    2.0.0
	   */
		function get_page_type() {
	    if (is_home() || is_front_page()) {
	        $t_page_name = "Home Page";
	    } else if (is_product_category()) {
	        $t_page_name = "Category Pages";
	    } else if (is_product()) {
	        $t_page_name = "Product Pages";
	    } else if (is_cart()) {
	        $t_page_name = "Cart Page";
	    } else if (is_order_received_page()) {
	        $t_page_name = "Thankyou Page";
	    } else if (is_checkout()) {
	        $t_page_name = "Checkout Page";
	    } else if (is_search()) {
	        $t_page_name = "Search Page";
	    } else if (is_shop()) {
	        $t_page_name = "Shop Page";
	    } else if (is_404()) {
	        $t_page_name = "404 Error Pages";
	    } else {
	        $t_page_name = "Others";
	    }
	    return $t_page_name;
	    
	  }
		/**
	   * Day type
	   * @since    2.0.0
	   */
	  function add_day_type() {
	    $date = date("Y-m-d");
	    $day = strtolower(date('l', strtotime($date)));
	    if (($day == "saturday" ) || ($day == "sunday")) {
	        $day_type = "weekend";
	    } else {
	        $day_type = "weekday";
	    }
	    return $day_type;
	  }

	  // Function to get the client IP address
		function get_client_ip() {
		    $ipaddress = '';
		    if (getenv('HTTP_CLIENT_IP'))
		        $ipaddress = getenv('HTTP_CLIENT_IP');
		    else if(getenv('HTTP_X_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    else if(getenv('HTTP_X_FORWARDED'))
		        $ipaddress = getenv('HTTP_X_FORWARDED');
		    else if(getenv('HTTP_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_FORWARDED_FOR');
		    else if(getenv('HTTP_FORWARDED'))
		       $ipaddress = getenv('HTTP_FORWARDED');
		    else if(getenv('REMOTE_ADDR'))
		        $ipaddress = getenv('REMOTE_ADDR');
		    else
		        $ipaddress = 'UNKNOWN';
		    return $ipaddress;
		}
	}
}