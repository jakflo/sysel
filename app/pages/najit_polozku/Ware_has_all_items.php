<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Pages\Najit_polozku\Items_search;
use Sysel\Utils\Array_tools;
use Sysel\Data_objects\Seznam_skladu;

class Ware_has_all_items {
    /**
     * @var Items_search 
     */
    protected $items_search;
    
    /**
     * asoc pole s id a jmeny skladu     
     * @var array
     */
    protected $ware_list;
    
    public function __construct(Items_search $items_search) {
        $this->items_search = $items_search;
        $array_tools = new Array_tools;
        $this->ware_list = $array_tools->vicepole_na_value_asoc($items_search->get_polozky_ve_skladech(), 'w_id', 'w_name');        
    }
    
    public function smaz_pokud_neni_v_array(array $ware_ids) {
        foreach ($this->ware_list as $k => $v) {
            if (!in_array($k, $ware_ids)) {
                unset($this->ware_list[$k]);
            }
        }        
    }
    
    public function get_ware_list() {
        $list = array();
        foreach ($this->ware_list as $w_id => $w_name) {
            $ware_data = new Seznam_skladu;
            $ware_data->load_array(array(
                'id' => $w_id, 
                'name' => $w_name
            ));
            $list[] = $ware_data;
        }
        return $list;
    }
}
