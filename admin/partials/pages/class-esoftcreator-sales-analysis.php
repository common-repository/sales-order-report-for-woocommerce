<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Sales_Order_Report_For_Woocommerce
 * @package    Sales_Order_Report_For_Woocommerce/admin/partials
 * Sales Order Report for WooCommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('Esoftcreator_Sales_Analysis')):
	class Esoftcreator_Sales_Analysis extends Esoftcreator_Helper{
    protected $Esoftcreator_DB_Helper;
		public function __construct( ) {
      $this->Esoftcreator_DB_Helper = new Esoftcreator_DB_Helper();
			$this->req_int();
      $this->load_html();
		}

		public function req_int(){      
      wp_enqueue_script( 'esoftcreator-moment-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/moment.min.js', array( 'jquery' ) );
      wp_enqueue_script( 'esoftcreator-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/daterangepicker.js', array( 'jquery' ) );
      wp_enqueue_script( 'esoftcreator-chart-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/chart.js', array( 'jquery' ) );
      wp_enqueue_script( 'esoftcreator-chart-plugin-table-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/chartjs-plugin-datalabels.js', array( 'jquery' ) );
		}

		public function load_html(){
      $this->current_html();
      $this->current_js();
    }
    /**
     * Page custom js code
     *
     * @since    1.0.0
     */
    public function current_js(){
      ?>
      <script type="text/javascript">
      (function($){
        jQuery(document).ready(function(){
          var start = moment().subtract(30, 'days');
          var end = moment();
          var is_rtl = '<?php echo is_rtl(); ?>';
          var global_chart_json = <?php echo $this->get_ChartAttributes(); ?>;
          function cb(start, end) {
            var start_date = start.format('MMMM/D/YYYY') || 0,
            end_date = end.format('MMMM/D/YYYY') || 0;
            $('#report_range span.report_range_val').html(start_date+ ' - ' + end_date);

            var data = {
              action:'get_sales_report_analysis',                
              plugin_url:'<?php echo E_SOFT_CREATOR_PLUGIN_URL; ?>',
              start_date :$.trim(start_date.replace(/\//g,"-")),
              end_date :$.trim(end_date.replace(/\//g,"-")),
              global_chart_json: global_chart_json,
              esc_ajax_nonce: '<?php echo wp_create_nonce( 'sales_report_analysis_nonce' ); ?>'
            };
            esoftc_helper.get_sales_report_analysis(data);
          }
          jQuery('#report_range').daterangepicker({
              showDropdowns: true,
              alwaysShowCalendars: true,
              opens: (is_rtl)?"left":"right",
              maxSpan: {
                  days: 60
              },
              startDate: start,
              endDate: end,
              maxDate:end,
              ranges: {
                 'Today': [moment(), moment()],
                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                 'This Month': [moment().startOf('month'), moment().endOf('month')],
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                 //'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              }
          }, cb);
          cb(start, end);
         
        });
      })(jQuery);
      </script>
      <?php
    }
    public function current_html(){
      ?>
      <div class="esoftcreator-contener esoftc-sales-analysis-wrap">        
        <div class="esoftcreator-layout" id="esoftcreator-sales-analysis">
          <div class="esoftcreator-main-section">
            <div class="esoftcreator-main-section-header">
              <h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item"><?php _e("Woocommerce sales order analysis","e-soft-creator"); ?></h2>
              <hr role="presentation">            
              <div class="date-range-select">
                <label class="field-title"><?php _e("Sales order date range:","e-soft-creator"); ?></label>
                <div class="report-range-row">
                  <div id="report_range" class="dshtpdaterange" >
                    <div class="dateclndicn">
                      <img src="<?php echo E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/claendar-icon.png'; ?>" alt="" />
                    </div> 
                    <span class="report_range_val"></span>
                    <div class="careticn"><img src="<?php echo E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/caret-down.png'; ?>" alt="" /></div>                  
                  </div>
                </div>
                <div id="wor-date-range-msg"></div>         
              </div>
              <div class="esc-rate-us"><a target="_blanck" href="https://wordpress.org/support/plugin/sales-order-report-for-woocommerce/reviews/"> <img src="<?php echo E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/rate-us.png'; ?>" alt="rate-us" /></a></div>
            </div>
            <div class="esoftcreator-sales-report-dashboard-section">
              <div class="esoftcreator-sec" id="esoftc-sales-rep-sec-1">
                <h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item icon icon-cart"><?php _e("Sales performance","e-soft-creator"); ?></h2>
                <hr role="presentation">
                <div class="product-card"  id="product-card-1">
                  <div class="row row-cols-1">
                    <div class="col" >
                      <div class="card">
                        <div>
                          <div id="s1_total_sale">
                            <h3 class="pro-count sales-smry-value">-</h3>
                            <p class="pro-title sales-smry-title"><?php _e("Total sales","e-soft-creator"); ?></p> 
                          </div>
                          <div id="s1_net_sale">
                            <h3 class="pro-count sales-smry-value">-</h3>
                            <p class="pro-title sales-smry-title"><?php _e("Net sales","e-soft-creator"); ?></p>
                          </div>
                        </div>
                        <div class="total-sale-chart" id="s1_total_sale_chart">
                          <canvas id="total_sale_chart" width="400" height="200"></canvas>
                        </div>                     
                      </div>
                      
                    </div>
                  </div>
                  <div class="row row-cols-2">
                    <div class="col">
                      <div class="card total_orders" id="s1_total_orders">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total order","e-soft-creator"); ?></p>
                        <div class="total-orders-chart" id="s1_total_orders_chart">
                          <canvas id="total_orders_chart" width="400" height="200"></canvas>
                        </div>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card average_order_value" id="s1_average_order_value">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Average order value","e-soft-creator"); ?></p>
                        <div class="average-order-value-chart" id="s1_average_order_value_chart">
                          <canvas id="average_order_value_chart" width="400" height="200"></canvas>
                        </div>                     
                      </div>
                    </div>
                    <div class="col">
                      <div class="card refund-order" id="s1_refund_order">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Refund orders","e-soft-creator"); ?></p>
                        <div class="refund-order-chart" id="s1_refund_order_chart">
                          <canvas id="refund_order_chart" width="400" height="200"></canvas>
                        </div>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card refund_order_value" id="s1_refund_order_value">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Refund amount","e-soft-creator"); ?></p> 
                        <div class="refund-order-value-chart" id="s1_refund_order_value_chart">
                          <canvas id="refund_order_value_chart" width="400" height="200"></canvas>
                        </div>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card discount_amount" id="s1_discount_amount">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Discount amount","e-soft-creator"); ?></p>
                        <div class="discount-amount-chart" id="s1_discount_amount_chart">
                          <canvas id="discount_amount_chart" width="400" height="200"></canvas>
                        </div>                      
                      </div>
                    </div>
                    <div class="col">
                      <div class="card total_tax" id="s1_total_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total TAX","e-soft-creator"); ?></p>
                        <div class="total-tax-chart" id="s1_total_tax_chart">
                          <canvas id="total_tax_chart" width="400" height="200"></canvas>
                        </div>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card order_tax" id="s1_order_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Order TAX","e-soft-creator"); ?></p>
                        <div class="order-tax-chart" id="s1_order_tax_chart">
                          <canvas id="order_tax_chart" width="400" height="200"></canvas>
                        </div>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card shipping_tax" id="s1_shipping_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Shipping TAX","e-soft-creator"); ?></p>
                        <div class="shipping-tax-chart" id="s1_shipping_tax_chart">
                          <canvas id="shipping_tax_chart" width="400" height="200"></canvas>
                        </div>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card shipping"  id="s1_shipping">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total shipping","e-soft-creator"); ?></p>
                        <div class="shipping-chart" id="s1_shipping_chart">
                          <canvas id="shipping_chart" width="400" height="200"></canvas>
                        </div>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card">
                        <div>
                          <div id="s1_total_users">
                            <h3 class="pro-count sales-smry-value">-</h3>
                            <p class="pro-title sales-smry-title"><?php _e("Total users","e-soft-creator"); ?></p>
                          </div>
                          <div id="s1_unique_users">
                            <h3 class="pro-count sales-smry-value">-</h3>
                            <p class="pro-title sales-smry-title"><?php _e("Unique users","e-soft-creator"); ?></p>
                          </div>
                        </div>                        
                        <div class="total-users-chart" id="s1_total_users_chart">
                          <canvas id="total_users_chart" width="400" height="200"></canvas>
                        </div>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card order_status" id="s1_order_status">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Order Status","e-soft-creator"); ?></p>
                        <div class="order-status-chart" id="s1_order_status_chart">
                          <canvas id="order_status_chart" width="400" height="200"></canvas>
                        </div>                        
                      </div>
                    </div>                    

                  </div>
                </div>
              </div>
            </div>            
          </div>
        </div>        
      </div>
      <?php
    }
	}
endif; // class_exists