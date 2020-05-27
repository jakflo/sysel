<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Conf\Controllers;
use Sysel\Pages\Najit_polozku\Najit_polozku_model;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\conf\Exceptions\Item_form_search_exception;
use Sysel\Pages\Najit_polozku\Search_result;

class Najit_polozku_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Hledat položky';
    
    public function zobraz() {
        $model = new Najit_polozku_model($this->env);
        $sklady = new Sklady_model($this->env);
        $polozky = new Polozky_model($this->env);
        
        $this->wares_list = $sklady->get_prosty_seznam();
        $this->hide_warelist = isset($_POST['ware_all'])? 'class="hidden"':'';
        if (isset($_POST['sent'])) {
            try {            
                $post = $model->sanitize_form($_POST);
                $selected_wares = isset($post['warelist'])? array_keys($post['warelist']) : array();
                $result = $model->search($post['items'], $selected_wares, $post['single_ware'] == 0);
                $this->search_result = new Search_result($result);
                $this->missing_items = $result->get_chybi_items();
                $this->sklady_kde_je_vse_hledane = $result->get_sklady_kde_je_vse_hledane();                
            }
            catch (Item_form_search_exception $e) {
                $post = $e->get_post();
                $this->form_error = $e->getMessage();
            }
            $this->saved_form = base64_encode(json_encode($post));
        }
        else {
            $this->saved_form = base64_encode(json_encode(array()));
        }        
        $this->saved_ware_list = array();
        if (isset($_POST['warelist'])) {
            foreach ($_POST['warelist'] as $w => $v) {
                $this->saved_ware_list[] = "warelist[{$w}]";
            }            
        }
        $this->saved_ware_list = base64_encode(json_encode($this->saved_ware_list));        
        $this->item_list = $polozky->get_prosty_seznam();
                
        require "{$this->folder}/najit_polozku.php";                
    }
}
