<?php

class AutocerfaImageDownloadAsync extends WP_Async_Request
{
    /**
     * @var string
     */
    protected $prefix = 'autocerfa';

    /**
     * @var string
     */
    protected $action = 'image_download';

    protected function handle()
    {
        // TODO: Implement handle() method.
        $post_id = $_POST['post_id'];
        $image = $_POST['image'];
        AutocerfaLogger::log('Image download started for post #' . $post_id . ' Image: ' . $image);
        $images = get_post_meta($post_id, 'images', true);
        $images[] = (new AutocerfaFiles)->download($image)->resize();
        update_post_meta($post_id, 'images', $images);
    }
}