<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AutocerfaOpcodespace
{
    protected $token;
    protected $url = 'https://www.opcodespace.com/wp-admin/admin-post.php?action=check_autocerfa_validation';

    public function __construct($token = null)
    {
        if($token === null){
            $this->token = sanitize_text_field(get_option('ops_token'));
        }
        else{
            $this->token = $token;
        }
    }

    public function isPaid()
    {
        if(empty($this->token)){
            return false;
        }

        return $this->request()['success'];
    }

    public function request()
    {
        $response = wp_remote_request($this->url . '&site=' . get_site_url() . '&version=' . AUTOCERFA_PLUGIN_VERSION, [
            'method'  => 'GET',
            'headers' => ['Authorization' => $this->token],
            'sslverify' => apply_filters( 'https_local_ssl_verify', false )
        ]);

        $code = wp_remote_retrieve_response_code($response);

        if($code != 200){
            throw new Exception(wp_remote_retrieve_body($response));
        }

        // TODO: Log
        return json_decode(wp_remote_retrieve_body($response));
    }

    public function setSubscriptionStatus()
    {
        try {
            $response = $this->request();
            if ($response->success) {
                update_option('opcodespace_subscription', true);
                return _return(true, __('Congratulation! Your account has been activated.', 'autocerfa-connector'));
            }

            update_option('opcodespace_subscription', false);

            return _return(false, $response->data->message);
        }
        catch (Exception $e){
            return _return(false, $e->getMessage());
        }
    }
}