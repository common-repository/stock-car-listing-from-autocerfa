<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
?>

<div class="wrap license-key-wrapper">
    <h1><?php _e('License Key', 'autocerfa-connector'); ?></h1>
    <div class="notice-wrapper"></div>
    <?php
        wp_nonce_field('autocerfa_license_key');
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="ops_token"><?php _e('License key', 'autocerfa-connector'); ?></label></th>
            <td><input class="regular-text" type="password" id="ops_token" name="ops_token" value="<?= sanitize_text_field( get_option('ops_token') ) ?>" />
                <p class="description"><?php printf(__('You will get a license key after subscribing on OP Code Space. <a
                                href="%s" target="_blank">Upgrade To Autocerfa Connector Pro</a>', 'autocerfa-connector'), AUTOCERFA_PRODUCT_LINK); ?></a></p>
            </td>
        </tr>
    </table>
    <p>
        <button class="button button-primary save-license-key"><?php _e('Save', 'autocerfa-connector'); ?></button>
    </p>
</div>

<script>
    jQuery(function ($){
        $('.save-license-key').click(function (e){
            e.preventDefault();
            $('.license-key-wrapper .notice-wrapper').html(`<img src="/wp-includes/js/tinymce/skins/lightgray/img/loader.gif"> <?php _e('Verifying your provided License key...', 'autocerfa-connector'); ?>`);
            $.ajax({
              method: "POST",
              url: "<?= admin_url('/admin-ajax.php') ?>",
              data: { action: "autocerfa_save_license_key",
                  ops_token: $('[name="ops_token"]').val(),
                  _wpnonce: $('#_wpnonce').val()
              }
            })
              .done(function( response ) {
                 if(response.success){
                     $('.license-key-wrapper .notice-wrapper').html(`<div class="notice notice-success"><p>${response.data.message}</p></div>`);
                 }
                 else{
                     $('.license-key-wrapper .notice-wrapper').html(`<div class="notice notice-error"><p>${response.data.message}</p></div>`);
                  }
              }).error(function (){
                $('.license-key-wrapper .notice-wrapper').html(`<div class="notice notice-error"><p>${response.data.message}</p></div>`);
            })
        })
    })
</script>