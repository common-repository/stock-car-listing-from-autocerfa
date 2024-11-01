<?php
if (!defined('ABSPATH')) {
    exit;
}

$yellow = AutocerfaMisc::color1();
$blue   = AutocerfaMisc::color2();
$black  = AutocerfaMisc::color3();
?>
<style>
    .autocerfa-single-car .autocerfa-car-details span {
        color: <?= $blue ?>;
    }
    .autocerfa-single-car .autocerfa-car-details ul.autocerfa-car-info li i {
        background-color: <?= $yellow ?>;
        color: <?= $black ?>
    }

    .autocerfa-page-heading .autocerfa-heading-content-bg .autocerfa-heading-content h2 em {
        font-style: normal;
        color: <?= $yellow ?>
    }

    .autocerfa-more-details .autocerfa-item .autocerfa-info-list ul li i {
        color: <?= $blue ?>
    }

    .autocerfa-more-details .autocerfa-item .autocerfa-contact-info i {
        background-color: <?= $yellow ?>;
        color: <?= $black ?>;
    }

    .autocerfa-contact-info a {
        color: #333333 !important;
    }

    .autocerfa-sep-section-heading h2 em {
        font-style: normal;
        color: #f4c23d
    }
</style>
<?php
$lead_id = (int) $_GET['lead_id'];
if ($lead_id > 0) {
    $post = (new AutocerfaStockPost())->getByLeadId($lead_id);
    if(!$post){
        echo '<h1>404</h1>';
        exit;
    }
} else {
    global $post;
}

$images = empty($post->images) ? [] : array_filter($post->images);
$equipments = $post->equipments;

$raw_first_image = empty($raw_images) ? '' : $raw_images[0];

$title = apply_filters(
    'autocerfa_single_car_title',
    sprintf('<h1>%s</h1>', esc_attr($post->title)),
    $post
);
?>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
    <div class="autocerfa-recent-car autocerfa-single-car wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
        <div class="container autocerfa_container padding_top_58">
            <div class="autocerfa-recent-car-content">
                <div class="row">
                    <div class="col-md-7">
                        <div id="single-car" class="autocerfa-slider-pro">
                            <div class="sp-slides">
                                <?php if(!empty($images)):
                                foreach ($images as $image) :
                                    $image_url = empty($image['thumbnails']['800-630']['url']) ? $image['thumbnails']['570-450']['url'] : $image['thumbnails']['800-630']['url'];
                                    ?>
                                    <div class="sp-slide">
                                        <img class="sp-image" src="<?= esc_url($image_url) ?>" alt="<?php echo $post->title  ?>" />
                                    </div>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="sp-slide">
                                        <img class="sp-image" src="<?= esc_url($raw_first_image) ?>" alt="<?php echo $post->title ?>" />
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="sp-thumbnails">
                                <?php if($images):
                                foreach ($images as $image) : ?>
                                    <img class="sp-thumbnail" src="<?= esc_url($image['thumbnails']['120-80']['url']) ?>" alt="<?php echo $post->title ?>" />
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="autocerfa-car-details">
                            <?= $title ?>
                            <span><?= esc_attr($post->price) ?> &euro;</span>
                            <div class="row">
                                <ul class="autocerfa-car-info col-md-6">
                                    <li>
                                        <i class="fa-regular fa-calendar-days"></i>
                                        <p>
                                            <span class="color_gray">Date de la 1ère immatriculation</span> <br>
                                            <?= esc_attr($post->reg_date) ?>
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-gauge"></i>
                                        <p>
                                            <span class="color_gray">Catégorie</span> <br>
                                            <?= esc_attr($post->category) ?>
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fa-sharp fa-solid fa-road"></i>
                                        <p>
                                            <span class="color_gray">Kilométrage</span> <br>
                                            <?= esc_attr($post->milage) ?>km</p>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-gas-pump"></i>
                                        <p>
                                            <span class="color_gray">Energie</span> <br>
                                            <?= esc_attr($post->energy) ?>
                                        </p>
                                    </li>
                                </ul>
                                <ul class="autocerfa-car-info col-md-6">
                                    <li>
                                        <i class="fa-solid fa-palette"></i>
                                        <p>
                                            <span class="color_gray">Couleur</span> <br>
                                            <?= esc_attr($post->couleur) ?>
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-code-branch"></i>
                                        <p>
                                            <span class="color_gray">Boite de Vitesse</span> <br>
                                            <?= esc_attr($post->gear_box) ?>
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-car"></i>
                                        <p>
                                            <span class="color_gray">Etat</span> <br>
                                            <?= esc_attr($post->etat) ?>
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-caravan"></i>
                                        <p>
                                            <span class="color_gray">Contrôle technique</span> <br>
                                            <?= esc_attr($post->con_tech) ?>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="autocerfa-more-details-section">
        <div class="autocerfa-more-details">
            <div class="container autocerfa_container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="autocerfa-item wow fadeInUp" data-wow-duration="0.5s">
                            <div class="autocerfa-sep-section-heading">
                                <h2><?php _e('DESCRIPTION', 'autocerfa-connector'); ?> <em style="color: <?= $yellow ?>"> <?php _e('DU VÉHICULE', 'autocerfa-connector'); ?></em></h2>
                            </div>
                            <p><?= nl2br($post->description) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="autocerfa-item wow fadeInUp" data-wow-duration="0.75s">
                            <div class="autocerfa-sep-section-heading">
                                <h2><?php _e('OPTIONS &', 'autocerfa-connector'); ?> <em style="color: <?= $yellow ?>"><?php _e('EQUIPEMENTS', 'autocerfa-connector'); ?></em></h2>
                            </div>
                            <div class="autocerfa-info-list">
                                <ul>
                                    <?php foreach ($equipments as $equipment) : ?>
                                        <li><i class="fa fa-check-square"></i><span><?= esc_attr($equipment) ?></span></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="autocerfa-item">
                            <div class="autocerfa-sep-section-heading">
                                <h2><?php _e('Contact', 'autocerfa-connector'); ?> <em style="color: <?= $yellow ?>"><?php _e('Informations', 'autocerfa-connector'); ?></em></h2>
                            </div>
                            <div class="autocerfa-contact-info">
                                <div class="row">
                                    <div class="autocerfa-phone col-md-12 col-sm-6 col-xs-6">
                                        <i class="fa-solid fa-phone"></i><span><a href="tel:<?= esc_attr($post->telephone) ?>"><?= esc_attr($post->telephone) ?></a></span>
                                    </div>
                                    <div class="autocerfa-mail col-md-12 col-sm-6 col-xs-6">
                                       <i class="fa-solid fa-envelope"></i><span><a href="mailto:<?= esc_attr($post->email); ?>"><?= esc_attr($post->email) ?></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>