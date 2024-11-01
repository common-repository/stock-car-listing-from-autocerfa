<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AutocerfaNotification extends AbstractNotification
{
    public function backgroundProcessComplete($no_of_cars)
    {
        $to = get_option('admin_email');
        $subject = __('Autocerfa synchronization has been completed', 'autocerfa-connector');
        $body = __('<p>Hi,</p>', 'autocerfa-connector');
        $body .= sprintf(__('<p>Autocerfa connector has completed fetching %d cars and saving to your website.</p>', 'autocerfa-connector'), $no_of_cars);
        if(empty(get_option('opcodespace_subscription'))) {
            $body .= __('<p>You are using free version of plugin. Please <a href="https://www.opcodespace.com/wp-autocerfa">upgrade</a> to get all stock cars.</p>', 'autocerfa-connector');
        }

        $this->send($to, $subject, $body);
    }
}