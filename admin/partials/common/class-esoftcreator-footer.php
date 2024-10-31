<?php
/**
 * @since      1.1.0
 * Description: Footer Section for Sales Order Report for WooCommerce
 */
if ( ! class_exists( 'Esoftcreator_Footer' ) ) {
	class Esoftcreator_Footer {	
		public function __construct( ){
			add_action('ssoftcreator_order_footer',array($this, 'before_end_footer'));
		}	
		public function before_end_footer(){ 
			?>
				</div>
			<?php
		}
	}
}
new Esoftcreator_Footer();