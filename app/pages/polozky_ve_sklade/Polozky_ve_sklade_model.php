<?php

namespace Sysel\Pages\Polozky_ve_sklade;
use Sysel\Conf\Models;
use Sysel\Utils\Array_tools;
use Sysel\Pages\Polozky_ve_sklade\Seznam_s_filtry;
use Sysel\Data_objects\Polozky_brief;
use Sysel\Utils\Strankovac;
use Sysel\Utils\Date_tools;
use Sysel\Data_objects\Polozky_full;
use Sysel\Utils\Simple_validator;
use Exception;

class Polozky_ve_sklade_model extends Models {
    
    /**
     *
     * @var Seznam_s_filtry
     */
    protected $filtred_list;
    protected $status_na_int = array('volná'=>1, 'rezervovaná'=>2, 'expedovaná'=>3, 'reklamace'=>4);


    public function __construct($env) {
        parent::__construct($env);
        $this->filtred_list = new Seznam_s_filtry($env);                
    }
    
    public function zobraz_brief() {
        $array_tools = new Array_tools;
        $data_brief = new Polozky_brief; 
        $all_wars = $this->env->db->dotaz_vse(
                "select w.name as w_name, ide.name as it_name, count(ide.id) as pocet 
                from item i join item_detail ide on i.item_detail_id=ide.id 
                join warehouse w on i.warehouse_id=w.id 
                where i.status in(1,2) group by i.item_detail_id, i.warehouse_id 
                order by warehouse_id, i.item_detail_id"
                );
        if ($all_wars) {
            $uzite_sklady = array_column($all_wars, 'w_name');
            $result = array();
            foreach ($uzite_sklady as $sklad) {
                $result[$sklad] = $data_brief->load_2d_array($array_tools->hledej_ve_vicepoli($all_wars, $sklad, 'w_name'));
            }
            return $result;
        }
        else {
            return false;
        }                
    }
    
    public function sanitize_get($get) {
        foreach ($get as $k => &$v) {
            $v = trim($v);
            if (empty($v)) {
                unset($get[$k]);
                continue;
            }
            switch($k) {
                case 'added_znak':
                    $allowed = array('less', 'equal', 'more');
                    if (!in_array($v, $allowed)) {                        
                        unset($get['added_znak']);
                        unset($get['added']);
                    }                    
                    break;
                case 'added':
                    $date_good = new Simple_validator($v);
                    if (!$date_good->not_empty()->is_date_valid()->get_result()) {
                        unset($get['added_znak']);
                        unset($get['added']);                        
                    }
                    break;
                case 'status':
                    $good = new Simple_validator($v);
                    if (!$good->is_int()->greater_than(0, true)->less_than(4, false)->get_result()) {
                        unset($get['status']);
                    }
                    break;
                case 'order_by':
                    $order_by_part = explode(' ', $v);
                    $allowed_1 = array('w.name', 'ide.name', 'i.added', 'i.order_id', 'comb_name');
                    $allowed_2 = array('asc', 'desc');
                    if (!in_array($order_by_part[0], $allowed_1) or !in_array($order_by_part[1], $allowed_2)) {
                        $v = 'i.added desc';
                    }
                    break;
            }            
        }
        $get['order_by'] = empty($get['order_by'])? 'i.added desc':$get['order_by'];
        return $get;        
    }
    
    public function status_na_string(int $int) {
        $tr = array_flip($this->status_na_int);
        if (!isset($tr[$int])) {
            throw new Exception('Neznámý typ statusu');
        }
        else {
            return $tr[$int];
        }        
    }
    
    public function get_pocet_zaznamu(array $get) {
        $this->filtred_list->init($get);
        return $this->filtred_list->get_count();        
    }
    
    public function get_rows(Strankovac $page) {
        $dates = new Date_tools;
        $array_tools = new Array_tools;
        $data = new Polozky_full;
        $rows = $this->filtred_list->get_rows($page);
        $rows = $array_tools->null_na_empty_string_vicepole($rows);
        foreach ($rows as &$r) {
            $r['added'] = $dates->en_date_na_cz($r['added']);
            $r['status'] = $this->status_na_string($r['status']);
        }
        return $data->load_2d_array($rows);        
    }
    
}
