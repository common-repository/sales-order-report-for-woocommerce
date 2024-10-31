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
if(!class_exists('Esoftcreator_Ajax_Helper')):	
	require_once( 'class-esoftcreator-helper.php');	
	class Esoftcreator_Ajax_Helper extends Esoftcreator_Helper{
		protected $Esoftcreator_DB_Helper;
		public function __construct(){
			$this->req_int();
			$this->Esoftcreator_DB_Helper = new Esoftcreator_DB_Helper();
			add_action('wp_ajax_get_sales_reports_download', array($this,'get_sales_reports_download') );
			add_action('wp_ajax_get_sales_reports_view', array($this,'get_sales_reports_view') );
			add_action('wp_ajax_get_sales_reports_overview', array($this,'get_sales_reports_overview') );
			add_action('wp_ajax_get_sales_report_analysis', array($this,'get_sales_report_analysis') );
		}

		public function req_int(){
			require_once( 'class-esoftcreator-db-helper.php');
		}
		protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
			// only return results when the user is an admin with manage options
			if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Ajax code for show wc order data.
		 * @since    1.0.0
		 */
		public function get_sales_report_analysis(){
			$ajax_nonce = (isset($_POST['esc_ajax_nonce']))?$_POST['esc_ajax_nonce']:"";
			if($this->admin_safe_ajax_call($ajax_nonce, 'sales_report_analysis_nonce')){	
				$start_date = (isset($_POST['start_date']))?$_POST['start_date']:"";
				$start_date = sanitize_text_field($start_date);
				if($start_date != ""){
					$date = DateTime::createFromFormat('F-d-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !==strtotime( $start_date ))?date('Y-m-d', strtotime($start_date)):date( 'Y-m-d', strtotime( '-1 month' ));

				$end_date = (isset($_POST['end_date']))?$_POST['end_date']:"";
				$end_date = sanitize_text_field($end_date);
				if($end_date != ""){
					$date = DateTime::createFromFormat('F-d-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !==strtotime( $end_date ))?date('Y-m-d', strtotime($end_date)):date( 'Y-m-d', strtotime( 'now' ));

			  if($start_date && $end_date){
			    $data = $this->Esoftcreator_DB_Helper->get_sales_report_analysis($start_date, $end_date);
      		$data['currency'] = $this->get_woocommerce_currency_symbol();      		
			     echo wp_send_json(array("error"=>false, 'data'=>$data));
          exit;
			  }
			}else{
        echo wp_send_json(array("error"=>true, 'message'=> __("Your admin nonce is not valid.","e-soft-creator")));
				exit;
			}
		}
		/**
		 * Ajax code for show wc order data.
		 * @since    1.0.0
		 */
		public function get_sales_reports_overview(){
			$ajax_nonce = (isset($_POST['esc_ajax_nonce']))?$_POST['esc_ajax_nonce']:"";
			if($this->admin_safe_ajax_call($ajax_nonce, 'sales_reports_overview_nonce')){	
				
			  $start_date = (isset($_POST['start_date']))?$_POST['start_date']:"";
			  $start_date = sanitize_text_field($start_date);
        if($start_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $start_date);
          $start_date = $date->format('Y-m-d');
        }
        $start_date == (false !==strtotime( $start_date ))?date('Y-m-d', strtotime($start_date)):date( 'Y-m-d', strtotime( '-1 month' ));

        $end_date = (isset($_POST['end_date']))?$_POST['end_date']:"";
        $end_date = sanitize_text_field($end_date);
        if($end_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $end_date);
          $end_date = $date->format('Y-m-d');
        }
        $end_date == (false !==strtotime( $end_date ))?date('Y-m-d', strtotime($end_date)):date( 'Y-m-d', strtotime( 'now' ));

			  if($start_date && $end_date){
			    $data = $this->Esoftcreator_DB_Helper->get_sales_reports_overview($start_date, $end_date);
          $data['currency'] = $this->get_woocommerce_currency_symbol();
          $data['cart_abandoned'] = $this->Esoftcreator_DB_Helper->get_cart_abandoned($start_date, $end_date);
			    echo wp_send_json(array("error"=>false, 'data'=>$data));
			    exit;
			  }
			}else{
				echo wp_send_json(array("error"=>true, 'message'=> __("Your admin nonce is not valid.","e-soft-creator")));
				exit;
			}
		}

		/**
		 * Ajax code for view sales data.
		 * @since    1.1.0
		 */
		public function get_sales_reports_view(){
			$ajax_nonce = (isset($_POST['esc_ajax_nonce']))?$_POST['esc_ajax_nonce']:"";
			
			if($this->admin_safe_ajax_call($ajax_nonce, 'sales_reports_view_nonce')){
				global $wpdb;
			   
			  $start_date = (isset($_POST['start_date']))?$_POST['start_date']:"";
			  $start_date = sanitize_text_field($start_date);
        if($start_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $start_date);
          $start_date = $date->format('Y-m-d');
        }
        $start_date == (false !==strtotime( $start_date ))?date('Y-m-d', strtotime($start_date)):date( 'Y-m-d', strtotime( '-1 month' ));

        $end_date = (isset($_POST['end_date']))?$_POST['end_date']:"";
        $end_date = sanitize_text_field($end_date);
        if($end_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $end_date);
          $end_date = $date->format('Y-m-d');
        }
        $end_date == (false !==strtotime( $end_date ))?date('Y-m-d', strtotime($end_date)):date( 'Y-m-d', strtotime( 'now' ));

			  $html ="";
			  $data = array();
			  if($start_date && $end_date){
			    $results = $this->Esoftcreator_DB_Helper->get_order_data($start_date, $end_date);
			    if(!empty($results)){
			    	$html ='<table id="order-data-rs" class="table table-striped table-bordered dataTable display" style="width:100%"><thead><tr> <th>'.__('Relevant ID','e-soft-creator').'</th> <th>'.__('Date','e-soft-creator').'</th> <th>'.__('Row Type','e-soft-creator').'</th> <th class="td-align-left">'.__('Item Name','e-soft-creator').'</th> <th>'.__('Qty','e-soft-creator').'</th> <th>'.__('Amount','e-soft-creator').'</th> <th>'.__('Shipping','e-soft-creator').'</th> <th>'.__('Discount','e-soft-creator').'</th> <th>'.__('Taxes','e-soft-creator').'</th> <th>'.__('Returns','e-soft-creator').'</th> <th>'.__('Total','e-soft-creator').'</th> </tr></thead><tbody>';
			      foreach ($results as $key_order => $row_order){
			        if(!empty($row_order)){
			          $order_id =$row_order['order_id'];
			          $order_total =$row_order['order_total'];
			          $order_date =$row_order['order_date'];
			          $order_status =$row_order['order_status'];
			          $user_id =$row_order['user_id'];
			          $billing_email =$row_order['billing_email'];
			          $order_tax =$row_order['order_tax'];

			          $sub_total =0;
			          $line_tax =0;
			          $qty =0;
			          $shipping = 0;
			          $discount_amount = 0;
			          $tax = 0;
			          $refund_amount = 0;
			          $is_addecount_row =0;
			          foreach ($row_order['order_item_type_data'] as $key => $row){
			          	//print_r($row);
			            if($row['order_item_type'] == 'line_item'){
			              $sub_total+= $row["line_subtotal"];
			              $line_tax+= $row["line_tax"];
			              $qty+= $row["line_qty"];
			              $tax+= $row["line_tax"];

			              $html.='<tr><td colspan=""><a href="'.get_admin_url().'post.php?post='.$row["line_product_id"].'&action=edit" target="_blank">'.$row["line_product_id"].'</a></td> <td></td> <td>product</td> <td class="td-align-left">'.$row["order_item_name"].'<br>SKU: '.$row["prod_sku"].'</td> <td>'.$row["line_qty"].'</td> <td>'.$row["line_subtotal"].'</td> <td></td> <td></td> <td>'.$row["line_tax"].'</td> <td></td> <td>'.$row["line_subtotal"].'</td></tr>';
			            }else if($row['order_item_type'] == 'shipping'){
			              $shipping = $row["shipping"];
			              $html.='<tr><td></td> <td></td> <td>shipping</td> <td class="td-align-left">'.$row["shipping_name"].'</td> <td></td> <td></td> <td>'.$row["shipping"].'</td> <td></td> <td>'.$row["order_shipping_tax"].'</td> <td></td> <td>'.$row["shipping"].'</td></tr>';
			            }else if($row['order_item_type'] == 'coupon'){
			              $coupon_data = maybe_unserialize($row['discount_coupon_data']);
			              $discount_amount = $row["discount_amount"];
			              $html.='<tr><td></td> <td></td> <td>Discount</td> <td class="td-align-left">Code: '.$coupon_data["code"].'</td> <td></td> <td></td> <td></td> <td>-'.$row["discount_amount"].'</td> <td></td> <td></td> <td>-'.$row["discount_amount"].'</td></tr>';
			            }
			          }
			          if($row_order['refund_amount'] > 0){            
			            $f_total= $order_total - $row_order["refund_amount"];
			            $html.='<tr class="refund_highlighted"><td>'.$order_id.'</td> <td>'.$order_date.'</td> <td>refund</td> <td class="td-align-left">'.$row_order["refund_reason"].'</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td>-'.$row_order["refund_amount"].'</td> <td>-'.$row_order["refund_amount"].'</td></tr>';

			            $html.='<tr class="highlighted"><td><a href="'.get_admin_url().'post.php?post='.$order_id.'&action=edit" target="_blank">'.$order_id.'</a></td> <td>'.$order_date.'</td> <td>product total</td> <td class="td-align-left">status: '.$order_status.'</td> <td>'.$qty.'</td> <td>'.$sub_total.'</td> <td>'.$shipping.'</td> <td>'.$discount_amount.'</td> <td>'.$tax.'</td> <td>-'.$row_order["refund_amount"].'</td> <td>'.$f_total.'</td></tr>';
			          }else{
			            $html.='<tr class="highlighted"><td><a href="'.get_admin_url().'post.php?post='.$order_id.'&action=edit" target="_blank">'.$order_id.'</a></td> <td>'.$order_date.'</td> <td>email: '.$billing_email.'</td> <td class="td-align-left">status: '.$order_status.'</td> <td>'.$qty.'</td> <td>'.$sub_total.'</td> <td>'.$shipping.'</td> <td>'.$discount_amount.'</td> <td>'.$order_tax.'</td> <td></td> <td>'.$order_total.'</td></tr>';
			          }          

			        }
			      }
			      $html .='<tbody><tfoot><tr> <th>'.__('Relevant ID','e-soft-creator').'</th> <th>'.__('Date','e-soft-creator').'</th> <th>'.__('Row Type','e-soft-creator').'</th> <th class="td-align-left">'.__('Item Name','e-soft-creator').'</th> <th>'.__('Qty','e-soft-creator').'</th> <th>'.__('Amount','e-soft-creator').'</th> <th>'.__('Shipping','e-soft-creator').'</th> <th>'.__('Discount','e-soft-creator').'</th> <th>'.__('Taxes','e-soft-creator').'</th> <th>'.__('Returns','e-soft-creator').'</th> <th>'.__('Total','e-soft-creator').'</th> </tr></tfoot></table>';
			      $data = array('error' => false,'order_result' => $html);
			    }else{
			    	$html = __("No order data is available.","e-soft-creator");
			    	$data = array('error' => true,'message' => $html);
			    }
			  }else{
			    $html = __("Your admin nonce is not validated.","e-soft-creator");
			    $data = array('error' => true,'message' => $html);
			  } 
			  echo wp_send_json($data);
			  wp_die();
			}else{
				echo wp_send_json(array("error"=>true, 'message'=> __("Your admin nonce is not valid.","e-soft-creator")));
			}
		}
		/**
		 * Ajax code for download sales data.
		 * @since    1.0.0
		 */
		public function get_sales_reports_download(){
			$ajax_nonce = (isset($_POST['esc_ajax_nonce']))?$_POST['esc_ajax_nonce']:"";
			
			if($this->admin_safe_ajax_call($ajax_nonce, 'sales_reports_download_nonce')){
				global $wpdb;
			   
			  $start_date = (isset($_POST['start_date']))?$_POST['start_date']:"";
			  $start_date = sanitize_text_field($start_date);
        if($start_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $start_date);
          $start_date = $date->format('Y-m-d');
        }
        $start_date == (false !==strtotime( $start_date ))?date('Y-m-d', strtotime($start_date)):date( 'Y-m-d', strtotime( '-1 month' ));

        $end_date = (isset($_POST['end_date']))?$_POST['end_date']:"";
        $end_date = sanitize_text_field($end_date);
        if($end_date != ""){
          $date = DateTime::createFromFormat('F-d-Y', $end_date);
          $end_date = $date->format('Y-m-d');
        }
        $end_date == (false !==strtotime( $end_date ))?date('Y-m-d', strtotime($end_date)):date( 'Y-m-d', strtotime( 'now' ));

			  $html ="";
			  $file_name = "";
			  $data = array();
			  $woocommerce_order_itemmeta_t = $wpdb->prefix.'woocommerce_order_itemmeta';

			  if($start_date && $end_date){
			    $results = $this->Esoftcreator_DB_Helper->get_order_data($start_date, $end_date);
			    if(!empty($results)){
            $delimiter = ",";
            $current_user = wp_get_current_user(); 
            $file_name = 'e-soft-creator-u-'.$current_user->ID.'.csv';
            if (!file_exists(WP_CONTENT_DIR.'/upgrade/'.E_SOFT_CREATOR_PLUGIN)) {
              mkdir(WP_CONTENT_DIR.'/upgrade/'.E_SOFT_CREATOR_PLUGIN, 0777, true);
            }
            $f = fopen(WP_CONTENT_DIR.'/upgrade/'.E_SOFT_CREATOR_PLUGIN.'/'.$file_name, 'w'); 
            $html ='<table id="order-data-rs" class="table table-striped table-bordered dataTable display" style="width:100%"><thead><tr> <th>'.__('Order ID','e-soft-creator').'</th> <th>'.__('Date','e-soft-creator').'</th> <th>'.__('Row Type','e-soft-creator').'</th> <th class="td-align-left">'.__('Item Name','e-soft-creator').'</th> <th>'.__('Qty','e-soft-creator').'</th> <th>'.__('Amount','e-soft-creator').'</th> <th>'.__('Shipping','e-soft-creator').'</th> <th>'.__('Discount','e-soft-creator').'</th> <th>'.__('Taxes','e-soft-creator').'</th> <th>'.__('Returns','e-soft-creator').'</th> <th>'.__('Total','e-soft-creator').'</th> </tr></thead><tbody>';

            //csv
            $fields = array('Order ID', 'Date', 'Row Type', 'Item Name', 'SKU', 'Qty', 'Amount', 'Shipping', 'Discount','Taxes','Refund','Total'); 
            fputcsv($f, $fields, $delimiter);
			      
			      foreach ($results as $key_order => $row_order){
			        if(!empty($row_order)){
			          $order_id =$row_order['order_id'];
			          $order_total =$row_order['order_total'];
			          $order_date =$row_order['order_date'];
			          $order_status =$row_order['order_status'];
			          $user_id =($row_order['user_id'] != 0)?$row_order['user_id']:'Guest';
			          $billing_email =$row_order['billing_email'];
			          $order_tax =$row_order['order_tax'];

			          $sub_total =0;
			          $line_tax =0;
			          $qty =0;
			          $shipping = 0;
			          $discount_amount = 0;
			          $tax = 0;
			          
			          $order_row = 0;
			          foreach ($row_order['order_item_type_data'] as $key => $row){     
			            if($row["order_item_type"] == "line_item"){
			              $variation_description ="";	              
			              if($row["prod_sku"] != ""){
			               	$sql =  $wpdb->prepare("SELECT meta_key, meta_value FROM ".$woocommerce_order_itemmeta_t." WHERE  order_item_id = %d and meta_key NOT IN ('_product_id','_variation_id','_qty','_tax_class','_line_subtotal','_line_subtotal_tax','_line_total','_line_tax','_line_tax_data','_fly_woo_discount_price_rules')",$row["order_item_id"]);
			                $o_results = $wpdb->get_results($sql, ARRAY_A);
			                if(!empty($o_results)){
			                  foreach ($o_results as $o_key => $o_row){
			                    $o_row['meta_key'] = str_replace('choose-your-', '', $o_row['meta_key']);
			                    if($variation_description ==""){
			                      $variation_description=str_replace('choose-the-', '', $o_row['meta_key']).':'.$o_row['meta_value'];
			                    }else{
			                      $variation_description.=', '.str_replace('choose-the-', '', $o_row['meta_key']).':'.$o_row['meta_value'];
			                    }                    
			                  }
			                }
			              }

			              $sub_total+= $row["line_subtotal"];  
			              $line_tax+= $row["line_tax"];
			              $qty+= $row["line_qty"];
			              $tax+= $row["line_tax"];
			              $html.='<tr><td>'.esc_attr($order_id).'</td> <td>'.esc_attr($order_date).'</td> <td>product item</td> <td class="td-align-left">'.esc_attr($row["order_item_name"]).'<br>SKU: '.esc_attr($row["prod_sku"]).'</td> <td>'.esc_attr($row["line_qty"]).'</td> <td>'.esc_attr($row["line_subtotal"]).'</td> <td></td> <td></td> <td>'.esc_attr($row["line_tax"]).'</td> <td></td> <td>'.esc_attr($row["line_subtotal"]).'</td></tr>';
                    //csv
                    $fields = array($order_id, $order_date, 'Product', $row["order_item_name"], $row["prod_sku"], $row["line_qty"], $row["line_subtotal"], '', '',$row["line_tax"],'',$row["line_subtotal"]);
                    fputcsv($f, $fields, $delimiter);             

			            }else if($row["order_item_type"] == "shipping"){
			              $shipping = $row["shipping"];
			              $html.='<tr><td>'.esc_attr($order_id).'</td> <td>'.esc_attr($order_date).'</td> <td>shipping type</td> <td class="td-align-left">'.esc_attr($row["shipping_name"]).'</td> <td></td> <td></td> <td>'.esc_attr($row["shipping"]).'</td> <td></td> <td>'.esc_attr($row["order_shipping_tax"]).'</td> <td></td> <td>'.esc_attr($row["shipping"]).'</td></tr>';

                    //csv
                    $fields = array($order_id, $order_date, 'Shipping', $row["shipping_name"], '', '', '', '', $row["shipping"],$row["order_shipping_tax"],'',$row["shipping"]);
                    fputcsv($f, $fields, $delimiter);

			            }else if($row["order_item_type"] == "coupon"){
			              $coupon_data = maybe_unserialize($row['discount_coupon_data']);
			              $discount_amount = $row["discount_amount"];
			              $html.='<tr><td>'.esc_attr($order_id).'</td> <td>'.esc_attr($order_date).'</td> <td>discount</td> <td class="td-align-left">Code: '.esc_attr($coupon_data["code"]).'</td> <td></td> <td></td> <td></td> <td>-'.esc_attr($row["discount_amount"]).'</td> <td></td> <td></td> <td>-'.esc_attr($row["discount_amount"]).'</td></tr>';

                    //csv
                    $fields = array($order_id, $order_date, 'Discount', $coupon_data["code"], '', '', '', '', $row["discount_amount"],'','',$row["discount_amount"]);
                    fputcsv($f, $fields, $delimiter);
			            }
			            //echo $row["order_item_type"]."-".$e_row;
			            if($row["order_item_type"] != "tax"){
			            	$e_row++;
			            	$order_row++;
			            }
			          }
			          if($row_order['refund_amount'] > 0){

			            $html.='<tr class="refund_highlighted"><td>'.esc_attr($order_id).'</td> <td>'.esc_attr($order_date).'</td> <td>Refund</td> <td class="td-align-left">'.esc_attr($row_order["refund_reason"]).'</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td>-'.esc_attr($row_order["refund_amount"]).'</td> <td>-'.esc_attr($row_order["refund_amount"]).'</td></tr>';

			            //csv
                    $fields = array($order_id, $order_date, 'Refund', $row_order["refund_reason"], '', '', '', '', '','',$row_order["refund_amount"],$row["refund_amount"]);
                    fputcsv($f, $fields, $delimiter);
			            $order_row++;
			          }
			        }
			      }
			      
			      $current_user = wp_get_current_user();			    
			      
			      //$writer->save(WP_CONTENT_DIR.'/upgrade/'.E_SOFT_CREATOR_PLUGIN.'/'.$file_name);

			      $html .='<tbody><tfoot><tr> <th>'.__('Order ID','e-soft-creator').'</th> <th>'.__('Date','e-soft-creator').'</th> <th>'.__('Row Type','e-soft-creator').'</th> <th class="td-align-left">'.__('Item Name','e-soft-creator').'</th> <th>'.__('Qty','e-soft-creator').'</th> <th>'.__('Amount','e-soft-creator').'</th> <th>'.__('Shipping','e-soft-creator').'</th> <th>'.__('Discount','e-soft-creator').'</th> <th>'.__('Taxes','e-soft-creator').'</th> <th>'.__('Returns','e-soft-creator').'</th> <th>'.__('Total','e-soft-creator').'</th> </tr></tfoot></table>';
				    $file_url = content_url().'/upgrade/'.E_SOFT_CREATOR_PLUGIN.'/'.$file_name;
				    $file_name= 'e-soft-creator-date-'.sanitize_title(date("M-d-Y")).'.csv';
				    $file_name = sanitize_file_name($file_name);
				    $data = array('error' => false,'order_result' => $html,'file_url'=>$file_url,'file_name'=>$file_name);
			    }else{
			    	$html = __("No order data is available.","e-soft-creator");
					  $data = array('error' => true,'message' => $html);
			    }	    
			  }else{
			    $html = __("Your admin nonce is not validated.","e-soft-creator");
			    $data = array('error' => true,'message' => $html);
			  }
			}else{
				echo wp_send_json(array("error"=>true, 'message'=> __("Your admin nonce is not valid.","e-soft-creator")));
				exit;
			}
		  echo wp_send_json($data);
		  wp_die();	  
		}
	}
endif; // class_exists
new Esoftcreator_Ajax_Helper();