<?php

class AutocerfaBadge extends AbstractModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix.'autocerfa_badges';
    }

    public static function default()
    {
        $yellow = AutocerfaMisc::color1();
//        $blue = AutocerfaMisc::color2();
        $black = AutocerfaMisc::color3();
        return (object) [
            'background_color' => $yellow,
            'text_color'       => $black,
            'label'            => 'Vendu',
            'badge_id'         => 1
        ];
    }

    public function get_by_id($id)
    {
        if(empty(get_option('opcodespace_subscription'))){
            return false;
        }
        $badge = $this->getRow(['badge_id' => $id]);

        if(!empty($badge)){
            return $badge;
        }

        return self::default();
    }

}