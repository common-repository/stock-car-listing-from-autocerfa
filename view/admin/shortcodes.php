<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
$selected_single_page = (int) get_option('autocerfa_single_page');
$selected_car_list_page = (int) get_option('autocerfa_car_list_page');

$AutocerfaStockPost = new AutocerfaStockPost();
$AutocerfaStockPost->query(-1, 1);
wp_nonce_field('autocerfa_shortcodes_nonce', 'autocerfa_shortcodes');

$autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
?>

<div class="bootstrap-wrapper wrap shortcodes autocerfa_main_wrapper">
	<div class="row">
		<div class="col-md-8">
			<!-- <h2><?php _e('All Shortcodes', 'autocerfa-connector'); ?></h2>	 -->		
			<div class="wrap">
				<?php $tab = sanitize_text_field( $_GET['tab'] ?? '' ) ?>
				<nav class="nav-tab-wrapper wp-clearfix autocerfa_tab_bar">
					<a class="nav-tab <?= $tab === 'general' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'admin.php?page=autocerfa-settings&tab=general' ) ?>">General</a>
					<a class="nav-tab <?= $tab === 'short_listed_cars' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'admin.php?page=autocerfa-settings&tab=short_listed_cars' ) ?>">Short Listed Cars</a>
					<a class="nav-tab <?= $tab === 'slider' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'admin.php?page=autocerfa-settings&tab=slider' ) ?>">Slider</a>
					<a class="nav-tab <?= $tab === 'search_box' ? 'nav-tab-active' : ''  ?>" href="<?= admin_url( 'admin.php?page=autocerfa-settings&tab=search_box' ) ?>">Search Box</a>
				</nav>
				<form action="" class="autocerfa_tab_form_style">
					<?php 
					switch($tab){
						case 'short_listed_cars':
						$path = '_shortcode_short_listed_cars.php';
						break;
						case 'slider':
						$path = '_shortcode_slider.php';
						break;
						case 'search_box':
						$path = '_shortcode_search_box.php';
						break;
						default:
						$path = '_shortcode_general.php';
						break;
					}
					include $path;
					?>
				</form>
			</div>
		</div>
		<div class="col-md-4">
			<?php include '_upgrade-panel.php' ?>
			<?php include '_support-panel.php' ?>
		</div>
	</div>