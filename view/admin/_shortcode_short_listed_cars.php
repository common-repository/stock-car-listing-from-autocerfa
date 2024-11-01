<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}

$autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
$option = isset($autocerfa_shortcodes_settings['short_listed_cars']['option']) ? $autocerfa_shortcodes_settings['short_listed_cars']['option'] : false;
$latest_car = isset($autocerfa_shortcodes_settings['short_listed_cars']['latest_car']) ? $autocerfa_shortcodes_settings['short_listed_cars']['latest_car'] : 5;
?>

<div class="autocerfa-panel autocerfa_short_listed_cars">
    <h5><?php _e('Short listed cars', 'autocerfa-connector') ?></h5>
    <p class="description"><?php _e('You can display short listed cars on any page. Use this shortcode.', 'autocerfa-connector') ?>
        <kbd>[autocerfa-short-listed-cars]</kbd></p>
    <div class="row">
        <div class="col-md-7">
            <div class="autocerfa_radio_btn_dblock">
                <input type="radio" name="short_listed_cars_option" id="short_listed_cars_option_1"
                       value="auto" <?= $option === 'auto' ? 'checked' : '' ?>> <label
                        for="short_listed_cars_option_1"><?php _e('Latest cars (recommended)', 'autocerfa-connector') ?></label>
            </div>
            <div class="autocerfa_latest_car_showing_content <?= $option === 'auto' ? 'active' : '' ?>">
                <input type="number" class="form-control" name="latest_car" value="<?= $latest_car ?>"><br>
            </div>
            <div class="autocerfa_radio_btn_dblock">
                <input type="radio" name="short_listed_cars_option" id="short_listed_cars_option_2"
                       value="custom" <?= $option === 'custom' ? 'checked' : '' ?>> <label
                        for="short_listed_cars_option_2"><?php _e('Custom list of car', 'autocerfa-connector') ?></label>
            </div>
        </div>
    </div>
    <div class="autocerfa_custom_list_car_showing_content <?= $option === 'custom' ? 'active' : '' ?>">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <div class="input-group mb-3 no_dropdown_arrow">
                        <select name="short_selected_car_id" class="form-control choosen_select">
                            <option value=""><?php _e('Select Car', 'autocerfa-connector'); ?></option>
                            <?php foreach ($AutocerfaStockPost->cars as $car)
                                echo "<option value='{$car->ID}'>{$car->marque} {$car->immat}</option>";
                            ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn_bg add_car" type="button">
                                <div class="load-ripple lds-ripple">
                                    <div></div>
                                    <div></div>
                                </div>
                                <span class="checkmark"><i class="fa fa-check"></i></span>Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="auto-cerfa-table">
            <div class="table-responsive">
                <table class="table" id="shortListedCars">
                    <thead>
                    <tr>
                        <th>MARQUE</th>
                        <th>MODEL</th>
                        <th>IMMAT</th>
                        <th>KM</th>
                        <th>1ere MEC</th>
                        <th>Valeur de Vente</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $cars = $autocerfa_shortcodes_settings['short_listed_cars']['custom_cars'];
                    if (!empty($cars)):
                        foreach ($cars as $car) {
                            $lead = get_post($car);
                            if (empty($lead)) {
                                continue;
                            }

                            echo $row = "<tr data-id='{$lead->ID}'>
												<td>{$lead->marque}</td>
												<td>{$lead->model}</td>
												<td>{$lead->immat}</td>
												<td>{$lead->milage}</td>
												<td>{$lead->reg_date}</td>
												<td>{$lead->price}</td>
												<td class='text-center'><i class='delete_row fa fa-trash'></i></td>
												</tr>";
                        }
                    endif;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h5><?php _e('Template for short listed cars', 'autocerfa-connector') ?></h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <select name="" id="autocerfa_select_short_listed_car" class="form-control">
                    <option value="[autocerfa-short-listed-cars]">Default</option>
                    <option value="[autocerfa-short-listed-cars id=1]">Template 1</option>
                    <option value="[autocerfa-short-listed-cars id=2]">Template 2</option>
                    <option value="[autocerfa-short-listed-cars id=3]">Displaying all short listed cars</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <a href="https://www.opcodespace.com/demo/?tab=short-listed-car" class="autocerfa-cmn-btn autocerfa_demo_btn_link" target="_blank">Demo</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-group mb-3 no_dropdown_arrow">
                    <input type="text" id="autocerfa_copy_slider_shortcode" class="form-control" value="[autocerfa-short-listed-cars]" readonly>
                    <div class="input-group-append">
                        <button class="btn btn_bg autocerfa_copy_shortcode_btn" type="button"><i class="fa fa-copy"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty(get_option('opcodespace_subscription'))) : ?>
        <div class="autocerfa_overlay">
            <div class="autocerfa_overlay_btn_group">
                <a href="<?= AUTOCERFA_PRODUCT_LINK ?>"
                   class="autocerfa-cmn-btn" target="_blank"><?php _e('Upgrade', 'autocerfa-connector'); ?></a>
                <a href="https://www.opcodespace.com/demo/?tab=short-listed-car" target="_blank"
                   class="autocerfa-cmn-btn autocerfa_demo_btn"><?php _e('Demo', 'autocerfa-connector'); ?></a>
            </div>
        </div>
    <?php endif; ?>
</div>