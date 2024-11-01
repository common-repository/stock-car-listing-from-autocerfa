<?php if (!defined('ABSPATH')) {
    exit;
}
$yellow = AutocerfaMisc::color1();
$blue = AutocerfaMisc::color2();
$black = AutocerfaMisc::color3();

$short_listed_id = (int) $short_listed_id;
?>

<style>
    .autocerfa_short_listed_slider_2 .owl-nav button,
    .autocerfa_short_listed_car_slider_3 .owl-nav button,
    .autocerfa_short_listed_car_wrapper .owl-nav button {
        color: #fff !important;
        background-color: <?=$yellow ?> !important;
        box-shadow: 1px 1px 1px <?=$yellow ?> !important;
    }

    .autocerfa_slider_2_single_item .autocerfa_slider_2_description ul.autocerfa_slider_2_car_info li .autocerfa-item i {
        color: #fff !important;
        background-color: <?=$yellow ?> !important;
    }

    .slider-image .autocerfa_slider_3_single_item .autocerfa_slider_3_content .autocerfa_slider_3_left_item .item_des i,
    .autocerfa_short_listed_car_slider_3 .autocerfa_slider_3_single_item .autocerfa_slider_3_content .autocerfa_slider_3_left_item .item_des i,
    .slider-image .autocerfa_slider_3_single_item .autocerfa_slider_3_content .autocerfa_slider_3_right_item .autocerfa_car_price,
    .autocerfa_short_listed_car_slider_3 .autocerfa_slider_3_single_item .autocerfa_slider_3_content .autocerfa_slider_3_right_item .autocerfa_car_price,
    .autocerfa_slider_2_single_item .autocerfa_slider_2_description .autocerfa_slider_2_price,
    .autocerfa_short_listed_car_box .autocerfa_car_content .autocerfa_car_price {
        color: <?=$yellow ?>;
    }

    .autocerfa_short_listed_car_box .autocerfa_car_content .autocerfa_car_meta_description span.autocerfa_separator {
        color: <?=$yellow ?>;
    }

    .autocerfa_more_demo_btn {
        background: <?=$yellow ?> !important;
    }
</style>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
    <?php if (empty($cars)): ?>
        <h1><?php _e('No selected cars.', 'autocerfa-connector') ?></h1>
    <?php else:
        if($short_listed_id === 1){
            include "short-listed-cars/_template_1.php";
        }
        elseif($short_listed_id === 2){
            include "short-listed-cars/_template_2.php";
        }
        elseif($short_listed_id === 3){
            include "short-listed-cars/_without_slider.php";
        }
        else{
            include 'short-listed-cars/_default.php';
        }
        ?>
        <div class="text-center">
            <a href="<?= AutocerfaMisc::carListUrl() ?>"
               class="autocerfa_more_demo_btn"><?php _e('More Cars', 'autocerfa-connector') ?></a>
        </div>
    <?php endif ?>
</div>