<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>
<div class="autocerfa-panel alert_panel">
    <h5><?php _e('Badges', 'autocerfa-connector'); ?></h5>
    <div class="autocerfa_view_badge_wrapper">
        <div class="form-group text-right">
            <div class="autocerfa-cmn-btn" data-toggle="modal" data-target="#addBadge"><?php _e('Add Badge', 'autocerfa-connector'); ?></div>
        </div>

        <?php
        $badges = (new AutocerfaBadge())->getAll('badge_id');
        if(empty($badges)){
            $badges[] = AutocerfaBadge::default();
        }
        ?>
        <?php foreach($badges as $badge): ?>
        <div class="autocerfa_view_badge" data-badge_id="<?php echo esc_attr($badge->badge_id) ?>">
            <div class="autocerfa_badge_label" style="background:<?=$badge->background_color ?>; color:<?=$badge->text_color ?>;"><?php echo esc_attr($badge->label) ?></div>
            <div class="autocerfa_badge_set_opt" style="border:1px solid <?=$badge->background_color ?>;">
                            <span class="autocerfa_badge_edit" data-toggle="modal" data-target="#addBadge" ><i
                                    class="fa fa-edit"></i></span>
                <?php if((int)$badge->badge_id !== 1): ?>
                <span class="autocerfa_badge_delete"><i class="fa fa-trash"></i></span>
                <?php endif; ?>

            </div>
        </div>
<?php endforeach ?>

    </div>
</div>

<?php
include_once '_add_badge_modal.php';
?>