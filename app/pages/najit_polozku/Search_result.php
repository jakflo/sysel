<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Pages\Najit_polozku\Items_search;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Xss_fix;
use Sysel\Data_objects\Search_result_row;
use Sysel\Data_objects\Search_result_cell;

class Search_result {
    /**
     * 2D array s výsledky hledání; získáno z Items_search
     * @var array
     */
    protected $item_search;
    
    /**
     * @var Items_search
     */
    protected $item_search_obj;
    
    /**
     * seznam IDs skladů (tvoří sloupce tabulky)
     * @var array
     */
    protected $ware_list_ids;


    public function __construct(Items_search $item_search) {
        $this->item_search_obj = $item_search;
        $this->item_search = $item_search->get_polozky_ve_skladech();        
    }
    
    public function get_ware_list() {
        $array_tools = new Array_tools;
        $xss = new Xss_fix;
        $this->ware_list_ids = array_unique(array_column($this->item_search, 'w_id'));
        $names = array();
        foreach ($this->ware_list_ids as $id) {
            $name = $array_tools->hledej_ve_vicepoli($this->item_search, $id, 'w_id');
            $names[] = $xss->fix_string($name[0]['w_name']);
        }
        return $names;
    }
    
    public function get_item_ids() {
        return array_unique(array_column($this->item_search, 'it_id'));
    }
    
    public function get_row(int $item_id) {
        $array_tools = new Array_tools;
        $row_data_obj = new Search_result_row;
        $item_name = $array_tools->hledej_ve_vicepoli($this->item_search, $item_id, 'it_id')[0]['it_name'];
        $cells = array();
        foreach ($this->ware_list_ids as $w_id) {
            $result = $array_tools->hledej_ve_vicepoli_multi($this->item_search, array(
                'w_id' => $w_id, 
                'it_id' => $item_id
            ));
            if (count($result) == 0) {
                $kolik_z = "0 / {$this->item_search_obj->kolik_je_polozky_pozadovano($item_id)}";
                $color = 'red';
            }
            else {
                $kolik_z = $result[0]['kolik_z'];
                $color = $result[0]['staci']? 'green' : 'red';
            }
            $cell = new Search_result_cell;
            $cell->load_array(array(
                'kolik_z' => $kolik_z, 
                'color' => $color
            ));
            $cells[] = $cell;
        }
        $row_data_obj->load_array(array(
            'item_name' => $item_name, 
            'cells' => $cells
        ));
        return $row_data_obj;
    }
    
    public function is_empty() {
        return count($this->item_search) == 0;        
    }
}
