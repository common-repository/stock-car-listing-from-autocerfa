<?php if ( ! defined( 'ABSPATH' ) ) {exit;} 
$yellow = AutocerfaMisc::color1();
$blue   = AutocerfaMisc::color2();
$black  = AutocerfaMisc::color3();
?>
<style>
    .autocerfa_brand::after,
    .autocerfa_brand::before,
    .autocerfa_brand {
        background: <?=$blue ?> !important;
        color: #fff !important;
    }
    .autocerfa_model::after,
    .autocerfa_model::before,
    .autocerfa_model {
        background: <?=$yellow ?> !important;
        color: #fff !important;
    }
    .autocerfa_price::after,
    .autocerfa_price::before,
    .autocerfa_price {
        background: <?=$black ?> !important;
        color: #fff !important;
    }
</style>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
    <?php if(empty($cars)): ?>
    <h1><?php _e('No selected cars.', 'autocerfa-connector') ?></h1>
    <?php else: ?>
    <div class="autocerfa_front_slider_wrapper owl-carousel">
        <?php foreach ($cars as $car):
            $url = AutocerfaMisc::url($car);
        $year = explode(' ',$car->reg_date)[2];
        $images = $car->images;
		$first_image = reset(array_filter($images));
        $slider = <<<SLIDER
        <div class="autocerfa_slider_item">
            <img src="{$first_image['url']}" alt="{$car->title}">
            <div class="autocerfa_slider_text_wrapper">
                <a href="{$url}" class="autocerfa_brand">{$car->marque}</a>
                <a href="{$url}" class="autocerfa_model">{$car->model} - {$year}</a>
                <a href="{$url}" class="autocerfa_price">{$car->price}€</a>
            </div>
        </div>
SLIDER;
        echo $slider;
        endforeach;
        ?>
    </div>
    <?php endif ?>
</div>