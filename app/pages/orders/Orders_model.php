<?php

namespace Sysel\Pages\Orders;
use Sysel\Conf\Models;
use Sysel\Utils\Simple_validator;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Date_tools;
use Sysel\Data_objects\Orders;
use Sysel\Pages\Orders\Objednavky_s_filtry;
use Sysel\Utils\Strankovac;
use Sysel\Pages\Polozky_ve_sklade\Validate_order_by;

class Orders_model extends Models {
    protected $statuses = array(
        1 => 'Nová', 2 => 'Připravena k odeslání', 
        3 => 'Expedováno', 4 => 'Probíhá reklamace', 5 => 'Reklamace vyřízena'
    );
    
    /**
     * @var Objednavky_s_filtry
     */
    protected $objednavky_s_filtry;


    public function __construct($env) {
        parent::__construct($env);
        $this->objednavky_s_filtry = new Objednavky_s_filtry($this->env);
    }
    
    public function zobraz() {
        $array_tools = new Array_tools;
        $date_tools = new Date_tools;
        $data_obj = new Orders;
        $data = $this->env->db->dotaz_vse(
                "SELECT o.id, o.added, last_edited, o.note, status, cn.comb_name  
                FROM `order` o 
                join client c on o.client_id=c.id 
                join (select id, if(company_name is null,concat(forname,' ',surname),concat(company_name,' (',forname,' ',surname,')')) as comb_name from client) as cn on c.id=cn.id 
                where o.added>'2020-01-01' 
                order by o.added desc"
                );
        $data = $array_tools->null_na_empty_string_vicepole($data);
        foreach ($data as &$row) {
            $row['added'] = $date_tools->en_datetime_na_cz($row['added']);
            if (!empty($row['last_edited'])) {
                $row['last_edited'] = $date_tools->en_datetime_na_cz($row['last_edited']);
            }
            $row['status_name'] = $this->get_status_name($row['status']);
        }
        $data_obj = $data_obj->load_2d_array($data);
        return $data_obj;
    }   
    
    public function sanitize_get($get) {
        $array_tools = new Array_tools;
        $get = $array_tools->trim_values($get);
        $mark_array = array('less' => '<=', 'equal' => '=', 'more' => '>=');
        $order_by_valid = new Validate_order_by(array('o.id', 'o.added', 'o.last_edited', 'o.note', 'cn.comb_name'));
        foreach ($get as $k => &$v) {
            if (empty($v)) {
                unset($get[$k]);
                continue;
            }
            $val_valid = new Simple_validator($v);
            switch ($k) {
                case 'o_id':
                    if (!$val_valid->is_int()->greater_than(0, true)->get_result()) {
                        unset($get[$k]);
                    }
                    break;
                case 'add_sign':
                case 'edit_sign':
                    if (!$val_valid->not_empty()->in_array(array_keys($mark_array))->get_result()) {
                        unset($get[$k]);
                    }                    
                    break;
                case 'add_date':
                case 'edit_date':
                    if (!$val_valid->not_empty()->is_date_valid()->get_result()) {
                        unset($get[$k]);
                    }
                    break;
                case 'note':
                case 'client':
                    break;
                case 'status':
                    if (!$val_valid->not_empty()->in_array(array_keys($this->statuses))->get_result()) {
                        unset($get[$k]);
                    }
                    break;
                case 'order_by':
                    if (!$order_by_valid->validate($v)) {
                        unset($get[$k]);
                    }
                    break;
                default :
                    unset($get[$k]);                    
            }
        }
        return $get;
    }
    
    public function get_pocet_zaznamu(array $get) {
        $this->objednavky_s_filtry->init($get);
        return $this->objednavky_s_filtry->get_count();        
    }
    
    public function get_rows(Strankovac $page) {
        $array_tools = new Array_tools;
        $date_tools = new Date_tools;
        $data_obj = new Orders;
        $data = $this->objednavky_s_filtry->get_rows($page, 'o.id');
        $data = $array_tools->null_na_empty_string_vicepole($data);
        foreach ($data as &$row) {
            $row['added'] = $date_tools->en_datetime_na_cz($row['added']);
            if (!empty($row['last_edited'])) {
                $row['last_edited'] = $date_tools->en_datetime_na_cz($row['last_edited']);
            }
            $row['status_name'] = $this->get_status_name($row['status']);
        }
        $data_obj = $data_obj->load_2d_array($data);
        return $data_obj;               
    }
    
    public function get_statuses_array() {
        return $this->statuses;
    }
    
    public function get_status_name(int $status) {
        return $this->statuses[$status];        
    }
    
}
