<?php
if (!defined('ABSPATH')) exit;

class AutocerfaFiles
{
    protected $path;
    protected $url;
    protected $filename;
    public $source_file;
    public $source_url;
    protected $sizes = [
        '800-630' => [800, 630], // all image
        '120-80'  => [120, 80] // all image
    ];

    protected $thumbnail_sizes = [
        '375-345' => [375, 345], // thumbnail
        '540-405' => [540, 405], // thumbnail
        '800-630' => [800, 630], // all image
        '120-80'  => [120, 80] // all image
    ];

    public function __construct()
    {
        $this->getPath();
        $this->getUrl();
    }


    protected function getPath()
    {
        $this->path = AUTOCERFA_UPLOAD_PATH . '/autocerfa/' . date('Y') . '/' . date('m') . '/';
        if (!is_dir($this->path)) {
            wp_mkdir_p($this->path);
        }

        return $this->path;
    }

    protected function getUrl()
    {
        $this->url = str_replace(AUTOCERFA_UPLOAD_PATH, AUTOCERFA_UPLOAD_URL, $this->path);
        return $this->url;
    }

    public function download($image_url)
    {
        $filename = basename($image_url);
        $unique_file_name = wp_unique_filename($this->path, $filename);
        $this->source_file = $this->path . $unique_file_name;
        $this->source_url = $this->url . $unique_file_name;
        $response = wp_remote_get($image_url);

        if (is_wp_error($response)) {
            return $this;
        }

        $body = wp_remote_retrieve_body($response);

        file_put_contents($this->source_file, $body);
        return $this;
    }

    protected function fileName($size_name)
    {
        $info     = pathinfo($this->source_file);

        $uniqid = uniqid('', true);

        $new_path = "{$this->path}{$info['filename']}-{$uniqid}-{$size_name}.{$info['extension']}";
        $new_url  = "{$this->url}{$info['filename']}-{$uniqid}-{$size_name}.{$info['extension']}";

        return ['file' => $new_path, 'url' => $new_url];
    }

    public function resize($is_thumbnail = false)
    {

        $files = [];
        $cropping = AutocerfaMisc::croppingImageAsAspectRatio();

        $sizes = $is_thumbnail ? $this->thumbnail_sizes : $this->sizes;

        foreach ($sizes as $size_name => $size) {
            $image = wp_get_image_editor($this->source_file);

            if (is_wp_error($image)) {
                echo $image->get_error_message();
                return false;
            }

            $new_file = $this->filename($size_name);
            if ($size_name === '120-80') {
                $image->resize($size[0], $size[1], true);
            }elseif ($size_name === '800-630') {
                $image->resize($size[0], $size[1]);
            } else {
                // Thumbnail
                $image->resize( $size[0], $size[1], !$cropping );
            }

            $result = $image->save($new_file['file']);

            if(is_wp_error($result)){
                AutocerfaLogger::log($result->get_error_message());
                return false;
            }

            AutocerfaLogger::log('Resized Image: ' . $new_file['file']);

            $files[$size_name] = ['file' => $result['path'], 'url' => str_replace(AUTOCERFA_UPLOAD_PATH, AUTOCERFA_UPLOAD_URL, $result['path'])];
        }

        return ['image' => $this->source_file, 'url' => $this->source_url, 'thumbnails' => $files];
    }
}
