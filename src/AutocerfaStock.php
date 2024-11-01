<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AutocerfaStock extends AbstractModule
{
	public $table;
	public $db;

	public $params;
    
    public function __construct()
    {
        parent::__construct();
        $this->table   = $this->db->prefix . "autocerfa_stock";
    }

    public function getAutocerfaStock( $limit = 0, $offset = 0,  $is_sum = false, $invisible = true)
    {
        $limit_query = "";
        if ($limit > 0) {
            $limit_query .= " LIMIT $limit OFFSET $offset ";
        }

         $fields = "s.*";

        if ($is_sum) {
            $fields = "COUNT(*)";
            $limit_query = "";
        }

        $invisible_query = '';
        if(!$invisible){
            $invisible_query = 'AND (s.hidden IS NULL OR s.hidden = 0 )';
        }

        $search = '';

        if(!empty($this->params)){
            foreach ($this->params as $column => $param) {
                if(empty($param) || $column === 'min_price' || $column === 'max_price') {
                    continue;
                }

                $search .= " AND s.{$column} = '{$param}'";
            }

            if(!empty($this->params['min_price']) && !empty($this->params['max_price'])){
                $search .= " AND (s.price >= {$this->params['min_price']} AND s.price <= {$this->params['max_price']})";
            }
            elseif(!empty($this->params['min_price']) ){
                $search .= " AND s.price >= {$this->params['min_price']}";
            }
            elseif(!empty($this->params['max_price']) ){
                $search .= " AND s.price <= {$this->params['max_price']}";
            }
        }

        $query = "SELECT $fields FROM $this->table AS s WHERE s.active = 1 {$invisible_query} {$search}
                     {$limit_query}";

        if ($is_sum) return $this->db->get_var($query);

        return $this->db->get_results($query);    
    }

    public function valueList($column_name)
    {
        $query = "SELECT DISTINCT {$column_name} FROM $this->table WHERE active = 1 AND hidden = 0 ORDER BY {$column_name}";
        return $this->db->get_results($query, ARRAY_A);
    }

}