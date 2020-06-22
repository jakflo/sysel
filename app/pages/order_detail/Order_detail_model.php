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

class Order_detail_model extends Models {
    /**
     * @var int
     */
    protected $order_id, $client_id;
    
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
    }
    
    public function get_order_detail() {
        $array_tools = new Array_tools;
        $date_tools = new Date_tools;
        $ord_model = new Orders_model($this->env);
        $basic_info = $array_tools->null_na_empty_string($this->basic_info);
        $basic_info['added'] = $date_tools->en_datetime_na_cz($basic_info['added']);        
        $basic_info['status_name'] = $ord_model->get_status_name($basic_info['status']);        
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
}
