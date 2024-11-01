<?php
if (!defined('ABSPATH')) {
    exit;
}

$color_1 = AutocerfaMisc::color1();
$color_2 = AutocerfaMisc::color2();
$color_3 = AutocerfaMisc::color3();
// $badge_bg_color = AutocerfaMisc::color4();
// $badge_txt_color = AutocerfaMisc::color5();
?>


<div class="autocerfa-general-wrappper">
    <?php
    wp_nonce_field('autocerfa-general')
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="autocerfa-panel">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="car_per_page"><?php _e('No. of Cars Per Page',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <input name="car_per_page" type="number" step="1" min="1" id="car_per_page"
                                   value="<?= AutocerfaMisc::carPerPage() ?>" class="small-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="single_page_slug"><?php _e('Single Page Slug',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <?php

                            ?>
                            <input class="regular-text" name="single_page_slug" type="text" id="single_page_slug"
                                   value="<?= AutocerfaMisc::singlePageSlug() ?>" class="small-text">
                            <p class="description">  <?php _e('Example:', 'autocerfa-connector'); ?>
                                <code><?= home_url('/{car}/perfect-sport-car') ?></code>. <?php _e('If you get 404 error, after changing this slug, please remove cache and try again.',
                                    'autocerfa-connector'); ?> </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="color_picker"><?php _e('Color skin',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <input type="color" id="autocerfa_theme_color_1" name="autocerfa_theme_color_1"
                                   value="<?= $color_1 ?>"
                            />
                            <input type="color" id="autocerfa_theme_color_2" name="autocerfa_theme_color_2"
                                   value="<?= $color_2 ?>"
                            />
                            <input type="color" id="autocerfa_theme_color_3" name="autocerfa_theme_color_3"
                                   value="<?= $color_3 ?>"
                            />
                            <p class="description"><?php _e('You can change color as your website theme.',
                                    'autocerfa-connector'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label><?php _e('View Style', 'autocerfa-connector'); ?></label></th>
                        <td>
                            <select name='autocerfa_view_style'>
                                <option value="list" <?= (get_option('autocerfa_view_style') === 'list') ? "selected" : "" ?>><?php _e('List View',
                                        'autocerfa-connector'); ?></option>
                                <option value="grid" <?= (get_option('autocerfa_view_style') === 'grid') ? "selected" : "" ?>><?php _e('Grid View',
                                        'autocerfa-connector'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="filter_option"><?php _e('Showing Filter Option',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Showing Filter Option',
                                            'autocerfa-connector'); ?></span></legend>
                                <label for="filter_option">
                                    <input name="filter_option" type="checkbox" id="filter_option"
                                           value="yes" <?= (AutocerfaMisc::FilterOptionVisibility()) ? "checked" : "" ?>><?php _e('Yes',
                                        'autocerfa-connector'); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="daily_sync"><?php _e('Daily Synchronization',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <select name='daily_sync'>
                                <option value='Daily Once' <?= (get_option('daily_sync') === 'Daily Once') ? "selected" : "" ?>><?php _e('Daily Once',
                                        'autocerfa-connector'); ?></option>
                                <option value='Daily Twice' <?= (get_option('daily_sync') === 'Daily Twice') ? "selected" : "" ?>><?php _e('Daily Twice',
                                        'autocerfa-connector'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cropping_image_as_aspect_ration"><?php _e('Cropping Image as aspect ratio',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Cropping Image as aspect ratio',
                                            'autocerfa-connector'); ?></span></legend>
                                <label for="filter_option">
                                    <input name="cropping_image_as_aspect_ration" type="checkbox" id="cropping_image_as_aspect_ration"
                                           value="yes" <?= AutocerfaMisc::croppingImageAsAspectRatio() ? "checked" : "" ?>><?php _e('Yes',
                                        'autocerfa-connector'); ?></label>
                                <p class="description"><?php _e('If you want to crop image as aspect ratio, please check this option. If you keep unchecked, image will be cropped exactly and you may not see full car.',
                                        'autocerfa-connector'); ?></p>
                            </fieldset>
                        </td>
                    </tr>
<?php
$debug = AutocerfaMisc::debug();
?>
                    <tr>
                        <th scope="row"><label for="filter_option"><?php _e('Debug',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Debug',
                                            'autocerfa-connector'); ?></span></legend>
                                <label for="filter_option">
                                    <input name="autocerfa_debug" type="checkbox" id="autocerfa_debug"
                                           value="yes" <?= $debug ? "checked" : "" ?>><?php _e('Yes',
                                        'autocerfa-connector'); ?></label>
                            </fieldset>
                            <?php if($debug): ?>
                            <div class="view_log">
                                <h3><?php _e('Logs','autocerfa-connector'); ?></h3>
                                <div class="logs_box">
                                    <pre><?php echo AutocerfaLogger::content() ?></pre>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php

                    $nonce = wp_create_nonce('update_min_max_price');
                    ?>

                    <tr>
                        <td colspan="2">
                            <a href="<?php echo admin_url('/admin-post.php?action=update_min_max_price&_wpnonce=' . $nonce) ?>"><?php _e('Update Minimum and Maximum Price') ?></a>
                        </td>
                    </tr>



                </table>

                <p>
                    <button class="btn btn_bg autocerfa-save-general-settings" type="button">
                <div class="load-ripple lds-ripple">
                    <div></div>
                    <div></div>
                </div>
                <span class="checkmark"><i class="fa fa-check"></i></span><?php _e('Save','autocerfa-connector') ?></button>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="autocerfa-panel autocerfa_general_shortcode">
                <h5><?php _e('Showing all stock cars', 'autocerfa-connector'); ?></h5>
                <p><?php _e('Use this shortcode', 'autocerfa-connector'); ?> <code>[autocerfa-car-lists]</code></p>

                <div class="form-group">
                    <label for=""><?php _e('Select a page where this shorcode is added',
                            'autocerfa-connector') ?></label>
                    <div class="input-group mb-3 no_dropdown_arrow">
                        <select name="autocerfa-car-list-page" class="form-control choosen_select">
                            <option value=""><?php _e('Select Page', 'autocerfa-connector'); ?></option>
                            <?php foreach (get_pages() as $page) {
                                $selected = '';
                                if ($selected_car_list_page === $page->ID) {
                                    $selected = 'selected';
                                }
                                echo "<option value='{$page->ID}' {$selected}>{$page->post_title}</option>";
                            } ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn_bg autocerfa-car-list-page-btn" type="button">
                                <div class="load-ripple lds-ripple">
                                    <div></div>
                                    <div></div>
                                </div>
                                <span class="checkmark"><i class="fa fa-check"></i></span><?php _e('Save',
                                    'autocerfa-connector'); ?></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="autocerfa-panel alert_panel">
                <h5><?php _e('Single stock car', 'autocerfa-connector'); ?></h5>
                <p class="description"><?php _e('If default single page is not compatible to your theme, you should select create a page and use this shortcode',
                        'autocerfa-connector'); ?> <code>[autocerfa-single-car]</code></p>
                <div class="alert alert-danger">
                    <span><i class="fa fa-exclamation-triangle"></i></span> <?php _e('Please, select page to use above shortcode for single car.',
                        'autocerfa-connector'); ?>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3 no_dropdown_arrow">
                        <select name="autocerfa-single-page" class="form-control choosen_select">
                            <option value=""><?php _e('Select Page', 'autocerfa-connector'); ?></option>
                            <?php foreach (get_pages() as $page) {
                                $selected = '';
                                if ($selected_single_page === $page->ID) {
                                    $selected = 'selected';
                                }
                                echo "<option value='{$page->ID}' {$selected}>{$page->post_title}</option>";
                            } ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn_bg autocerfa-single-page-btn" type="button">
                                <div class="load-ripple lds-ripple">
                                    <div></div>
                                    <div></div>
                                </div>
                                <span class="checkmark"><i class="fa fa-check"></i></span><?php _e('Save',
                                    'autocerfa-connector'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
include_once 'badges/badges.php';
            ?>
        </div>
    </div>
</div>



