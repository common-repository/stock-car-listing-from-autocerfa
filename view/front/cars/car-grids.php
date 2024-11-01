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
    .autocerfa-car-item .autocerfa-thumb-content .autocerfa-car-badge a {
        background-color: #f4c23d;
        color: #1e1e1e;
    }

    .autocerfa-car-item .autocerfa-down-content span {
        color: <?php echo esc_attr($blue) ?>;
    }

    .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover {
        background-color: <?php echo esc_attr($yellow) ?>
    }

    .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item i {
        color: <?php echo esc_attr($black) ?>
    }

    .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover i {
        color: <?php echo esc_attr($black) ?>
    }

    .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover p {
        color: <?php echo esc_attr($black) ?>
    }

    .autocerfa-car-item:hover {
        border-bottom: 3px solid <?php echo esc_attr($blue) ?>;
    }
</style>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
    <div class="container autocerfa_container">
        <div class="autocerfa-grid-car-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <?php
                        if (!empty($leads)) :
                            foreach ($leads as $lead) :
                                $images = $lead->images;
                                $raw_images = $lead->raw_images;

                                $first_image = empty($images) ? [] : reset(array_filter($images));
                                $raw_first_image = empty($raw_images) ? '' : $raw_images[0];
                                $image_url = empty($first_image) ? $raw_first_image : $first_image['thumbnails']['375-345']['url'];

                                $url = AutocerfaMisc::url($lead);

                                $badge_label = '';
                                if (!empty($lead->badge_id)) {
                                    $badge = (new AutocerfaBadge())->get_by_id($lead->badge_id);
                                    if (!empty($badge)) {
                                        $badge_label = sprintf(
                                            '<div class="autocerfa-car-badge" style="background: %s; color: %s">%s</div>',
                                            $badge->background_color,
                                            $badge->text_color,
                                            $badge->label
                                        );
                                    }
                                }
                        ?>
                                <div class="col-md-4">
                                    <div class="autocerfa-car-item">
                                        <div class="autocerfa-thumb-content">
                                            <?= $badge_label ?>
                                            <div class="autocerfa-thumb-inner">
                                                <a href="<?= esc_url($url) ?>"><img src="<?= esc_attr($image_url) ?>" alt="<?= esc_attr($lead->title) ?>"></a>
                                            </div>
                                        </div>
                                        <div class="autocerfa-down-content autocerfa-down-content-grid">
                                            <a href="<?= esc_url($url) ?>">
                                                <h4><?= esc_attr($lead->title) ?></h4>
                                            </a>
                                            <span><?= esc_attr($lead->price) ?> &euro;</span>
                                            <div class="autocerfa-line-dec"></div>
                                            <p><?php
                                                echo autocerfa_read_more(esc_attr($lead->description), $url); ?>
                                            </p>
                                            <ul class="autocerfa-car-info">
                                                <?php if (!empty($lead->reg_date)) : ?>
                                                    <li>
                                                        <div class="autocerfa-item"><i class="fa-regular fa-calendar-days"></i>
                                                            <p><?= esc_attr(explode(" ", $lead->reg_date)[2]) ?></p>
                                                        </div>
                                                    </li>
                                                <?php endif ?>
                                                <?php if (!empty($lead->gear_box)) : ?>
                                                    <li>
                                                        <div class="autocerfa-item"><i class="fa-solid fa-gauge"></i>
                                                            <p><?= esc_attr($lead->gear_box) ?> </p>
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (!empty($lead->milage)) : ?>
                                                    <li>
                                                        <div class="autocerfa-item"><i class="fa-sharp fa-solid fa-road"></i>
                                                            <p><?= esc_attr($lead->milage) . 'km' ?></p>
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (!empty($lead->energy)) : ?>
                                                    <li>
                                                        <div class="autocerfa-item"><i class="fa-solid fa-gas-pump"></i>
                                                            <p><?= esc_attr($lead->energy) ?></p>
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach;
                        else :
                            ?>
                            <div class="col-md-12">
                                <p class='no-result-message'><?php _e(
                                                                    'Sorry, we don’t have any cars according your criteria, try something else',
                                                                    'autocerfa-connector'
                                                                ) ?></p>
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
            </div>
        </div>

    </div>
</div>