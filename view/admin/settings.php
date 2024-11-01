<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$color_1 = AutocerfaMisc::color1();
$color_2 = AutocerfaMisc::color2();
$color_3 = AutocerfaMisc::color3();
?>

<div class="wrap">
    <h1><?php _e('Autocerfa Settings', 'autocerfa-connector'); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'autocerfa_register_setting' ); ?>
        <p><?php _e('Use this shortcode <kbd>[autocerfa-car-lists]</kbd> to view cars on front.', 'autocerfa-connector'); ?></p>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="ops_token"><?php _e('Opcodespace Token', 'autocerfa-connector'); ?></label></th>
                <td><input class="regular-text" type="password" id="ops_token" name="ops_token" value="<?= sanitize_text_field( get_option('ops_token') ) ?>" />
                    <p class="description"><?php _e('You will get this opcodespace token after purchasing your subscription on OP Code Space. <a
                                href="https://www.opcodespace.com/wp-autocerfa/">Upgrade To Autocerfa Connector Pro</a>', 'autocerfa-connector'); ?></a></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="car_per_page"><?php _e('No. of Cars Per Page', 'autocerfa-connector'); ?></label></th>
                <td>
                  <?php
                      $default_page = (int)get_option('car_per_page');
                      if(empty($default_page))
                      {
                          $default_page = 20;
                      }
                  ?>
                <input name="car_per_page" type="number" step="1" min="1" id="car_per_page" value="<?= (int) $default_page ?>" class="small-text">
              </td>
            </tr>

            <tr>
                <th scope="row"><label for="single_page_slug"><?php _e('Single Page Slug', 'autocerfa-connector'); ?></label></th>
                <td>
                  <?php
                      $default_slug = sanitize_title_with_dashes(get_option('single_page_slug'));
                      if(empty($default_slug))
                      {
                          $default_slug = "car";
                      }
                  ?>
                <input class="regular-text" name="single_page_slug" type="text" id="single_page_slug" value="<?= $default_slug ?>" class="small-text">
                    <p class="description">  <?php _e('Example:', 'autocerfa-connector'); ?> <kbd><?=home_url('/car/12345/perfect-sport-car') ?></kbd>. <?php _e('If you get 404 error, after changing this slug, please remove cache and try again.', 'autocerfa-connector'); ?> </p>
              </td>
            </tr>

            <tr>
                <th scope="row"><label for="color_picker"><?php _e('Color skin', 'autocerfa-connector'); ?></label></th>
                <td>
                    <input type="color" id="autocerfa_theme_color_1" name="autocerfa_theme_color_1"
                           value="<?=  $color_1 ?>"
                    />
                  <input type="color" id="autocerfa_theme_color_2" name="autocerfa_theme_color_2"
                         value="<?= $color_2 ?>"
                  />
                  <input type="color" id="autocerfa_theme_color_3" name="autocerfa_theme_color_3"
                         value="<?=  $color_3 ?>"
                  />
                    <p class="description"><?php _e('You can change color as your website theme.', 'autocerfa-connector'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="filter_option"><?php _e('Showing Filter Option', 'autocerfa-connector'); ?></label></th>
                <td><fieldset><legend class="screen-reader-text"><span><?php _e('Showing Filter Option', 'autocerfa-connector'); ?></span></legend>
                  <label for="filter_option">
                    <input name="filter_option" type="checkbox" id="filter_option" value="yes"  <?= (AutocerfaMisc::FilterOptionVisibility())?"checked":"" ?>><?php _e('Yes', 'autocerfa-connector'); ?></label>
                </fieldset></td>
            </tr>

            <tr>
                <th scope="row"><label for="daily_sync"><?php _e('Daily Synchronization', 'autocerfa-connector'); ?></label></th>
                <td>
                  <select name='daily_sync'>
                      <option value='Daily Once' <?= (get_option('daily_sync') === 'Daily Once')?"selected":"" ?>><?php _e('Daily Once', 'autocerfa-connector'); ?></option>
                      <option value='Daily Twice' <?= (get_option('daily_sync') === 'Daily Twice')?"selected":"" ?>><?php _e('Daily Twice', 'autocerfa-connector'); ?></option>
                  </select>
                </td>
            </tr>

            
        </table>
        <?php  submit_button(); ?>
    </form>
</div>
