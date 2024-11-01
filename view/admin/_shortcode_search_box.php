<?php 
if ( ! defined( 'ABSPATH' ) ) {exit;}

(new AutocerfaStockPost)->getMinMaxPrice();

$autocerfa_shortcodes_settings = get_option('autocerfa_shortcodes_settings');
$box_bg_color = empty($autocerfa_shortcodes_settings['search_box']['box_bg_color']) ? AutocerfaMisc::BLUE : $autocerfa_shortcodes_settings['search_box']['box_bg_color'];
$field_bg_color = empty($autocerfa_shortcodes_settings['search_box']['field_bg_color']) ? AutocerfaMisc::BLUE : $autocerfa_shortcodes_settings['search_box']['field_bg_color'];
$field_border_color = empty($autocerfa_shortcodes_settings['search_box']['field_border_color']) ? AutocerfaMisc::WHITE : $autocerfa_shortcodes_settings['search_box']['field_border_color'];
$field_font_color = empty($autocerfa_shortcodes_settings['search_box']['field_font_color']) ? AutocerfaMisc::WHITE : $autocerfa_shortcodes_settings['search_box']['field_font_color'];
$range_color = empty($autocerfa_shortcodes_settings['search_box']['range_color']) ? AutocerfaMisc::YELLOW : $autocerfa_shortcodes_settings['search_box']['range_color'];

$AutocerfaDefault = new AutocerfaDefault;

$min_price = empty($autocerfa_shortcodes_settings['search_box']['min_price']) ? $AutocerfaDefault->get_min_price() : $autocerfa_shortcodes_settings['search_box']['min_price'];
$max_price = empty($autocerfa_shortcodes_settings['search_box']['max_price']) ? $AutocerfaDefault->get_max_price() : $autocerfa_shortcodes_settings['search_box']['max_price'];
?>

<div class="autocerfa-panel autocerfa_sp_search_box">
    <h5><?php _e('Search Box', 'autocerfa-connector') ?></h5>
    <p class="description"><?php _e('You can put this search box on any page. On submit it will take car list page. If default car list page does not look good, please select your current car list page in car list section. Use this shorcode on any page.', 'autocerfa-connector') ?> <kbd>[autocerfa-search-box]</kbd></p>
    <table>
        <tr>
            <td><?php _e('Search Box Background Color', 'autocerfa-connector') ?></td>
            <td><input type="text" class="autocerfa-color-picker box_bg_color" data-alpha-enabled="true" value="<?=$box_bg_color ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Field Background Color', 'autocerfa-connector') ?></td>
            <td><input type="text" class="autocerfa-color-picker field_bg_color" data-alpha-enabled="true" value="<?=$field_bg_color ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Field Border Color', 'autocerfa-connector') ?></td>
            <td><input type="text" class="autocerfa-color-picker field_border_color" data-alpha-enabled="true" value="<?=$field_border_color ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Field Font Color', 'autocerfa-connector') ?></td>
            <td><input type="text" class="autocerfa-color-picker field_font_color" data-alpha-enabled="true" value="<?=$field_font_color ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Price Range & Search Button Color', 'autocerfa-connector') ?></td>
            <td><input type="text" class="autocerfa-color-picker range_color" data-alpha-enabled="true" value="<?=$range_color ?>"></td>
        </tr>
<!--        <tr>-->
<!--            <td>--><?php //_e('Minimum Price', 'autocerfa-connector') ?><!--</td>-->
<!--            <td><input type="text" class="min_price" value="--><?//=$min_price ?><!--"></td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>--><?php //_e('Maximum Price', 'autocerfa-connector') ?><!--</td>-->
<!--            <td><input type="text" class="max_price" value="--><?//=$max_price ?><!--"></td>-->
<!--        </tr>-->
        <tr>
            <td></td>
            <td><button class="autocerfa-cmn-btn" style="border:none; margin-top: 30px" type="button">
                    <div class="load-ripple lds-ripple">
                        <div></div>
                        <div></div>
                    </div><span class="checkmark"><i class="fa fa-check"></i></span><?php _e('Save', 'autocerfa-connector') ?>
                </button></td>
        </tr>
    </table>
    <?php if (empty(get_option('opcodespace_subscription'))) : ?>
        <div class="autocerfa_overlay">
            <div class="autocerfa_overlay_btn_group">
                <a href="<?= AUTOCERFA_PRODUCT_LINK ?>" class="autocerfa-cmn-btn" target="_blank"><?php _e('Upgrade', 'autocerfa-connector'); ?></a>
                <a href="https://www.opcodespace.com/demo/?tab=search-box" target="_blank" class="autocerfa-cmn-btn autocerfa_demo_btn"><?php _e('Demo', 'autocerfa-connector'); ?></a>
            </div>
        </div>
    <?php endif; ?>

</div>