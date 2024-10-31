<?php
/**
 * @since      1.1.0
 * Description: Header Section for Sales Order Report for WooCommerce
 */
if ( ! class_exists( 'Esoftcreator_Header' ) ) {
	class Esoftcreator_Header extends Esoftcreator_Helper{
		public function __construct( ){
			$this->site_url = "admin.php?page=";			
			add_action('esoftcreator_header',array($this, 'before_start_header'));
			add_action('esoftcreator_header',array($this, 'header_notices'));
			add_action('esoftcreator_header',array($this, 'page_header'));
			add_action('esoftcreator_header',array($this, 'header_menu'));
		}	
		
		/**
     * before start header section
     *
     * @since    1.1.0
     */
		public function before_start_header(){
			?>
			<div class="wrap esoftcreator-sales-report-start">
			<?php
		}
		/**
     * header notices section
     *
     * @since    1.1.0
     */
		public function header_notices(){
			
		}
		/**
     * header section
     *
     * @since    1.1.0
     */
		public function page_header(){			
			
		}

		/* add active tab class */
	  protected function is_active_menu($page=""){
	      if($page!="" && isset($_GET['page']) && $_GET['page'] == $page){
	          return "active";
	      }
	      return ;
	  }
	  /**
     * header section
     *
     * @since    1.1.0
     */
	  public function menu_list(){
	  	//slug => arra();
	  	$menu_list = array(
	  		'esc-sales-analysis' => array(
	  			'title'=>'Sales Analysis',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'esc-sales-data-analysis'=>array(
	  			'title'=>'Sales Data Analysis',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'esc-report-download'=>array(
	  			'title'=>'Report Download',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		)/*,'esc-settings'=>array(
	  			'title'=>'Settings',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		)*/
	  	);
	  	return apply_filters('wc_order_menu_list', $menu_list, $menu_list);
	  }
		/**
     * header menu section
     *
     * @since    1.1.0
     */
		public function header_menu(){
			$menu_list = $this->menu_list();
			if(!empty($menu_list)){
				?>
				<div class="esoftcreator-menu">
      	  <nav>
            <ul>
						<?php
						foreach ($menu_list as $key => $value) {
							if(isset($value['title']) && $value['title']){
								$is_active = $this->is_active_menu($key);
								$icon = "";
								if(!isset($value['icon']) && !isset($value['acitve_icon'])){
									$icon = E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/'.$key.'-menu.png';					
									if($is_active == 'active'){
										$icon = E_SOFT_CREATOR_PLUGIN_URL.'/admin/images/'.$is_active.'-'.$key.'-menu.png';
									}
								}else{
									$icon = (isset($value['icon']))?$value['icon']:((isset($value['acitve_icon']))?$value['acitve_icon']:"");
									if($is_active == 'active' && isset($value['acitve_icon'])){
										$icon =$value['acitve_icon'];
									}
								}
								?>								
								<li class="<?php echo esc_attr($is_active);  ?>">
		              <a href="<?php echo esc_attr($this->site_url.$key); ?>">
		              	<?php if($icon!=""){?>
		                	<span class="navinfoicon"><img src="<?php echo esc_attr($icon); ?>" /></span>
		              	<?php } ?>
		                <span class="navinfonavtext"><?php echo esc_attr(__($value['title'],'e-soft-creator')); ?></span>
		              </a>
			          </li>
								<?php	
							}
						}?>
						</ul>
					</nav>
				</div>
				<?php
			}
			
		}

	}
}
new Esoftcreator_Header();