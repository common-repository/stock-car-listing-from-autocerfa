<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="autocerfa_swiper-container two">
    <div class="autocerfa_swiper-wrapper">
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

            $title = apply_filters( 'autocerfa_short_listed_car_title',
		sprintf('%s - %s',$car->marque, $car->model),
		$car
	);

		 $subtitle = apply_filters( 'autocerfa_short_listed_car_subtitle', sprintf('%s',$car->category),
		 $car );

            $list = <<<LIST
<div class="autocerfa_swiper-slide">
                    <div class="slider-image">
                        <a href="{$url}">
                            <div class="autocerfa_slider_3_single_item">
                                <div class="autocerfa_slider_3_img">
                                    <img src="{$image_url}" alt="{$car->title}">
                                    <div class="img_content">
                                        <div class="img_text_wrapper">
                                            <div class="autocerfa_car_model">{$title}</div>
                                            <div class="autocerfa_car_description">{$subtitle}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="autocerfa_slider_3_content">
                                    <div class="autocerfa_slider_3_left_item">
                                        <div class="item_des"><i class="fa-regular fa-calendar-days"></i>{$year}</div>
                                        <div class="item_des"><i class="fa-solid fa-gauge"></i>{$car->gear_box}
                                        </div>
                                        <div class="item_des"><i class="fa-sharp fa-solid fa-road"></i>{$car->milage} km</div>
                                        <div class="item_des"><i class="fa-solid fa-gas-pump"></i>{$car->energy}</div>
                                    </div>
                                    <div class="autocerfa_slider_3_right_item">
                                        <div class="autocerfa_car_price">{$car->price} €</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
LIST;
            echo $list;
        endforeach;
        ?>
    </div>
</div>