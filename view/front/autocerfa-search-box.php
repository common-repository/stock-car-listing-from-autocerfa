<?php

$search_max   = isset($_GET['max-price']) ? (int) $_GET['max-price'] : null;
$search_min   = isset($_GET['min-price']) ? (int) $_GET['min-price'] : null;
$search_mark  = isset($_GET['mark']) ? sanitize_text_field($_GET['mark']) : null;
$search_model = isset($_GET['model']) ? sanitize_text_field($_GET['model']) : null;
$search_fuel  = isset($_GET['fuel']) ? sanitize_text_field($_GET['fuel']) : null;
$search_trans = isset($_GET['transmission']) ? sanitize_text_field($_GET['transmission']) : null;

$autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
$box_bg_color = empty($autocerfa_shortcodes_settings['search_box']['box_bg_color']) ? AutocerfaMisc::BLUE : $autocerfa_shortcodes_settings['search_box']['box_bg_color'];
$field_bg_color = empty($autocerfa_shortcodes_settings['search_box']['field_bg_color']) ? AutocerfaMisc::BLUE : $autocerfa_shortcodes_settings['search_box']['field_bg_color'];
$field_border_color = empty($autocerfa_shortcodes_settings['search_box']['field_border_color']) ? AutocerfaMisc::WHITE : $autocerfa_shortcodes_settings['search_box']['field_border_color'];
$field_font_color = empty($autocerfa_shortcodes_settings['search_box']['field_font_color']) ? AutocerfaMisc::WHITE : $autocerfa_shortcodes_settings['search_box']['field_font_color'];
$range_color = empty($autocerfa_shortcodes_settings['search_box']['range_color']) ? AutocerfaMisc::YELLOW : $autocerfa_shortcodes_settings['search_box']['range_color'];

$AutocerfaDefault = new AutocerfaDefault;

$min_price = $AutocerfaDefault->get_min_price();
$max_price = $AutocerfaDefault->get_max_price();
?>
<style>
	.autocerfa-sp-search-box-wrapper {
		background: <?=$box_bg_color ?>;
	}

	.autocerfa-awesome-select-wrapper .autocerfa_select {
		background-color: <?=$field_bg_color ?> !important;
		border: 1px solid <?=$field_border_color ?> !important;
		color: <?=$field_font_color ?> !important;
	}
	.autocerfa-sp-search-box-wrapper .autocerfa-range-slider-wrapper #autocerfaPriceRange {
		color: <?=$range_color ?>;
	}

	.autocerfa-sp-search-box-wrapper .autocerfa_sp_search_btn,
	.autocerfa-sp-search-box-wrapper .autocerfa-range-slider-wrapper .ui-slider-horizontal .ui-slider-range {
		background: <?=$range_color ?>;
	}
</style>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
	<div class="autocerfa-sp-search-box-wrapper">
		<form action="<?php echo AutocerfaMisc::carListUrl() ?>" method="GET">
			<input type="hidden" name="min-price">
			<input type="hidden" name="max-price">
			<div class="row">
				<div class="col-md-8">
                        <div class="autocerfa-single-search-item">
						<div class="autocerfa-awesome-select-wrapper">
							<select name="mark" class="autocerfa_select">
								<option value=""><?php _e('Mark', 'autocerfa-connector') ?></option>
								<option value="">---</option>
								<?php foreach ($marks as $mark) :
									if (empty($mark)) {
										continue;
									}
									$selected = $search_mark === $mark ? 'selected' : '';
									echo "<option {$selected} value='{$mark}'>{$mark}</option>";
								endforeach;
								?>
							</select>
						</div>
					</div>

					<div class="autocerfa-single-search-item">
						<div class="autocerfa-awesome-select-wrapper">
							<select name="model" class="autocerfa_select">
								<option value=""><?php _e('Model', 'autocerfa-connector') ?></option>
								<option value="">---</option>
								<?php foreach ($models as $model) :
									if (empty($model)) {
										continue;
									}
									$selected = $search_model === $model ? 'selected' : '';
									echo "<option {$selected} value='{$model}'>{$model}</option>";
								endforeach;
								?>
							</select>
						</div>
					</div>

					<div class="autocerfa-single-search-item">
						<div class="autocerfa-awesome-select-wrapper">
							<select name="fuel" class="autocerfa_select">
								<option value=""><?php _e('Fuel Type', 'autocerfa-connector') ?></option>
								<option value="">---</option>
								<option value="Diesel" <?= $search_fuel === 'Diesel' ? ' selected' : '' ?>>Diesel</option>
								<option value="Essence" <?= $search_fuel === 'Essence' ? ' selected' : '' ?>>Essence</option>
								<option value="Electrique" <?= $search_fuel === 'Electrique' ? ' selected' : '' ?>>Electrique</option>
								<option value="Hybrides" <?= $search_fuel === 'Hybrides' ? ' selected' : '' ?>>Hybrides</option>
								<option value="Bicarburant Essence/GPL" <?= $search_fuel === 'Bicarburant Essence/GPL' ? ' selected' : '' ?>>Bicarburant Essence/GPL</option>
								<option value="Bicarburant Essence/Bioethonal" <?= $search_fuel === 'Bicarburant Essence/Bioethonal' ? ' selected' : '' ?>>Bicarburant Essence/Bioethonal</option>
								<option value="Autres" <?= $search_fuel === 'Autres' ? ' selected' : '' ?>>Autres</option>
							</select>
						</div>
					</div>
					<div class="autocerfa-single-search-item">
						<div class="autocerfa-awesome-select-wrapper">
							<select name="transmission" class="autocerfa_select">
								<option value=""><?php _e('Transmission Type', 'autocerfa-connector') ?></option>
								<option value="">---</option>
								<option value="automatique" <?= $search_trans == 'automatique' ? ' selected' : '' ?>>Automatique</option>
								<option value="manuelle" <?= $search_trans == 'manuelle' ? ' selected' : '' ?>>Manuelle</option>
								<option value="semi-automatique" <?= $search_trans == 'semi-automatique' ? ' selected' : '' ?>>Semi-automatique</option>
								<option value="séquentielle" <?= $search_trans == 'séquentielle' ? ' selected' : '' ?>>Séquentielle</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="autocerfa-range-slider-wrapper">
						<h4><?php _e('Price Range', 'autocerfa-connector') ?></h4>
						<input type="text" id="autocerfaPriceRange" readonly>
						<div id="autocerfa-price-range" class="slider" data-min-price="<?= $search_min ?>" data-max-price="<?= $search_max ?>"></div>
					</div>

                    <input type="hidden" name="default_min_price" value="<?php echo $min_price ?>">
                    <input type="hidden" name="default_max_price" value="<?php echo $max_price ?>">

					<div class="text-center">
						<button class="autocerfa_sp_search_btn"><?php _e('Search', 'autocerfa-connector') ?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>