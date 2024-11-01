<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AutocerfaEnqueue
{
	protected $version = AUTOCERFA_PLUGIN_VERSION;
	public static function init()
	{
		$self = new self;
		add_action('wp_enqueue_scripts', array($self, 'wp_scripts'));
		add_action('admin_enqueue_scripts', array($self, 'enqueue_admin_script' ));
	}


	public function wp_scripts()
	{
//        $this->version = time();
		wp_enqueue_style('autocerfa-fontAwesome', AUTOCERFA_ASSETSURL . "add-on/fontAwesome/css/fontawesome.min.css");
		wp_enqueue_style('autocerfa-fontAwesomeAll', AUTOCERFA_ASSETSURL . "add-on/fontAwesome/css/all.min.css");
//		wp_enqueue_style('awesome-select', AUTOCERFA_ASSETSURL . "add-on/awesome-select/awselect.css");
		wp_enqueue_style('slider-pro', AUTOCERFA_ASSETSURL . "add-on/slider-pro/sliderPro.css", false, '1.0.0');
		wp_enqueue_style('jquery-ui', AUTOCERFA_ASSETSURL . "add-on/jquery-ui/jquery-ui.min.css", false, '1.0.0');
		wp_enqueue_style('owl-carousel', AUTOCERFA_ASSETSURL . "add-on/owl-carousel/owl.carousel.min.css", false, '1.0.0');
		wp_enqueue_style('autocerfa-swiper', AUTOCERFA_ASSETSURL . "add-on/swiper-slider/autocerfa-swiper.min.css", false, '1.0.0');
		// wp_enqueue_style('swiper-slider', AUTOCERFA_ASSETSURL . "add-on/swiper-slider/autocerfa-swiper.css", false, '1.0.0');
		wp_enqueue_style('animate', AUTOCERFA_ASSETSURL . "add-on/owl-carousel/animate.css", false, '1.0.0');
		wp_enqueue_style('bootstrap-css', AUTOCERFA_ASSETSURL . "add-on/bootstrap/bootstrap-wrapper.min.css", false, '1.0.0');
        //wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css');
        //wp_enqueue_style('autocerfa-connector-style', AUTOCERFA_ASSETSURL . "css/style.css", array(), '1.0.1');
		wp_enqueue_style('autocerfa-connector-style', AUTOCERFA_ASSETSURL . "css/style.css", array(), $this->version);
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-slider');

		wp_enqueue_script('owl-carousel', AUTOCERFA_ASSETSURL . "add-on/owl-carousel/owl.carousel.min.js", array(), '1.0.0', true);
		wp_enqueue_script('autocerfa-swiper', AUTOCERFA_ASSETSURL . "add-on/swiper-slider/autocerfa-swiper.min.js", array(), '1.0.0', true);
		// wp_enqueue_script('swiper-slider', AUTOCERFA_ASSETSURL . "add-on/swiper-slider/autocerfa-swiper.js", array(), '1.0.0', true);
		wp_enqueue_script('awselect-js', AUTOCERFA_ASSETSURL . "add-on/awesome-select/awselect.js", array('jquery'), '1.0.0', true);
		wp_enqueue_script('slider-pro', AUTOCERFA_ASSETSURL . "add-on/slider-pro/sliderpro.min.js", array(), '1.0.0', true);
		wp_enqueue_script('popper-js', AUTOCERFA_ASSETSURL . "add-on/bootstrap/popper.min.js", array(), '1.0.0', true);
		wp_enqueue_script('bootstrap-js', AUTOCERFA_ASSETSURL . "add-on/bootstrap/bootstrap.min.js", array('jquery'), '1.0.0', true);
		wp_enqueue_script('autocerfa-connector-script', AUTOCERFA_ASSETSURL . "js/script.js", array(),  $this->version, true);

		wp_localize_script(
			'autocerfa-connector-script',
			'frontend_form_object',
			array(
				'ajaxurl' => admin_url('admin-ajax.php')
			)
		);
	}

	public function enqueue_admin_script()
	{
		wp_enqueue_style('admin-bootstrap-css', AUTOCERFA_ASSETSURL . "add-on/bootstrap/bootstrap-wrapper.min.css", false, '1.0.0');
		wp_enqueue_style('admin-fontAwesome', AUTOCERFA_ASSETSURL . "add-on/fontAwesome/css/fontawesome.min.css");
		wp_enqueue_style('admin-fontAwesomeAll', AUTOCERFA_ASSETSURL . "add-on/fontAwesome/css/all.min.css");
		wp_enqueue_style('sweet-alert', AUTOCERFA_ASSETSURL . "add-on/sweet-alert/sweetalert2.css");
		wp_enqueue_style('admin-chosen', AUTOCERFA_ASSETSURL . "add-on/choosen/chosen.min.css");
		wp_enqueue_style('admin-jquery-ui', AUTOCERFA_ASSETSURL . "add-on/jquery-ui/jquery-ui.min.css", false, '1.0.0');
		wp_enqueue_style('admin-style', AUTOCERFA_ASSETSURL . "css/style-admin.css", array(), $this->version);
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('choosen', AUTOCERFA_ASSETSURL . "add-on/choosen/chosen.jquery.min.js", array(), '1.0.0', true);
		wp_enqueue_script('sweet-alert', AUTOCERFA_ASSETSURL . "add-on/sweet-alert/sweetalert2.js", array(), '6.10.3', true);
		wp_register_script( 'wp-color-picker-alpha-1', AUTOCERFA_ASSETSURL . "add-on/color-picker/wp-color-picker-alpha.min.js", array( 'wp-color-picker' ), '1.0.0', true);
		wp_enqueue_script( 'wp-color-picker-alpha-1');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('admin-popper-js', AUTOCERFA_ASSETSURL . "add-on/bootstrap/popper.min.js", array(), '1.0.0', true);
		wp_enqueue_script('admin-bootstrap-js', AUTOCERFA_ASSETSURL . "add-on/bootstrap/bootstrap.min.js", array(), '1.0.0', true);
		wp_enqueue_script('admin-script', AUTOCERFA_ASSETSURL . "js/admin-script.js", array(), $this->version, true);
		wp_localize_script(
			'admin-script',
			'frontend_form_object',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'autocerfa_processing' => get_option('autocerfa_processing'),
				'localize' => [
					'optimize_notice' => __('To optimize the performance of your website it may take a longer time to pull all stock cars.','autocerfa-connector'),
					'are_you_sure' => __('Are you sure?','autocerfa-connector'),
					'not_able_to_revert' => __('You won\'t be able to revert this!','autocerfa-connector'),
					'yes_delete' => __('Yes, delete it!','autocerfa-connector'),
					'cancel' => __('Cancel','autocerfa-connector'),
				]
			)
		);
	}
}