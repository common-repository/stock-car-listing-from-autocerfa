<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
$step         = isset($_GET['step']) ? (int) $_GET['step'] : 1;
$step_1_class = '';
$step_2_class = '';
$step_3_class = '';
if ($step === 1) {
    $step_1_class = 'active';
} elseif ($step === 2) {
    $step_1_class = 'complete';
    $step_2_class = 'active';
} elseif ($step === 3) {
    $step_1_class = 'complete';
    $step_2_class = 'complete';
    $step_3_class = 'active';
} elseif ($step > 0) {
    $step_1_class = 'complete';
}

$redirect_url           = urlencode(admin_url('/admin.php?page=autocerfa'));
$redirect_url_with_step = urlencode(admin_url('/admin.php?page=autocerfa&step=2&get_started'));
$autocerfa_url          = "https://www.autocerfa.com/all-post/?action=authorize-autocerfa";
$url                    = "{$autocerfa_url}&redirect_url={$redirect_url_with_step}";

?>
<div class="step-bar-wrapper">
    <?php
        wp_nonce_field('autocerfa-get-started')
    ?>
    <div class="row">
        <div class="col-md-8">
            <div class="autocerfa-step-bar">
                <ul class="progressbar">
                    <li class="authorization <?= $step_1_class ?>"><?php _e('Authorization',
                            'autocerfa-connector'); ?></li>
                    <li class="synchronization <?= $step_2_class ?>"><?php _e('Synchronization',
                            'autocerfa-connector'); ?></li>
                    <li class="creating-page <?= $step_3_class ?>"><?php _e('Completed',
                            'autocerfa-connector'); ?></li>
                </ul>
            </div>
            <div class="step-bar-content-wrapper">
                <div class="step-authorization-content <?= $step_1_class ?>">
                    <div class="step-content-box">
                        <h2><?php _e('Authorizing Autocerfa', 'autocerfa-connector'); ?></h2>
                        <p><?php _e('Authorize your <a href="https://www.autocerfa.com/" target="_blank">Autocerfa</a> account. You may require login autocerfa if not logged in.',
                                'autocerfa-connector'); ?></p>
                        <a class="autocerfa-cmn-btn step_bar_btn"
                           href="<?= "{$autocerfa_url}&redirect_url={$redirect_url_with_step}" ?>"><?php _e('Authorize Your Autocerfa Account',
                                'autocerfa-connector'); ?></a>
                    </div>
                </div>

                <div class="step-synchronization-content <?= $step_2_class ?>">
                    <div class="step-content-box">
                        <h2><?php _e('Synchronization', 'autocerfa-connector'); ?></h2>
                        <p><?php _e('This process may take a longer time. After completing this process, you will get notification to your admin email.',
                                'autocerfa-connector'); ?></p>
                        <div class="dropdown-wrapper">
                            <div class="row">
                                <div class="col-md-4 text-right"><b><?php _e('Daily Synchronization',
                                            'autocerfa-connector'); ?></b></div>
                                <div class="col-md-4">
                                    <select name="daily_sync" class="form-control">
                                        <option value="Daily Once"><?php _e('Daily Once',
                                                'autocerfa-connector'); ?></option>
                                        <option value="Daily Twice"><?php _e('Daily Twice',
                                                'autocerfa-connector'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="autocerfa-cmn-btn step_bar_btn synchronize-now"><?php _e('Synchronize Now',
                                'autocerfa-connector'); ?></div>
                    </div>
                </div>

                <div class="step-creating-page-content <?= $step_3_class ?>">
                    <div class="step-content-box">
                        <h4><?php _e('Congratulation! You have completed the installation.',
                                'autocerfa-connector'); ?></h4>
                        <p><?php printf(__('All cars are on this page <a href="%s">%s</a>',
                                'autocerfa-connector'), AutocerfaMisc::carListUrl(), AutocerfaMisc::carListUrl()); ?></p>

                        <p class="autocerfa-advance-configure-change"><strong>Change link of car list page</strong></p>
                        <div class="autocerfa-advance-configure">
                        <p><?php _e('Copy following shortcode and paste on web page to display your stock cars.',
                                'autocerfa-connector'); ?></p>
                        <div class="copy_shortcode">
                            <kbd>[autocerfa-car-lists]</kbd>
                        </div>
                        <p><?php _e('OR', 'autocerfa-connector'); ?>
                            <br><?php _e('Your can create page with shortcode from here automatically.',
                                'autocerfa-connector'); ?></p>
                        <div class="instruction">
                            <div class="input-wrapper">
                                <input type="text" placeholder="Insert Page Name" name="page_name">
                                <button
                                        class="autocerfa-cmn-btn
										loading_animate_btn insert-page-btn">
                                    <div class="load-ripple
										lds-ripple">
                                        <div></div>
                                        <div></div>
                                    </div><?php _e('OK', 'autocerfa-connector'); ?></button>
                            </div>
                            <a href="" target="_blank"
                               class="new-permalink"></a>
                        </div>
                        <a class="autocerfa-cmn-btn step_bar_btn" href="?page=autocerfa"><?php _e('Close',
                                'autocerfa-connector'); ?></a>

                        </div>
                        <p><a href="<?= admin_url('/admin.php?page=autocerfa-settings&tab=general') ?>">Configure theme color & others</a> | <a href="<?= admin_url('/admin.php?page=autocerfa-settings&tab=search_box') ?>">Configure Search Box</a> | <a href="<?= admin_url('/admin.php?page=autocerfa-settings&tab=short_listed_cars') ?>">Configure Short Listed Cars</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>