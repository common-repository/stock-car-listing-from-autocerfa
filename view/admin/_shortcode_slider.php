<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
$autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
$option = isset($autocerfa_shortcodes_settings['slider_cars']['option']) ? $autocerfa_shortcodes_settings['slider_cars']['option'] : false;
$latest_car = isset($autocerfa_shortcodes_settings['slider_cars']['latest_car']) ? $autocerfa_shortcodes_settings['slider_cars']['latest_car'] : 5;
?>

<div class="autocerfa-panel autocerfa_slider_cars">
    <h5><?php _e('Slider', 'autocerfa-connector') ?></h5>
    <p class="description"><?php _e('Highlighting cars with beautiful animation.', 'autocerfa-connector') ?> <kbd>[autocerfa-slider]</kbd>
    </p>
    <div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <div class="autocerfa_radio_btn_dblock">
                <input type="radio" name="slider_option" id="slider_option_1"
                       value="auto" <?= $option === 'auto' ? 'checked' : '' ?>> <label
                        for="slider_option_1"><?php _e('Latest Cars', 'autocerfa-connector') ?></label>
            </div>
            <div class="autocerfa_slider_latest_car_showing_content <?= $option === 'auto' ? 'active' : '' ?>">
                <input type="number" class="form-control" name="latest_car" value="<?= $latest_car ?>"><br>
            </div>
            <div class="autocerfa_radio_btn_dblock">
                <input type="radio" name="slider_option" id="slider_option_2"
                       value="custom" <?= $option === 'custom' ? 'checked' : '' ?>> <label
                        for="slider_option_2"><?php _e('Custom list of car', 'autocerfa-connector') ?></label>
            </div>
        </div>
    </div>
    <div class="autocerfa_slider_custom_list_showing_content <?= $option === 'custom' ? 'active' : '' ?>">
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
                <table class="table" id="sliderShortTable">
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

                    $cars = $autocerfa_shortcodes_settings['slider_cars']['custom_cars'];
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
    </div>

    <?php if (empty(get_option('opcodespace_subscription'))) : ?>
        <div class="autocerfa_overlay">
            <div class="autocerfa_overlay_btn_group">
                <a href="<?= AUTOCERFA_PRODUCT_LINK ?>"
                   class="autocerfa-cmn-btn" target="_blank"><?php _e('Upgrade', 'autocerfa-connector'); ?></a>
                <a href="https://www.opcodespace.com/demo/?tab=hero-slider" target="_blank"
                   class="autocerfa-cmn-btn autocerfa_demo_btn"><?php _e('Demo', 'autocerfa-connector'); ?></a>
            </div>
        </div>
    <?php endif; ?>

</div>