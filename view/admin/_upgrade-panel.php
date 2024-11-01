<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
if(empty(get_option('opcodespace_subscription'))): ?>
<div class="autocerfa_right_sidebar">
    <div class="autocerfa_sidebar_box bg-yellow">
        <div class="autocerfa_sidebar_box_ttl"><?php _e('Autocerfa Connector Pro', 'autocerfa-connector'); ?></div>
        <div class="autocerfa-box-content">
            <ul>
                <li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span><?php _e('Unlimited stock cars', 'autocerfa-connector'); ?></li>
                <li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span><?php _e('Short listed cars displaying on any page.', 'autocerfa-connector'); ?></li>
                <li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span><?php _e('Short listed cars in slider on any page', 'autocerfa-connector'); ?></li>
                <li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span><?php _e('Priority support', 'autocerfa-connector'); ?></li>
                <li><span class="li_icon"><i class="fa fa-angle-double-right"></i></span><?php _e('Updates for 1 year', 'autocerfa-connector'); ?></li>
            </ul>
            <hr>
            <h5><?php _e('Buy now only 99€/year', 'autocerfa-connector'); ?></h5>
            <div class="text-center">
                <a href="<?= AUTOCERFA_PRODUCT_LINK ?>" class="autocerfa_buy_now_btn" target="_blank"><?php _e('Buy Now', 'autocerfa-connector'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php endif ?>