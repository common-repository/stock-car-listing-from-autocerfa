<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>

<!-- Modal -->
<div class="modal fade" id="addBadge" tabindex="-1" aria-hidden="true">
    <?php
    wp_nonce_field('autocerfa-badge-modal', 'autocerfa-badge-modal')
    ?>
    <input type="hidden" name="id">
    <input type="hidden" name="badge_id">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Badge</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="form-table">

                    <tr>
                        <th scope="row"><label for="color_picker"><?php _e('Badge Text',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <input type="text" id="autocerfa_badge_text" name="autocerfa_badge_text"
                                   value=""
                            />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="autocerfa_badge_bg_color"><?php _e('Badge Background Color',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <input type="color" id="autocerfa_badge_bg_color" name="autocerfa_badge_bg_color"
                                   value=""
                            />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="autocerfa_badge_txt_color"><?php _e('Badge Text Color',
                                    'autocerfa-connector'); ?></label></th>
                        <td>
                            <input type="color" id="autocerfa_badge_txt_color" name="autocerfa_badge_txt_color"
                                   value=""
                            />
                        </td>
                    </tr>

                </table>
                <div class="text-center">
                    <div class="autocerfa-cmn-btn autocerfa-badge-save">Save</div>
                </div>
            </div>
        </div>
    </div>
</div>