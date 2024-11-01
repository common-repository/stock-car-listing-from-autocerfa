<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @param $file_path
 * @param $variables
 * @return false|string
 */
if(!function_exists('get_view')) {
    function get_view($file_path, $variables = null)
    {

        if ($variables !== null) {
            extract($variables, EXTR_PREFIX_SAME, "autocerfa");
        }

        ob_start();
        include (string)$file_path;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

/**
 * @param boolean $success
 * @param string $message
 * @param array $data
 * @return array
 */
if(!function_exists('_return')) {
    function _return($success, $message = "", $data = [])
    {
        $data['success'] = $success;
        $data['message'] = $message;

        return $data;
    }
}


if(!function_exists('autocerfa_read_more')) {
    function autocerfa_read_more($des, $link)
    {
        if (strlen($des) > 150) {
            $stringCut = substr($des, 0, 150);
            $endPoint = strrpos($stringCut, ' ');
            $des = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $des .= ' ... <a href="' . $link . '" >'.__('Read More', 'autocerfa-connector').'</a>';
        }
        return $des;
    }
}

/**
 * Recursive sanitation for an array
 * 
 * @param $array
 *
 * @return mixed
 */

if (!function_exists('autocerfa_recursive_sanitize_text_field')) {
    function autocerfa_recursive_sanitize_text_field($array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = autocerfa_recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }
        return $array;
    }
}