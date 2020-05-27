<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Conf\Models;
use Sysel\conf\Exceptions\Item_form_search_exception;
use Sysel\Utils\Simple_validator;
use Sysel\Utils\Array_tools;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\Pages\Najit_polozku\Items_search;
use Sysel\Data_objects\Search_missing_items;

class Najit_polozku_model extends Models {
    public function sanitize_form(array $post) {
        unset($post['sent']);
        $sklady = new Sklady_model($this->env);
        $polozky = new Polozky_model($this->env);
                
        if (isset($post['warelist'])) {
            $ware_ids = $sklady->get_ids();
            foreach ($post['warelist'] as $k => $v) {
                $ware = new Simple_validator($k);
                if (!$ware->not_empty()->in_array($ware_ids)->get_result()) {
                    unset($post['warelist'][$k]);
                }
            }
            if (count($post['warelist']) == 0) {
                unset($post['warelist']);                
            }
        }
        
        if (isset($post['items'])) {
            $item_ids = $polozky->get_ids();
            foreach ($post['items'] as $k => $v) {
                $id_valid = new Simple_validator($v['item_id']);
                $id_valid = $id_valid->not_empty()->in_array($item_ids)->get_result();
                $amount_valid = new Simple_validator($v['item_amount']);
                $amount_valid = $amount_valid->is_int()->greater_than(0, true)->get_result();
                if (!$id_valid or !$amount_valid) {
                    unset($post['items'][$k]);
                }
            }
            if (count($post['items']) == 0) {
                unset($post['items']);
            }
        }
        
        $e = new Item_form_search_exception('');
        $e->set_post($post);
        if (!isset($post['items'])) {
            throw $e->set_message('Zadajte alespoň 1 položku k vyhledání.');
        }
        if (!isset($post['ware_all']) and !isset($post['warelist'])) {
            throw $e->set_message('Zadajte alespoň 1 sklad k prohledání, nebo nechte prohledat všechny sklady.');
        }
        return $post;        
    }
    
    public function search(array $item_list, array $ware_list) {
        $array_tools = new Array_tools;
        $item_list = $array_tools->vicepole_na_sum_asoc($item_list, 'item_id', 'item_amount');
        $search = new Items_search($this->env, array_keys($item_list), $ware_list);
        foreach ($item_list as $i_id => $i_amount) {            
            $search->search_item($i_id, $i_amount);            
        }
        return $search;
    }    
}
