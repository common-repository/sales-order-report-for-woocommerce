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
if(!class_exists('Esoftcreator_Reports_Download')):
	class Esoftcreator_Reports_Download extends Esoftcreator_Helper{
    protected $version;
		public function __construct( ) {
			$this->req_int();
      $this->load_html();
      $this->version = E_SOFT_CREATOR_VERSION;
		}

		public function req_int(){
      wp_enqueue_script( 'esoftcreator-moment-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/moment.min.js', array( 'jquery' ) );
      wp_enqueue_script( 'esoftcreator-daterangepicker-js', E_SOFT_CREATOR_PLUGIN_URL.'/admin/js/daterangepicker.js', array( 'jquery' ) );					
		}

		public function load_html(){			
			$this->current_html();			
		}

		public function current_html(){
      $current = current_time( 'timestamp' );
      $start_date = date( 'M-d-Y', strtotime( '-1 month' ));
      $today_date = date( 'M-d-Y', strtotime( 'now' ));
			?>
			<div class="esoftcreator-contener esoftcreator-reports">				
				<div class="esoftcreator-layout" id="esoftcreator-sales-report">
					<div class="esoftcreator-main-section">
						<div class="esoftcreator-main-section-header">
							<h2 class="esoftcreator-main-section-header__title esoftcreator-main-section-header__header-item">
              <?php _e("Download woocommerce sales report","e-soft-creator"); ?></h2>
							<hr role="presentation">
						
							<div class="worwoocommerce order-date-select">
								<label class="wor-field-title"><?php _e("Sales order date range:","e-soft-creator"); ?></label>
								<div class="report-range-row">
                  <div id="report_range" class="dshtpdaterange" >
                    <div class="dateclndicn">
                      <img src="<?php echo E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/claendar-icon.png'; ?>" alt="" />
                    </div> 
                    <span class="report_range_val"></span>
                    <div class="careticn"><img src="<?php echo E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/caret-down.png'; ?>" alt="" /></div>                  
                  </div> 
                  <button type="button" id="esoftcreator-sales-report-view" class="button button-primary button-large"><?php _e("View sales","e-soft-creator"); ?></button> 
                  <button type="button" id="esoftcreator-sales-report-excel" class="button button-primary button-large"><?php _e("Download CSV","e-soft-creator"); ?></button>
                </div>	
								<div id="wor-date-range-msg"></div>					
							</div>
						</div>
						<div class="esoftcreator-sales-report-section esoftcreator-sec">
							<div class="esoftcreator-sales-report-data" id="esoftcreator-sales-report-data">
							</div>
						</div>						
  				</div>
  			</div>    		
			</div>
			<script type="text/javascript">
    	(function($){
    		jQuery(document).ready(function(){
          //

				  $(function() {
            var start = moment().subtract(30, 'days');
            var end = moment();
            var is_rtl = '<?php echo is_rtl(); ?>';
            function cb(start, end) {
              $('#report_range span.report_range_val').html(start.format('MMMM/D/YYYY') + ' - ' + end.format('MMMM/D/YYYY'));
            }
            $('#report_range').daterangepicker({
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
                   //'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                   'Last Month': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(start, end);

            jQuery("#esoftcreator-sales-report-view").trigger("click");
          }); 
				});
        /**
         *start View Data
         */
        jQuery("#esoftcreator-sales-report").on('click', '#esoftcreator-sales-report-view', function (e) {
            e.preventDefault();
            $('#wor-date-range-msg').html(""); 
            var date_range = $.trim($(".report_range_val").text()).split('-');
            var $thisbutton = $(this),
              $form = $thisbutton.closest('form.wor-date-range'),            
              start_date = $.trim(date_range[0].replace(/\//g,"-")) || 0,
              end_date = $.trim(date_range[1].replace(/\//g,"-")) || 0;

            var data = {
              action: 'get_sales_reports_view',
              start_date: start_date,
              end_date: end_date,
              esc_ajax_nonce: '<?php echo wp_create_nonce( 'sales_reports_view_nonce' ); ?>',          
            };
            /*start ajax*/
          $("#esoftcreator-sales-report-data").html("");
          $.ajax({
            type: 'post',
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            data: data,
            beforeSend: function (response) {
              $thisbutton.addClass('loading'); 
              $thisbutton.prop('disabled', true);                 
            },
            complete: function (response) {              
            },
            success: function (response) {
              if (response.error){
                $('#wor-date-range-msg').html('<div class="error"><p>'+response.message+'</p></div>');
                $thisbutton.removeClass('loading');
                $thisbutton.prop('disabled', false); 
                return false;
              }else if(response.order_result){
                //$("#esoftcreator-sales-report-data").hide();
                $("#esoftcreator-sales-report-data").html(response.order_result);
                $thisbutton.removeClass('loading');
                $thisbutton.prop('disabled', false);
              }              
            },
          });
          /*end ajax*/

        });

        /**
         *start download
         */
        jQuery("#esoftcreator-sales-report").on('click', '#esoftcreator-sales-report-excel', function (e) {
          e.preventDefault();
          $('#wor-date-range-msg').html("");          
          var date_range = $.trim($(".report_range_val").text()).split('-');
          var $thisbutton = $(this),
            $form = $thisbutton.closest('form.wor-date-range'),            
            start_date = $.trim(date_range[0].replace(/\//g,"-")) || 0,
            end_date = $.trim(date_range[1].replace(/\//g,"-")) || 0;

          var data = {
            action: 'get_sales_reports_download',
            start_date: start_date,
            end_date: end_date,
            esc_ajax_nonce: '<?php echo wp_create_nonce( 'sales_reports_download_nonce' ); ?>',          
          };
          /*start ajax*/
          $("#esoftcreator-sales-report-data").html("");
          $.ajax({
            type: 'post',
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            data: data,
            beforeSend: function (response) {
              $thisbutton.addClass('loading'); 
              $thisbutton.prop('disabled', true);                 
            },
            complete: function (response) {              
            },
            success: function (response) {
              if (response.error){
                $('#wor-date-range-msg').html('<div class="error"><p>'+response.message+'</p></div>');
                $thisbutton.removeClass('loading');
                $thisbutton.prop('disabled', false); 
                return false;
              }else if(response.order_result){
                //$("#esoftcreator-sales-report-data").hide();
                $("#esoftcreator-sales-report-data").html(response.order_result);
                $thisbutton.removeClass('loading');
                $thisbutton.prop('disabled', false);           
                if(response.file_url){
                  setTimeout(function(){
                    //window.open(response.file_url,"mywin");
                     var a = document.createElement("a");
                      a.href = response.file_url;                       
                      a.download = response.file_name;
                      document.body.appendChild(a);
                      a.click();
                      a.remove();
                  }, 1000);
                }
              }              
            },
          });
          /*end ajax*/
        });
        /*end excel*/
			})(jQuery);
    	</script>
			<?php
		}
	}
endif; // class_exists