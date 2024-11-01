<?php
if (!defined('ABSPATH')) {
    exit;
}

$AutocerfaStockPost = new AutocerfaStockPost();

$rowPerPage  = empty(get_option('car_per_page')) ? 20 : (int) get_option('car_per_page');
$urlPattern  = "?page=autocerfa"."&pg=(:num)";
$currentPage = isset($_GET['pg']) ? (int) $_GET['pg'] : 1;
$offset      = ($currentPage - 1) * $rowPerPage;

$AutocerfaStockPost->query($rowPerPage, $currentPage);
$autocerfaStocks = $AutocerfaStockPost->cars;
$totalStock      = $AutocerfaStockPost->total;

$redirect_url           = urlencode(admin_url('/admin.php?page=autocerfa'));
$redirect_url_with_step = urlencode(admin_url('/admin.php?page=autocerfa&step=2&get_started'));
$autocerfa_url          = "https://www.autocerfa.com/all-post/?action=authorize-autocerfa";
$url                    = "{$autocerfa_url}&redirect_url={$redirect_url}";

$subscription = get_option('opcodespace_subscription');
?>
<style>
    .dropdown-item.badge_show_clr {
        margin-bottom: 5px;
        font-size: 11px;
        transition: 0.4s;
        cursor: pointer;
    }

    .dropdown-item.badge_show_clr:hover {
        opacity: 0.8;
    }

    .autocerfa_grid_list .dropdown-menu {
        padding: 10px;
    }
</style>
<div class="wrap">
    <h1>Autocerfa Connector <?= $subscription ? 'Pro' : '' ?></h1>
    <?php if (isset($_GET['autocerfa_token'])):
        update_option('autocerfa_token', sanitize_text_field($_GET['autocerfa_token']));
        update_option('autocerfa_username', sanitize_user($_GET['username']));
        ?>
        <div id="setting-error-settings_updated" class="notice notice-success">
            <p><strong><?php printf(__('You have authorized your %s account.', 'autocerfa-connector'),
                        esc_attr(sanitize_user($_GET['username']))) ?></strong></p>
        </div>
    <?php endif; ?>
    <?php
    $class = 'hide';
    if (!empty(get_option('autocerfa_processing'))):
        $class = '';
    endif;
    ?>
    <div id="autocerfa_bg_processing_message" class="notice notice-warning <?= $class ?>">
        <table>
            <tr>
                <td><img src="/wp-includes/js/tinymce/skins/lightgray/img/loader.gif" alt=""></td>
                <td><p><strong>    <?php _e('Pulling stock cars from autocerfa. It may take a longer time.',
                                'autocerfa-connector'); ?></strong><br><i>    <?php _e('If you don\'t see any cars after reloading this page, please make sure you have stock cars in autocerfa. If you have stock cars, please contact with server provider to fix wp-cron issue.',
                                'autocerfa-connector'); ?></i></p></td>
            </tr>
        </table>
    </div>

    <?php if (!isset($_GET['autocerfa_token']) && !empty(get_option('autocerfa_token'))): ?>

        <div class="notice notice-success">
            <p><?php printf(__('You are running your Autocerfa <strong>%s</strong> account.', 'autocerfa-connector'),
                    sanitize_user(get_option('autocerfa_username'))); ?></p>
        </div>

    <?php endif; ?>

    <?php if (empty($subscription)): ?>
        <div class="notice notice-info">
            <h3><?php printf(__('Maximum 10 cars are visible for free version. Please <a href="%s" target="_blank">upgrade</a> your account to display unlimited cars.',
                    'autocerfa-connector'), AUTOCERFA_PRODUCT_LINK); ?></h3>
        </div>
    <?php endif; ?>
    <div class="bootstrap-wrapper autocerfa_main_wrapper">
        <div class="auto-cerfa-wrapper">
            <?php if (isset($_GET['get_started']) || empty(get_option('autocerfa_token'))):
                include '_getting_started.php';
            endif; ?>

            <?php if (!isset($_GET['get_started']) && !empty(get_option('autocerfa_token'))): ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <a class="autocerfa-cmn-btn m_right_20"
                                   href="<?= esc_url($url) ?>"><?php _e('Authorize Autocerfa',
                                        'autocerfa-connector'); ?></a>
                                <div class="autocerfa-cmn-btn refresh-now"><?php _e('Refresh Now',
                                        'autocerfa-connector'); ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <?php
                                    wp_nonce_field('autocerfa-dashboard')
                                    ?>
                                    <div class="autocerfa-cmn-btn" data-toggle="modal"
                                         data-target="#addSoldCar"><?php _e('Add Sold Car to List',
                                            'autocerfa-connector'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="auto-cerfa-table">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>MARQUE</th>
                                    <th>MODEL</th>
                                    <th>IMMAT</th>
                                    <th>KM</th>
                                    <th>1ere MEC</th>
                                    <th>Valeur de Vente</th>
                                    <th><?php _e('Invisible', 'autocerfa-connector'); ?></th>
                                    <th><?php _e('Badge', 'autocerfa-connector'); ?></th>
                                    <th class="text-center"><?php _e('Action', 'autocerfa-connector'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($autocerfaStocks as $stock) : ?>
                                    <tr data-id="<?= $stock->ID ?>">
                                        <td><?= esc_attr($stock->marque) ?></td>
                                        <td><?= esc_attr($stock->model) ?></td>
                                        <td><?= esc_attr($stock->immat) ?></td>
                                        <td><?= esc_attr($stock->milage) ?></td>
                                        <td><?= esc_attr($stock->reg_date) ?></td>
                                        <td><?= esc_attr($stock->price) ?></td>
                                        <td class="lg-swtc-btn multi-diff">
                                            <input data-id="<?= (int) $stock->ID ?>"
                                                   type="checkbox" <?php echo empty($stock->hidden) ? "" : 'checked'; ?>>
                                        </td>
                                        <td class="autocerfa_grid_list">
                                            <div class="dropdown">
                                                <?php
                                                $style = '';
                                                $selected_badge = null;
                                                if(!empty($stock->badge_id)){
                                                    $selected_badge = (new AutocerfaBadge())->getRow(['badge_id' => $stock->badge_id]);
                                                    if(empty($selected_badge)){
                                                        $selected_badge = AutocerfaBadge::default();
                                                    }
                                                    $style = sprintf('style="background:%s; color:%s;"', $selected_badge->background_color, $selected_badge->text_color );
                                                }
                                                ?>
                                                <div class="autocerfa_dropdown_btn dropdown-toggle"
                                                     data-toggle="dropdown"
                                                     aria-haspopup="true"
                                                     aria-expanded="false"
                                                     <?= $style ?>
                                                >
                                                    <?= $selected_badge->label ?? __('Badge') ?>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <?php
                                                    $badges = (new AutocerfaBadge())->getAll('badge_id');
                                                    if (empty($badges)):
                                                        $default = AutocerfaBadge::default();
                                                        printf('<div class="dropdown-item badge_show_clr" style="background:%s; color:%s;" data-id="%d">%s</div>',
                                                            $default->background_color, $default->text_color,
                                                            $default->badge_id, $default->label);
                                                    else:
                                                        foreach ($badges as $badge) {
                                                            printf('<div class="dropdown-item badge_show_clr" style="background:%s; color:%s;" data-id="%d">%s</div>',
                                                                $badge->background_color, $badge->text_color,
                                                                $badge->badge_id, $badge->label);
                                                        }
                                                    endif;
                                                    ?>
                                                    <div class="dropdown-item add_badge" style="cursor: pointer;" data-toggle="modal" data-target="#addBadge"><span
                                                                class="dashicons dashicons-plus"></span><?php
                                                        _e('Add Badge', 'autocerfa-connector')
                                                        ?></div>
                                                    <div class="dropdown-item remove_badge" style="cursor: pointer; color: orangered" ><span
                                                                class="dashicons dashicons-trash"></span><?php
                                                        _e('Remove Badge', 'autocerfa-connector')
                                                        ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($stock->type === 'sold'): ?>
                                            <span class="autocerfa_del"><i class="fa fa-trash"></i>
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                            <?= (new AutocerfaPaginator($totalStock, $rowPerPage, $currentPage,
                                $urlPattern))->toHtml() ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php include '_upgrade-panel.php' ?>
                        <?php include '_support-panel.php' ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addSoldCar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding Sold Car to List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""><?php _e('Select Sold Car', 'autocerfa-connector') ?></label>
                            <div class="input-group mb-3 no_dropdown_arrow">
<!--                                <select name="autocerfa-car-list-page" class="form-control choosen_select">-->
<!--                                    <option value=""></option>-->
<!--                                </select>-->
                                <input type="text" class="form-control chosen-search-input">
                                <input type="hidden" name="sold_lead_id">
                                <?php
                                    wp_nonce_field('sold_lead', 'sold_lead_nonce');
                                ?>
                                <div class="input-group-append">
                                    <button class="btn btn_bg autocerfa-sold-car" type="button">
                                        <div class="load-ripple lds-ripple">
                                            <div></div>
                                            <div></div>
                                        </div>
                                        <span class="checkmark"><i class="fa fa-check"></i></span><?php _e('Add',
                                            'autocerfa-connector'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
<!--        <div class="modal fade" id="windowModal" tabindex="-1" aria-hidden="true">-->
<!--            <div class="modal-dialog modal-dialog-centered">-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        <h5 class="modal-title">Get latest offer and features</h5>-->
<!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                            <span aria-hidden="true">&times;</span>-->
<!--                        </button>-->
<!--                    </div>-->
<!--                    <div class="modal-body">-->
<!--                        <div class="form-group">-->
<!--                            <input type="text" class="form-control">-->
<!--                        </div>-->
<!--                        <div class="text-center">-->
<!--                            <a class="autocerfa-cmn-btn outline_btn">No Thanks</a>-->
<!--                            <a class="autocerfa-cmn-btn outline_border">Subscribe Now</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <?php
        include_once 'badges/_add_badge_modal.php'
        ?>

    </div>
</div>


