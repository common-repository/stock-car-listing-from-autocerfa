<?php
if (!defined('ABSPATH')) {
    exit;
}

$yellow = AutocerfaMisc::color1();
$blue   = AutocerfaMisc::color2();
$black  = AutocerfaMisc::color3();

$selected_single_page = (int) get_option('autocerfa_single_page');
?>
<style>
    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content span {
        color: <?= $blue ?>;
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content p a {
        color: <?= $yellow ?>;
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover {
        background-color: <?= $yellow ?>
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover i {
        color: <?= $black ?>
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover p {
        color: <?= $black ?>
    }

    .pagination-content ul li a {
        color: <?= $black ?>;
        background-color: <?= $yellow ?>;
    }

    .pagination-content ul li a:hover {
        color: #fff;
        background-color: <?= $blue ?>
    }

    .pagination-content ul li.active a {
        color: #fff;
        background-color: <?= $blue ?>
    }

    /*sidebar css color here*/
    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content {
        background-color: <?= $blue ?>;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.15);
        border-top: 3px solid<?= $yellow ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-heading .autocerfa-icon i {
        color: <?= $black ?>;
        background-color: <?= $yellow ?>;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-heading .autocerfa-icon i:after {
        background-color: <?= $yellow ?>;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form input {
        background-color: transparent;
        border: 1px solid #eee;
        color: #fff;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form input:focus {
        border-color: <?= $yellow ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select {
        background-color: transparent;
        color: #fff;
        border: 1px solid #eee;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select option {
        color: <?= $black ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select:focus {
        border-color: <?= $yellow ?>
    }

    .autocerfa-secondary-button a {
        background: <?= $yellow ?>;
        color: <?= $black ?>;
    }

    .autocerfa-secondary-button a:hover {
        color: <?= $black ?>;
        background-color: #fff
    }

    .autocerfa-secondary-button .autocerfa-search {
        width: 100%;
        background: <?= $yellow ?>;
        color: <?= $black ?>;
    }

    .autocerfa-secondary-button .autocerfa-search:hover {
        color: <?= $black ?>;
        background-color: #fff
    }
</style>
<?php

$search_max   = isset($_GET['max-price']) ? (int) $_GET['max-price'] : null;
$search_min   = isset($_GET['min-price']) ? (int) $_GET['min-price'] : null;
$search_mark  = isset($_GET['mark']) ? sanitize_text_field($_GET['mark']) : null;
$search_model = isset($_GET['model']) ? sanitize_text_field($_GET['model']) : null;
$search_fuel  = isset($_GET['fuel']) ? sanitize_text_field($_GET['fuel']) : null;
$search_trans = isset($_GET['transmission']) ? sanitize_text_field($_GET['transmission']) : null;

$search_title = apply_filters(
    'autocerfa_car_list_search_form_title',
    sprintf('<h2>%s</h2>', __('Quick Search', 'autocerfa-connector'))
);
?>
<div class="bootstrap-wrapper">
    <div class="container">
        <div class="autocerfa-on-listing wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="autocerfa-recent-car-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <?php
                            if (!empty($leads)) :
                                foreach ($leads as $lead) :
                                    $images = $lead->images;
                                    $raw_images = $lead->raw_images;

                                    $first_image = empty($images) ? [] : reset(array_filter($images));
                                    $raw_first_image = empty($raw_images) ? '' : $raw_images[0];
                                    $image_url = empty($first_image) ? $raw_first_image : $first_image['thumbnails']['375-345']['url'];

                                    $url   = AutocerfaMisc::url($lead);

                                    $title = apply_filters(
                                        'autocerfa_car_list_title',
                                        sprintf('<h4>%s</h4>', esc_attr($lead->title)),
                                        $lead
                                    );

                            ?>
                                    <div class="col-md-12">
                                        <div class="autocerfa-car-item">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="autocerfa-thumb-content">
                                                        <div class="autocerfa-thumb-inner">
                                                            <a href="<?= $url ?>">
                                                                <img src="<?= esc_attr($image_url) ?>" alt="<?= $title ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="autocerfa-down-content">
                                                        <a href="<?= $url ?>">
                                                            <?= $title ?>
                                                        </a>
                                                        <span><?= esc_attr($lead->price) ?> &euro;</span>
                                                        <div class="line-dec"></div>
                                                        <p><?php
                                                            $sinlge_page = sanitize_title_with_dashes(get_option('single_page_slug'));
                                                            echo autocerfa_read_more(esc_attr($lead->description), $url); ?>
                                                        </p>
                                                        <ul class="car-info">
                                                            <li>
                                                                <div class="autocerfa-item"><i class="flaticon flaticon-calendar"></i>
                                                                    <p><?= (int) explode(" ", $lead->reg_date)[2] ?></p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="autocerfa-item"><i class="flaticon flaticon-speed"></i>
                                                                    <p><?= esc_attr($lead->gear_box) ?></p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="autocerfa-item"><i class="flaticon flaticon-road"></i>
                                                                    <p><?= esc_attr($lead->milage) . 'km' ?></p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="autocerfa-item"><i class="flaticon flaticon-fuel"></i>
                                                                    <p><?= esc_attr($lead->energy) ?></p>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;
                            else :
                                ?>
                                <div class="col-md-12">
                                    <p class='no-result-message'><?php _e('Sorry, we don’t have any cars according your criteria, try something else', 'autocerfa-connector') ?></p>
                                </div>
                            <?php endif ?>

                            <div class="col-md-12">
                                <div class="page-numbers">
                                    <div class="pagination-content">

                                        <?= (new AutocerfaPaginator(
                                            $totalStock,
                                            $rowPerPage,
                                            $currentPage,
                                            $urlPattern
                                        ))->toHtml() ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="autocerfa-sidebar-widgets">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="autocerfa-sidebar-widget">
                                        <div class="autocerfa-search-content">
                                            <div class="autocerfa-search-heading">
                                                <div class="autocerfa-icon">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                                <div class="autocerfa-text-content">
                                                    <?=$search_title ?>
                                                    <span><?php _e(
                                                                'We made a quick search just for you',
                                                                'autocerfa-connector'
                                                            ) ?></span>
                                                </div>
                                            </div>
                                            <div class="autocerfa-search-form">
                                                <div class="row">
                                                    <form class="form-inline" method='GET' action="">
                                                        <input type="hidden" name="" value="">
                                                        <div class="col-md-12">
                                                            <div class="autocerfa-input-select">
                                                                <select name="model" id="model">
                                                                    <option value=""><?php _e(
                                                                                            'Model',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
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
                                                        <div class="col-md-12">
                                                            <div class="autocerfa-input-select">
                                                                <select name="mark" id="mark">
                                                                    <option value=""><?php _e(
                                                                                            'Mark',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
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
                                                        <div class="col-md-6">
                                                            <div class="autocerfa-input-select">
                                                                <select name="min-price" id="min-price">
                                                                    <option value=""><?php _e(
                                                                                            'Min Price',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
                                                                    <option value="500" <?= $search_min == '500' ? ' selected' : '' ?>>
                                                                        &euro;500
                                                                    </option>
                                                                    <option value="1000" <?= $search_min == '1000' ? ' selected' : '' ?>>
                                                                        &euro;1,000
                                                                    </option>
                                                                    <option value="1500" <?= $search_min == '1500' ? ' selected' : '' ?>>
                                                                        &euro;1,500
                                                                    </option>
                                                                    <option value="2000" <?= $search_min == '2000' ? ' selected' : '' ?>>
                                                                        &euro;2,000
                                                                    </option>
                                                                    <option value="2500" <?= $search_min == '2500' ? ' selected' : '' ?>>
                                                                        &euro;2,500
                                                                    </option>
                                                                    <option value="3000" <?= $search_min == '3000' ? ' selected' : '' ?>>
                                                                        &euro;3,000
                                                                    </option>
                                                                    <option value="3500" <?= $search_min == '3500' ? ' selected' : '' ?>>
                                                                        &euro;3,500
                                                                    </option>
                                                                    <option value="4000" <?= $search_min == '4000' ? ' selected' : '' ?>>
                                                                        &euro;4,000
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="autocerfa-input-select">
                                                                <select name="max-price" id="max-price">
                                                                    <option value=""><?php _e(
                                                                                            'Max Price',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
                                                                    <option value="5000" <?= $search_max == '5000' ? ' selected' : '' ?>>
                                                                        &euro;5,000
                                                                    </option>
                                                                    <option value="7500" <?= $search_max == '7500' ? ' selected' : '' ?>>
                                                                        &euro;7,500
                                                                    </option>
                                                                    <option value="10000" <?= $search_max == '10000' ? ' selected' : '' ?>>
                                                                        &euro;10,000
                                                                    </option>
                                                                    <option value="15500" <?= $search_max == '15500' ? ' selected' : '' ?>>
                                                                        &euro;15,500
                                                                    </option>
                                                                    <option value="20000" <?= $search_max == '20000' ? ' selected' : '' ?>>
                                                                        &euro;20,000
                                                                    </option>
                                                                    <option value="25000" <?= $search_max == '25000' ? ' selected' : '' ?>>
                                                                        &euro;25,000
                                                                    </option>
                                                                    <option value="30000" <?= $search_max == '30000' ? ' selected' : '' ?>>
                                                                        &euro;30,000
                                                                    </option>
                                                                    <option value="35000" <?= $search_max == '35000' ? ' selected' : '' ?>>
                                                                        &euro;35,000
                                                                    </option>
                                                                    <option value="40000" <?= $search_max == '40000' ? ' selected' : '' ?>>
                                                                        &euro;40,000
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="autocerfa-input-select">
                                                                <select name="fuel" id="fuel">
                                                                    <option value=""><?php _e(
                                                                                            'Fuel Type',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
                                                                    <option value="Diesel" <?= $search_fuel == 'Diesel' ? ' selected' : '' ?>>
                                                                        Diesel
                                                                    </option>
                                                                    <option value="Essence" <?= $search_fuel == 'Essence' ? ' selected' : '' ?>>
                                                                        Essence
                                                                    </option>
                                                                    <option value="Electrique" <?= $search_fuel == 'Electrique' ? ' selected' : '' ?>>
                                                                        Electrique
                                                                    </option>
                                                                    <option value="Hybrides" <?= $search_fuel == 'Hybrides' ? ' selected' : '' ?>>
                                                                        Hybrides
                                                                    </option>
                                                                    <option value="Bicarburant Essence/GPL" <?= $search_fuel == 'Bicarburant Essence/GPL' ? ' selected' : '' ?>>
                                                                        Bicarburant Essence/GPL
                                                                    </option>
                                                                    <option value="Bicarburant Essence/Bioethonal" <?= $search_fuel == 'Bicarburant Essence/Bioethonal' ? ' selected' : '' ?>>
                                                                        Bicarburant Essence/Bioethonal
                                                                    </option>
                                                                    <option value="Autres" <?= $search_fuel == 'Autres' ? ' selected' : '' ?>>
                                                                        Autres
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="autocerfa-input-select">
                                                                <select name="transmission" id="transmission">
                                                                    <option value=""><?php _e(
                                                                                            'Transmission Type',
                                                                                            'autocerfa-connector'
                                                                                        ) ?></option>
                                                                    <option value="automatique" <?= $search_trans == 'automatique' ? ' selected' : '' ?>>
                                                                        Automatique
                                                                    </option>
                                                                    <option value="manuelle" <?= $search_trans == 'manuelle' ? ' selected' : '' ?>>
                                                                        Manuelle
                                                                    </option>
                                                                    <option value="semi-automatique" <?= $search_trans == 'semi-automatique' ? ' selected' : '' ?>>
                                                                        Semi-automatique
                                                                    </option>
                                                                    <option value="séquentielle" <?= $search_trans == 'séquentielle' ? ' selected' : '' ?>>
                                                                        Séquentielle
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="autocerfa-secondary-button">
                                                                <!-- <a href="#">Search <i class="fa fa-search"></i></a> -->
                                                                <button class="btn autocerfa-search" type="submit" name='button_search' value='Search'> <?php _e(
                                                                                                                                                            'Search',
                                                                                                                                                            'autocerfa-connector'
                                                                                                                                                        ) ?> <i class="fa fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>