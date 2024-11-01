<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="autocerfa_short_listed_slider_2 owl-carousel">
    <?php foreach ($cars as $car):
        $url         = AutocerfaMisc::url($car);
        $year        = explode(' ', $car->reg_date)[2];
        $images      = $car->images;
        $raw_images = $car->raw_images;

        $first_image = empty($images) ? [] : reset(array_filter($images));
        $raw_first_image = empty($raw_images) ? '' : $raw_images[0];

        $image_url = '';

        if(!empty($first_image)){
            $image_url = empty($first_image['thumbnails']['540-405']['url']) ? $first_image['thumbnails']['570-450']['url'] : $first_image['thumbnails']['540-405']['url'];
        }
        else{
            $image_url = $raw_first_image;
        }

        $title       = apply_filters('autocerfa_short_listed_car_title',
            sprintf('<h3>%s - %s</h3>', $car->marque, $car->model),
            $car
        );

        $subtitle = apply_filters('autocerfa_short_listed_car_subtitle', sprintf('<h5>%s</h5>', $car->category),
            $car);

        $list = <<<LIST
		<div class="autocerfa_slider_2_single_item">
                <div class="row">
                    <div class="col-md-6 col-sm-12 slider_2_mobile_version">
                    <a href="{$url}">
                        <img src="{$image_url}" alt="{$car->title}">
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="autocerfa_slider_2_description">
                            <div class="autocerfa_slider_2_price">{$car->price} €</div>
                            <a href="{$url}">{$title}</a>
                            <a href="{$url}">{$subtitle}</a>
                            <ul class="autocerfa_slider_2_car_info">
                                <li>
                                    <div class="autocerfa-item"><i class="fa-regular fa-calendar-days"></i>
                                        <p>{$year}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="autocerfa-item"><i class="fa-solid fa-gauge"></i>
                                        <p>{$car->energy}</p></div>
                                </li>
                                <li>
                                    <div class="autocerfa-item"><i class="fa-sharp fa-solid fa-road"></i>
                                        <p>{$car->milage} km</p></div>
                                </li>
                                <li>
                                    <div class="autocerfa-item"><i class="fa-solid fa-gas-pump"></i>
                                        <p>{$car->gear_box}</p></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 slider_2_desktop_version">
                        <img src="{$first_image['thumbnails']['570-450']['url']}" alt="{$car->title}">
                    </div>
                </div>
            </div>
LIST;
        echo $list;
    endforeach;
    ?>
</div>