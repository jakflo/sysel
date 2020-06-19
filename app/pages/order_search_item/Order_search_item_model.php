<?php

namespace Sysel\Pages\Order_search_item;
use Sysel\Conf\Models;
use Sysel\Conf\Env;
use Sysel\conf\Exceptions\Order_exception;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Protected_in;

class Order_search_item_model extends Models {
    
    /**
     * @var int
     */
    protected $order_id;
    
    /**
     * @var array
     */
    protected $items_needed;

    public function __construct(Env $env, $order_id) {
        parent::__construct($env);
        $this->order_id = $order_id;        
    }
    
    public function get_items_needed() {
        $array_tools = new Array_tools;        
        $treba = $this->env->db->dotaz_vse(
                "select item_id, amount as item_amount from order_has_item where order_id=?", 
                array($this->order_id)
                );
        if (!$treba) {
            throw new Order_exception('Objednavka nenalezena', 1);
        }
        $status = $this->env->db->dotaz_hodnota(
                "select status from `order` where id=?", 
                array($this->order_id)
                );
        if ($status != 1) {
            throw new Order_exception('Objednavka neni nova', 2);
        }
        
        $uz_je_na_sklade = $this->env->db->dotaz_vse(
                "SELECT item_detail_id as item_id, count(item_detail_id) as n FROM item where order_id=? group by item_detail_id", 
                array($this->order_id)
                );
        if ($uz_je_na_sklade) {
            foreach ($treba as $treba_key => &$treba_polozka) {
                $uz_je_na_sklade_polozka = $array_tools->hledej_ve_vicepoli($uz_je_na_sklade, $treba_polozka['item_id'], 'item_id');
                if (count($uz_je_na_sklade_polozka) > 0) {
                    $treba_polozka['item_amount'] -= $uz_je_na_sklade_polozka[0]['n'];
                }
                if ($treba_polozka['item_amount'] == 0) {
                    unset($treba[$treba_key]);
                }
            }
        }
        
        $this->items_needed = $treba;
        return $treba;
    }
    
    public function prirad_polozky_k_objednavce(array $ware_list) {
        $all_found = true;
        $array_tools = new Array_tools;
        $protected_in = new Protected_in;
        $protected_in->add_array('it_id', array_column($this->items_needed, 'item_id'));
        if (count($ware_list) > 0) {
            $protected_in->add_array('ware_id', $ware_list);
            $ware_id_term = "warehouse_id in({$protected_in->get_tokens('ware_id')}) and ";
        }
        else {
            $ware_id_term = '';
        }
        
        $nalezeno = $this->env->db->dotaz_vse(
                "SELECT id, warehouse_id, item_detail_id as item_id FROM item "
                . "where {$ware_id_term}"
                . "item_detail_id in({$protected_in->get_tokens('it_id')}) and status=1 "
                . "order by warehouse_id, item_detail_id, added", $protected_in->get_data()
                );
        if (!$nalezeno) {
            throw new Order_exception('Zadna polozka nebyla nelezena', 3);
        }
        
        foreach ($this->items_needed as $item) {
            $item_ids = $array_tools->hledej_ve_vicepoli($nalezeno, $item['item_id'], 'item_id');
            if (count($item_ids) == 0) {
                $all_found = false;
                continue;
            }
            elseif (count($item_ids) < $item['item_amount']) {
                $all_found = false;
            }
            else {
                $item_ids = array_slice($item_ids, 0, $item['item_amount']);
            }
            $this->env->db->sendSQL(
                    "update item set order_id=2, status=2 "
                    . "where id in({$array_tools->implode_pro_in(array_column($item_ids, 'id'))})"
                    );
        }
        
        if ($all_found) {
            $this->env->db->sendSQL("update `order` set status=2 where id=?", array($this->order_id));
        }
        return $all_found;
    }
    
}
