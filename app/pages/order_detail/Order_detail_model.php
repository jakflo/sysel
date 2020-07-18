<?php

namespace Sysel\Pages\Order_detail;
use Sysel\Conf\Models;
use Sysel\conf\Exceptions\Order_exception;
use Sysel\Conf\Env;
use Sysel\Pages\Orders\Orders_model;
use Sysel\Data_objects\Orders;
use Sysel\Data_objects\Client;
use Sysel\Data_objects\Polozky_brief;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Date_tools;
use Exception;

class Order_detail_model extends Models {
    /**
     * @var int
     */
    protected $order_id, $client_id, $status;
    
    /**
     * @var array
     */
    protected $basic_info;
    
    public function __construct(Env $env, $order_id) {
        parent::__construct($env);        
        $this->order_id = $order_id;
        $this->basic_info = $this->env->db->dotaz_radek(
                "SELECT o.id as ord_id, client_id, o.added, o.note, o.status, 
                company_name, forname, surname, middlename, title, email, phone, 
                street, city, country  
                FROM `order` o join client c on o.client_id=c.id join address a on c.address_id=a.id where o.id=?", 
                array($order_id)
                );
        if (!$this->basic_info) {
            throw new Order_exception('Objednavka nenalezena', 1);
        }
        $this->client_id = $this->basic_info['client_id'];
        $this->status = $this->basic_info['status'];
    }
    
    public function get_order_detail() {
        $array_tools = new Array_tools;
        $date_tools = new Date_tools;
        $ord_model = new Orders_model($this->env);
        $basic_info = $array_tools->null_na_empty_string($this->basic_info);
        $basic_info['added'] = $date_tools->en_datetime_na_cz($basic_info['added']);
        $basic_info['status_is_select'] = is_array($ord_model->get_permited_status_changes($this->status));
        if ($basic_info['status_is_select']) {
            $basic_info['status_select'] = $ord_model->get_status_changes_select($this->status);
        }
        else {
            $basic_info['status_name'] = $ord_model->get_status_name($this->status);
        }
        $data_obj = new Orders;
        $data_obj->load_array($basic_info);
        return $data_obj;        
    }
    
    public function get_client_detail() {
        $array_tools = new Array_tools;
        $client_info = $array_tools->null_na_empty_string($this->basic_info);
        $client_info['id'] = $client_info['client_id'];
        $data_obj = new Client;
        $data_obj->load_array($client_info);
        return $data_obj;
    }
    
    public function get_items() {
        $items = $this->env->db->dotaz_vse(
                "select item_id as it_id, amount as pocet, name as it_name  
                from order_has_item oi join item_detail ii on oi.item_id=ii.id where oi.order_id=?", 
                array($this->order_id)
                );
        if (!$items) {
            return false;
        }
        $data_obj = new Polozky_brief;
        $data_obj = $data_obj->load_2d_array($items);
        return $data_obj;
    }
    
    public function get_status() {
        return $this->status;
    }
    
    public function validate_status_change($status) {
        $ord_model = new Orders_model($this->env);
        return in_array($status, $ord_model->get_permited_status_changes($this->status));
    }
    
    public function change_status(int $status) {
        $item_status = $this->order_status_to_items_status($status);
        if ($item_status != $this->order_status_to_items_status($this->status)) {
            $order_term = $item_status == 1? ', order_id=null' : '';
            $this->env->db->sendSQL(
                    "update item set status=:stat {$order_term} where order_id=:o_id and status!=4", 
                    array(':stat' => $item_status, ':o_id' => $this->order_id)
                    );
        }
        $this->env->db->sendSQL(
                "update `order` set status=:status, last_edited=:now where id=:o_id", 
                array(':status' => $status, ':now' => date('Y-m-d H:i:s'), ':o_id' => $this->order_id)
                );        
    }
    
    public function order_status_to_items_status(int $status) {
        switch ($status) {
            case 1:
            case 6:
                return 1;
            case 2:
            case 3:
                return $status;
            case 4:
            case 5:
                return 4;
            default:
                throw new Exception('Neznamy typ statusu objednavky');
        }        
    }
}
