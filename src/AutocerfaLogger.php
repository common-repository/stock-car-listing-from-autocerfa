<?php

class AutocerfaLogger
{
    protected static function path()
    {

        $autocerfa_log_file_code = get_option('autocerfa_log_file_code');

        if(empty($autocerfa_log_file_code)){
            $autocerfa_log_file_code =  bin2hex(random_bytes(16));
            update_option('autocerfa_log_file_code', $autocerfa_log_file_code, false);
        }

        $path = AUTOCERFA_UPLOAD_PATH . '/autocerfa-log/';

        if(!file_exists($path)){
            wp_mkdir_p($path);
        }

        return $path. $autocerfa_log_file_code . '.log';
    }

    public static function view()
    {
        if(!AUTOCERFA_DEBUG){
            return false;
        }

        return str_replace(AUTOCERFA_UPLOAD_PATH, AUTOCERFA_UPLOAD_URL, self::path());
    }

    public static function content()
    {
        if(!AUTOCERFA_DEBUG){
            return false;
        }

        return file_get_contents(self::path());
    }

    public static function log($message)
    {
        if(AUTOCERFA_DEBUG){
            error_log(date('Y-m-d H:i:s') .' '. $message . PHP_EOL, 3, self::path() );
        }
    }

    public static function delete()
    {
        wp_delete_file(self::path());
        delete_option('autocerfa_log_file_code');
    }
}