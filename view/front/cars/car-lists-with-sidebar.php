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
    .autocerfa-car-item:hover {border-bottom: 3px solid <?=$blue ?>;}
    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content span {
        color: <?=$blue ?>;
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content p a {
        color: <?=$yellow ?>;
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover {
        background-color: <?=$yellow ?>
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover i {
        color: <?=$black ?>
    }

    .autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover p {
        color: <?=$black ?>
    }

    .pagination-content ul li a {
        color: <?=$black ?>;
        background-color: <?=$yellow ?>;
    }

    .pagination-content ul li a:hover {
        color: #fff;
        background-color: <?=$blue ?>
    }

    .pagination-content ul li.active a {
        color: #fff;
        background-color: <?=$blue ?>
    }

    /*sidebar css color here*/
    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content {
        background-color: <?=$blue ?>;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.15);
        border-top: 3px solid<?=$yellow ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-heading .autocerfa-icon i {
        color: <?=$black ?>;
        background-color: <?=$yellow ?>;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-heading .autocerfa-icon i:after {
        background-color: <?=$yellow ?>;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form input {
        background-color: transparent;
        border: 1px solid #eee;
        color: #fff;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form input:focus {
        border-color: <?=$yellow ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select {
        background-color: transparent;
        color: #fff;
        border: 1px solid #eee;
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select option {
        color: <?=$black ?>
    }

    .autocerfa-sidebar-widgets .autocerfa-sidebar-widget .autocerfa-search-content .autocerfa-search-form select:focus {
        border-color: <?=$yellow ?>
    }

    .autocerfa-secondary-button a {
        background: <?=$yellow ?>;
        color: <?=$black ?>;
    }

    .autocerfa-secondary-button a:hover {
        color: <?=$black ?>;
        background-color: #fff
    }

    .autocerfa-secondary-button .autocerfa-search {
        width: 100%;
        background: <?=$yellow ?>;
        color: <?=$black ?>;
    }

    .autocerfa-secondary-button .autocerfa-search:hover {
        color: <?=$black ?>;
        background-color: #fff
    }
    .autocerfa-car-badge {
       background-color: #f4c23d;
       color: #1e1e1e;
   }

</style>

<div class="bootstrap-wrapper autocerfa_main_wrapper">
    <div class="container autocerfa_container">
        <div class="autocerfa-on-listing wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
            <div class="autocerfa-recent-car-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <?php
                            if(!empty($leads)):
                                foreach ($leads as $lead) :
                                    $images = $lead->images;

                                    $raw_images = $lead->raw_images;

                                    $first_image = empty($images) ? [] : reset(array_filter($images));
                                    $raw_first_image = empty($raw_images) ? '' : $raw_images[0];
                                    $image_url = empty($first_image) ? $raw_first_image : $first_image['thumbnails']['375-345']['url'];

                                    $url   = AutocerfaMisc::url($lead);

                                    $badge_label = '';
                                    if(!empty($lead->badge_id)){
                                        $badge = (new AutocerfaBadge())->get_by_id($lead->badge_id);
                                        if(!empty($badge)) {
                                            $badge_label = sprintf('<div class="autocerfa-car-badge" style="background: %s; color: %s">%s</div>',
                                                $badge->background_color, $badge->text_color, $badge->label);
                                        }
                                    }
                                    ?>
                                    <div class="col-md-12">
                                        <div class="autocerfa-car-item">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="autocerfa-thumb-content">                                                    
                                                        <?= $badge_label ?>
                                                        <div class="autocerfa-thumb-inner">
                                                            <a href="<?= $url ?>">
                                                                <img src="<?= esc_attr($image_url) ?>"
                                                                alt="<?= esc_attr($lead->title) ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="autocerfa-down-content">
                                                        <a href="<?= $url ?>"><h4><?= esc_attr($lead->title) ?></h4></a>
                                                        <span><?= esc_attr($lead->price) ?> &euro;</span>
                                                        <div class="line-dec"></div>
                                                        <p><?php
                                                        $sinlge_page = sanitize_title_with_dashes(get_option('single_page_slug'));
                                                        echo autocerfa_read_more(esc_attr($lead->description), $url); ?>
                                                    </p>
                                                    <ul class="car-info">
                                                        <li>
                                                            <div class="autocerfa-item"><i class="fa-regular fa-calendar-days"></i>
                                                                <p><?= (int) explode(" ", $lead->reg_date)[2] ?></p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="autocerfa-item"><i class="fa-solid fa-gauge"></i>
                                                                <p><?= esc_attr($lead->gear_box) ?></p></div>
                                                            </li>
                                                            <li>
                                                                <div class="autocerfa-item"><i class="fa-sharp fa-solid fa-road"></i>
                                                                    <p><?= esc_attr($lead->milage).'km' ?></p></div>
                                                                </li>
                                                                <li>
                                                                    <div class="autocerfa-item"><i class="fa-solid fa-gas-pump"></i>
                                                                        <p><?= esc_attr($lead->energy) ?></p></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach;
                                    else:
                                        ?>
                                        <div class="col-md-12">
                                            <p class='no-result-message'><?php _e('Sorry, we don’t have any cars according your criteria, try something else', 'autocerfa-connector') ?></p>
                                        </div>
                                    <?php endif ?>

                                    <div class="col-md-12">
                                        <div class="page-numbers">
                                            <div class="pagination-content">

                                                <?= (new AutocerfaPaginator($totalStock, $rowPerPage, $currentPage,
                                                    $urlPattern))->toHtml() ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="autocerfa-sidebar-widgets">
                                        <?php
                                        echo get_view(AUTOCERFA_VIEW_PATH . 'front/cars/sidebar.php',compact('models', 'marks'))
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
