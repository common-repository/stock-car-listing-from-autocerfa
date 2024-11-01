<?php

$search_max   = isset($_GET['max-price']) ? (int) $_GET['max-price'] : null;
$search_min   = isset($_GET['min-price']) ? (int) $_GET['min-price'] : null;
$search_mark  = isset($_GET['mark']) ? sanitize_text_field($_GET['mark']) : null;
$search_model = isset($_GET['model']) ? sanitize_text_field($_GET['model']) : null;
$search_fuel  = isset($_GET['fuel']) ? sanitize_text_field($_GET['fuel']) : null;
$search_trans = isset($_GET['transmission']) ? sanitize_text_field($_GET['transmission']) : null;
?>

<div class="row">
    <div class="col-md-12">
        <div class="autocerfa-sidebar-widget">
            <div class="autocerfa-search-content">
                <div class="autocerfa-search-heading">
                    <div class="autocerfa-icon">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="autocerfa-text-content">
                        <h2><?php _e('Quick Search', 'autocerfa-connector') ?></h2>
                        <span><?php _e('We made a quick search just for you',
                                'autocerfa-connector') ?></span>
                    </div>
                </div>
                <div class="autocerfa-search-form">
                    <div class="row">
                        <form class="form-inline" method='GET' action="">
                            <input type="hidden" name="" value="">

                            <div class="col-md-12">
                                <div class="autocerfa-input-select">
                                    <select name="mark" id="mark">
                                        <option value=""><?php _e('Mark','autocerfa-connector') ?></option>
                                        <option value="">---</option>
                                        <?php foreach ($marks as $mark) :
                                            if (empty($mark)) {
                                                continue;
                                            }
                                            $selected = $search_mark === $mark ? 'selected' : '';
                                            echo "<option {$selected}>{$mark}</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="autocerfa-input-select">
                                    <select name="model" id="model">
                                        <option value=""><?php _e('Model',
                                                'autocerfa-connector') ?></option>
                                        <option value="">---</option>
                                        <?php foreach ($models as $model) :
                                            if (empty($model)) {
                                                continue;
                                            }
                                            $selected = $search_model === $model ? 'selected' : '';
                                            echo "<option {$selected}>{$model}</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <?php
                            $AutocerfaDefault = new AutocerfaDefault();
                            $min_price = $AutocerfaDefault->get_min_price();
                            $max_price = $AutocerfaDefault->get_max_price();
                            $starting_price = $min_price;
                            if(strlen($min_price) >= 3){
                                $starting_price = (float)(substr((string)$min_price, 0, strlen($min_price) - 2) . '00');
                            }
                            ?>

                            <div class="col-md-6">
                                <div class="autocerfa-input-select">
                                    <select name="min-price" id="min-price">
                                        <option value=""><?php _e('Min Price',
                                                'autocerfa-connector') ?></option>
                                        <option value="">---</option>
                                        <?php
                                        for($price = $starting_price; $price < $max_price+ 5000; $price += 5000){
                                            $selected = $price === $search_min ? 'selected' : '';
                                            printf('<option value="%d" %s>&euro;%d</option>', $price, $selected, $price);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="autocerfa-input-select">
                                    <select name="max-price" id="max-price">
                                        <option value=""><?php _e('Max Price',
                                                'autocerfa-connector') ?></option>
                                        <option value="">---</option>

                                        <?php
                                        for($price = $starting_price; $price < $max_price+ 5000; $price += 5000){
                                            $selected = $price === $search_max ? 'selected' : '';
                                            printf('<option value="%d" %s>&euro;%d</option>', $price, $selected, $price);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="autocerfa-input-select">
                                    <select name="fuel" id="fuel">
                                        <option value=""><?php _e('Fuel Type',
                                                'autocerfa-connector') ?></option>
                                        <option value="">---</option>
                                        <option value="Diesel"<?= $search_fuel == 'Diesel' ? ' selected' : '' ?>>
                                            Diesel
                                        </option>
                                        <option value="Essence"<?= $search_fuel == 'Essence' ? ' selected' : '' ?>>
                                            Essence
                                        </option>
                                        <option value="Electrique"<?= $search_fuel == 'Electrique' ? ' selected' : '' ?>>
                                            Electrique
                                        </option>
                                        <option value="Hybrides"<?= $search_fuel == 'Hybrides' ? ' selected' : '' ?>>
                                            Hybrides
                                        </option>
                                        <option value="Bicarburant Essence/GPL"<?= $search_fuel == 'Bicarburant Essence/GPL' ? ' selected' : '' ?>>
                                            Bicarburant Essence/GPL
                                        </option>
                                        <option value="Bicarburant Essence/Bioethonal"<?= $search_fuel == 'Bicarburant Essence/Bioethonal' ? ' selected' : '' ?>>
                                            Bicarburant Essence/Bioethonal
                                        </option>
                                        <option value="Autres"<?= $search_fuel == 'Autres' ? ' selected' : '' ?>>
                                            Autres
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="autocerfa-input-select">
                                    <select name="transmission" id="transmission">
                                        <option value=""><?php _e('Transmission Type',
                                                'autocerfa-connector') ?></option>
                                        <option value="">---</option>
                                        <option value="automatique"<?= $search_trans == 'automatique' ? ' selected' : '' ?>>
                                            Automatique
                                        </option>
                                        <option value="manuelle"<?= $search_trans == 'manuelle' ? ' selected' : '' ?>>
                                            Manuelle
                                        </option>
                                        <option value="semi-automatique"<?= $search_trans == 'semi-automatique' ? ' selected' : '' ?>>
                                            Semi-automatique
                                        </option>
                                        <option value="séquentielle"<?= $search_trans == 'séquentielle' ? ' selected' : '' ?>>
                                            Séquentielle
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="autocerfa-secondary-button">
                                    <!-- <a href="#">Search <i class="fa fa-search"></i></a> -->
                                    <button class="btn autocerfa-search" type="submit"
                                            name='button_search'
                                            value='Search'> <?php _e('Search',
                                            'autocerfa-connector') ?> <i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>