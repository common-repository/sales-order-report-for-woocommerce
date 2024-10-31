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
if(!class_exists('Esoftcreator_Sales_Overview')):
	class Esoftcreator_Sales_Overview extends Esoftcreator_Helper{
    protected $Esoftcreator_DB_Helper;
    public function __construct( ) {
      $this->Esoftcreator_DB_Helper = new Esoftcreator_DB_Helper();
      $this->req_int();
      $this->load_html();
    }

    public function req_int(){      
      wp_enqueue_script( 'esoftcreator-moment-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/moment.min.js', array( 'jquery' ) );
      wp_enqueue_script( 'esoftcreator-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/daterangepicker.js', array( 'jquery' ) );
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
          function cb(start, end) {
            var start_date = start.format('MMMM/D/YYYY') || 0,
            end_date = end.format('MMMM/D/YYYY') || 0;
            jQuery('#report_range span.report_range_val').html(start_date+ ' - ' + end_date);

            var data = {
              action:'get_sales_reports_overview',                
              plugin_url:'<?php echo E_SOFT_CREATOR_PLUGIN_URL; ?>',
              start_date :$.trim(start_date.replace(/\//g,"-")),
              end_date :$.trim(end_date.replace(/\//g,"-")),
              esc_ajax_nonce: '<?php echo wp_create_nonce( 'sales_reports_overview_nonce' ); ?>'
            };
            esoftc_helper.get_sales_data_analysis(data);
          }
          jQuery('#report_range').daterangepicker({
              showDropdowns: true,
              opens: (is_rtl)?"left":"right",
              alwaysShowCalendars: true,
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
      <div class="esoftcreator-contener esoftc-sales-rep-wrap">        
        <div class="esoftcreator-layout" id="esoftcreator-sales-report">
          <div class="esoftcreator-main-section">
            <div class="esoftcreator-main-section-header">
              <h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item">
                <?php _e('Woocommerce sales report overview','e-soft-creator'); ?></h2>
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
                  <div class="row row-cols-4">
                    <div class="col" >
                      <div class="card" id="s1_total_sale">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total sales","e-soft-creator"); ?></p>                      
                      </div>
                    </div>
                    <div class="col">
                      <div class="card" id="s1_net_sale">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Net sales","e-soft-creator"); ?></p>                      
                      </div>
                    </div>
                    <div class="col">
                      <div class="card pending" id="s1_total_orders">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total order","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card pending" id="s1_average_order_value">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Average order value","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card approved" id="s1_refund_order">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Refund orders","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card approved" id="s1_refund_order_value">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Refund amount","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved" id="s1_discount_amount">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Discount amount","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved" id="s1_total_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total TAX","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved" id="s1_order_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Order TAX","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved" id="s1_shipping_tax">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Shipping TAX","e-soft-creator"); ?></p>                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved"  id="s1_shipping">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Total shipping","e-soft-creator"); ?></p>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved"  id="s1_wc_on_hold">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("On hold","e-soft-creator"); ?></p>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved"  id="s1_wc_processing">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Processing","e-soft-creator"); ?></p>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved"  id="s1_wc_cancelled">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Cancelled","e-soft-creator"); ?></p>                       
                      </div>
                    </div>
                    <div class="col">
                      <div class="card disapproved"  id="s1_wc_completed">
                        <h3 class="pro-count sales-smry-value">-</h3>
                        <p class="pro-title sales-smry-title"><?php _e("Completed","e-soft-creator"); ?></p>                       
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="esoftcreator-sec order_performance_data_sec" id="esoftc-sales-rep-sec-2">
                <h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item icon icon-cart"><?php _e("Order status wise performance","e-soft-creator"); ?></h2>
                <hr role="presentation">
                <div class="esc-table columns-5">
                  <div class="row-header clearfix">
                    <div class="column">Order status</div>
                    <div class="column">Total sale</div>
                    <div class="column">Net sale</div>
                    <div class="column">Order</div>
                    <div class="column">Quantity</div>
                    <div class="column">Discount</div>                    
                    <div class="column">Refund</div>                    
                    <div class="column">Order TAX</div>
                    <div class="column">Shipping TAX</div>
                    <div class="column">Shipping</div>
                  </div>
                  <div class="esc-table-body">
                    
                  </div>
                  <div class="row-footer clearfix">
                   <div class="column">Order status</div>
                    <div class="column">Total sale</div>
                    <div class="column">Net sale</div>
                    <div class="column">Order</div>
                    <div class="column">Quantity</div>
                    <div class="column">Discount</div>                    
                    <div class="column">Refund</div>                    
                    <div class="column">Order TAX</div>
                    <div class="column">Shipping TAX</div>
                    <div class="column">Shipping</div>
                  </div>
                </div>
              </div>

              <div class="esoftcreator-sec cart_abandoned_data_sec" id="esoftc-sales-rep-sec-3">
                <h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item icon icon-cart"><?php _e("Cart abandoned","e-soft-creator"); ?></h2>
                <hr role="presentation">
                <div class="esc-table columns-5">
                  <div class="row-header clearfix">
                    <div class="column sm-column">ID</div>
                    <div class="column lg-column">Email</div>
                    <div class="column">Customer / IP</div>
                    <div class="column">Customer Type</div>
                    <div class="column lg-column">Order Date</div>                    
                    <div class="column">Day Type</div>                    
                    <div class="column">Order Quantity</div>
                    <div class="column">Order Total</div>
                  </div>
                  <div class="esc-table-body">
                    
                  </div>
                  <div class="row-footer clearfix">
                    <div class="column sm-column">ID</div>
                    <div class="column lg-column">Email</div>
                    <div class="column">Customer / IP</div>
                    <div class="column">Customer Type</div>
                    <div class="column lg-column">Order Date</div>                    
                    <div class="column">Day Type</div>                    
                    <div class="column">Order Quantity</div>
                    <div class="column">Order Total</div>
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