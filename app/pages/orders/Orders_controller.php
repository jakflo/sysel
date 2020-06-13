<?php

namespace Sysel\Pages\Orders;
use Sysel\Conf\Controllers;
use Sysel\Pages\Orders\Orders_model;
use Sysel\Utils\Strankovac;
use Sysel\Pages\Polozky_ve_sklade\Get_order_by_butts;

class Orders_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Objednávky';
    
    public function zobraz() {
        $model = new Orders_model($this->env);
        $array_tools = new \Sysel\Utils\Array_tools;
        if (count($_GET) > 0) {
            $get = $model->sanitize_get($_GET);            
            $this->page = $get['page'];
            $this->order_by = $get['order_by'];            
        }
        else {
            $get = array();
            $this->page = 1;
        }
        if (empty($this->order_by)) {
            $this->order_by = 'o.added desc';            
        }
        $this->get_order_by_butts = new Get_order_by_butts($this->order_by);
        $this->pocet_zaznamu = $model->get_pocet_zaznamu($get);
        if ($this->pocet_zaznamu > 0) {
            $strankovac = new Strankovac($this->pocet_zaznamu, 25, $this->page);
            $this->seznam = $model->get_rows($strankovac);
            $get['page'] = $this->page = $strankovac->get_page();
            $get_tmp = $get;
            $get_tmp['page'] = ':page:';
            $url = "{$this->webroot}/orders?{$array_tools->asoc_na_uri($get_tmp)}";                
            $this->strankovac = $strankovac->get_html($url);
        }
        else {
            $this->seznam = array();
            $this->page = 1;
        }
        
        $this->status_texts = $model->get_statuses_array();
        $this->form_save = base64_encode(json_encode($get));        
        
        require "{$this->folder}/orders.php";                
    }
}
