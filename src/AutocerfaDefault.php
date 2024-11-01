<?php

class AutocerfaDefault
{
    const SEARCH_BOX_MIN_PRICE = 0;
    const SEARCH_BOX_MAX_PRICE = 200000;

    protected $price;

    public function __construct()
    {
        $this->price = get_option('autocerfa_price');
    }

    public function get_min_price()
    {
        return empty($this->price) ? self::SEARCH_BOX_MIN_PRICE : $this->price['min'];
    }

    public function get_max_price()
    {
        return empty($this->price) ? self::SEARCH_BOX_MAX_PRICE : $this->price['max'];
    }


}