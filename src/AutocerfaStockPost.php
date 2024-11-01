<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}

class AutocerfaStockPost
{
    protected $post_type = 'autocerfa_car';
    protected $post_id;
    public $total;
    public $cars;
    public $params;

    public function update_post_meta($key, $value)
    {
        update_post_meta($this->post_id, $key, $value);
    }

    public function getByLeadId($lead_id)
    {
        $cars = get_posts([
            'post_type'  => $this->post_type,
            'meta_key'   => 'lead_id',
            'meta_value' => $lead_id
        ]);

        return empty($cars) ? false : $cars[0];
    }

    public function updateOrInsert($lead_id, $data)
    {
        $car = $this->getByLeadId($lead_id);

        $post_data = [
            'post_title'  => $data['title'],
            'post_status' => 'publish',
            'post_type'   => $this->post_type,
            'meta_input'  => $data
        ];

        if (!empty($car)) {
            $post_data['ID'] = $car->ID;
        }

        wp_insert_post($post_data);
    }

//    public function get($per_page, $page)
//    {
//        $post_data = [
//            'post_status'    => 'publish',
//            'post_type'      => $this->post_type,
//            'posts_per_page' => $per_page,
//            'page'           => $page
//        ];
//    }

    public function query($per_page, $page)
    {
        $arg = [
            'post_status'    => 'publish',
            'post_type'      => $this->post_type,
            'posts_per_page' => $per_page,
            'paged'           => $page,
            'meta_key'       => 'lead_id',
            'orderby'        => 'meta_value',
            'order'          => 'DESC'
        ];

        $meta_query = [
            'relation' => 'AND'
        ];

        if(!empty($this->params['model'])){
            $meta_query[] = [
                'key' => 'model',
                'value' => $this->params['model'],
                'compare' => '='
            ];
        }

        if(!empty($this->params['marque'])){
            $meta_query[] = [
                'key' => 'marque',
                'value' => $this->params['marque'],
                'compare' => '='
            ];
        }
        if(!empty($this->params['energy'])){
            $meta_query[] = [
                'key' => 'energy',
                'value' => $this->params['energy'],
                'compare' => '='
            ];
        }

        if(!empty($this->params['gear_box'])){
            $meta_query[] = [
                'key' => 'gear_box',
                'value' => $this->params['gear_box'],
                'compare' => '='
            ];
        }

        if(!empty($this->params['min_price']) && !empty($this->params['max_price'])){
            $meta_query[] = [
                'relation' => 'AND',
                [
                    'key'       => 'price',
                    'value'     => $this->params['min_price'],
                    'compare'   => '>=',
                    'type'      => 'NUMERIC',
                ],
                [
                    'key'       => 'price',
                    'value'     => $this->params['max_price'],
                    'compare'   => '<=',
                    'type'      => 'NUMERIC',
                ]
            ];
        } elseif (!empty($this->params['min_price'])) {
            $meta_query[] = [
                'key'       => 'price',
                'value'     => $this->params['min_price'],
                'compare'   => '>=',
                'type'      => 'NUMERIC',
            ];
        }
        elseif(!empty($this->params['max_price']) ){
            $meta_query[] = [
                'key'       => 'price',
                'value'     => $this->params['max_price'],
                'compare'   => '<=',
                'type'      => 'NUMERIC',
            ];
        }

        if(!empty($this->params['exclude_hidden'])){
            $meta_query[] = [
                'relation' => 'OR',
                [
                    'key'       => 'hidden',
                    'compare'   => 'NOT EXISTS',
                ],
                [
                    'key'       => 'hidden',
                    'value'     => 1,
                    'compare'   => '!=',
                    'type'      => 'NUMERIC',
                ],

            ];
        }

        if(!empty($this->params['lead_updated_date'])){
            $meta_query[] = [
                'key'       => 'lead_updated_date',
                'value'     => $this->params['lead_updated_date'],
                'compare'   => '<',
                'type'      => 'NUMERIC',
            ];
        }

        $arg['meta_query'] = $meta_query;

        $query = new WP_Query($arg);
        $this->total = $query->found_posts;
        $this->cars = $query->posts;
    }

    public function getUniqueValue($key)
    {
        global $wpdb;
        $query = "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} as m WHERE m.meta_key = '$key' AND (SELECT p.post_type FROM {$wpdb->posts} AS p WHERE p.id = m.post_id) = '$this->post_type'";
        return $wpdb->get_results($query);
    }

    public function getMinMaxPrice()
    {
        $arg = [
            'post_status'    => 'publish',
            'post_type'      => $this->post_type,
            'posts_per_page' => -1,
            'meta_key'       => 'price',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'relation' => 'OR',
                    [
                        'key'       => 'hidden',
                        'compare'   => 'NOT EXISTS',
                    ],
                    [
                        'key'       => 'hidden',
                        'value'     => 1,
                        'compare'   => '!=',
                        'type'      => 'NUMERIC',
                    ],

                ]
            ]
        ];

        $query = new WP_Query($arg);
//        $this->total = $query->found_posts;
//        $this->cars = $query->posts;

        return [
            'min' => $this->price($query->posts[0]->price),
            'max' => $this->price($query->posts[count($query->posts) - 1]->price)
        ];
    }

    public function getMake()
    {
        $arg = [
            'post_status'    => 'publish',
            'post_type'      => $this->post_type,
            'posts_per_page' => -1,
            'meta_key'       => 'marque',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'relation' => 'OR',
                    [
                        'key'       => 'hidden',
                        'compare'   => 'NOT EXISTS',
                    ],
                    [
                        'key'       => 'hidden',
                        'value'     => 1,
                        'compare'   => '!=',
                        'type'      => 'NUMERIC',
                    ],

                ]
            ]
        ];

        $query = new WP_Query($arg);
//        $this->total = $query->found_posts;
//        $this->cars = $query->posts;

        $makes = [];
        foreach ($query->posts as $_post) {
            $makes[] = $_post->marque;
        }

        return array_unique($makes);
    }


    public function getModelByMake($make)
    {
        $arg = [
            'post_status'    => 'publish',
            'post_type'      => $this->post_type,
            'posts_per_page' => -1,
            'meta_key'       => 'model',
            'orderby'        => 'meta_value',
            'order'          => 'ASC'
        ];

        if(!empty($make)){
            $arg['meta_query'] = [
                [
                    'key' => 'marque',
                    'value' => $make,
                    'compare' => '='
                ]
            ];
        }

        $query = new WP_Query($arg);
//        $this->total = $query->found_posts;
//        $this->cars = $query->posts;

        $models = [];
        foreach ($query->posts as $_post) {
            $models[] = $_post->model;
        }

        return array_unique($models);
    }

    public function ImageReadyForDownload()
    {
        $arg = [
            'post_status'    => 'publish',
            'post_type'      => $this->post_type,
            'posts_per_page' => 5,
            'meta_query'     => [
                [
                    'key' => 'raw_images',
                    'compare' => 'EXISTS'
                ]
            ]
        ];

        return (new WP_Query($arg))->posts;
    }

    public function price($price)
    {
        $price = preg_replace('/\.\d\b/', '', $price);
        $price = preg_replace('/\.\d{2}\b/', '', $price);
        $price = preg_replace('/[^0-9,]+/', '', $price);
        return (int)(str_replace(',', '.', $price));
    }
}