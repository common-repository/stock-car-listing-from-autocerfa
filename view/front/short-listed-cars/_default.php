<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="autocerfa_short_listed_car_wrapper owl-carousel">
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

        $badge_label = '';
        if(!empty($car->badge_id)){
            $badge = (new AutocerfaBadge())->get_by_id($car->badge_id);
            if(!empty($badge)) {
                $badge_label = sprintf('<div class="autocerfa_front_badge" style="background: %s; color: %s">%s</div>',
                    $badge->background_color, $badge->text_color, $badge->label);
            }
        }

		$title = apply_filters( 'autocerfa_short_listed_car_title',
		sprintf('<h3>%s - %s</h3>',$car->marque, $car->model),
		$car
	);

		 $subtitle = apply_filters( 'autocerfa_short_listed_car_subtitle', sprintf('<h5>%s</h5>',$car->category),
		 $car );

        $list = <<<LIST
					<div class="autocerfa_short_listed_car_box">
					<div class="car_img">
				    {$badge_label}
					<a href="{$url}"><img src="{$image_url}" alt="{$car->title}"></a>
					</div>
					<div class="autocerfa_car_content">
					<div class="row">
					<div class="col-md-7 col-8">
					<a href="{$url}">{$title}</a>
					{$subtitle}
					</div>
					<div class="col-md-5 col-4">
					<div class="autocerfa_car_price">{$car->price} €</div>
					</div>
					</div>
					<div class="autocerfa_car_meta_description">
					<span>{$year}</span>
					<span class="autocerfa_separator">|</span>
					<span>{$car->milage} Km</span>
					<span class="autocerfa_separator">|</span>
					<span>{$car->energy}</span>
					<span class="autocerfa_separator">|</span>
					<span>{$car->gear_box}</span>
					</div>
					</div>
					</div>
LIST;
        echo $list;
    endforeach;
    ?>
</div>



