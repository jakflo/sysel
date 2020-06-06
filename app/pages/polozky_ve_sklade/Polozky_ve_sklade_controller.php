<?php

namespace Sysel\Pages\Polozky_ve_sklade;
use Sysel\Conf\Controllers;
use Sysel\Pages\Polozky_ve_sklade\Polozky_ve_sklade_model;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Pages\Polozky_ve_sklade\Add_item_validate;
use Sysel\Utils\Strankovac;
use Sysel\Utils\Array_tools;

class Polozky_ve_sklade_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Položky ve skladě';
    
    public function zobraz() {
        $rows_per_page = 15;
        $model = new Polozky_ve_sklade_model($this->env);
        $array_tools = new Array_tools;
        if ($this->params[0] == 'detailne') {
            $this->detailne = true;
            $get = $model->sanitize_get($_GET);
            $this->page = $get['page'];
            $this->order_by = $get['order_by'];
            $this->pocet_zaznamu = $model->get_pocet_zaznamu($get);
            if ($this->pocet_zaznamu > 0) {
                $strankovac = new Strankovac($this->pocet_zaznamu, $rows_per_page, $this->page);
                $this->full_list = $model->get_rows($strankovac);
                $get['page'] = $this->page = $strankovac->get_page();
                $get_tmp = $get;
                $get_tmp['page'] = ':page:';
                $url = "{$this->webroot}/polozky_ve_sklade/detailne?{$array_tools->asoc_na_uri($get_tmp)}";                
                $this->strankovac = $strankovac->get_html($url);                
                
            }
            else {
                $this->full_list = array();
                $this->page = 1;
            }
            $this->form_save = base64_encode(json_encode($get));
        }
        else {
            if (isset($_POST['add_item_sent'])) {                
                $validator = new Add_item_validate($this->env);
                $empty_ware = isset($_POST['w_empty']);
                $post = $array_tools->trim_values($_POST);
                if (!$validator->validate($post)) {
                    unset($post['add_item_sent']);
                    $this->add_item_form = array(
                        'empty_ware' => $empty_ware, 
                        'data' => $post
                    );
                    $this->add_item_form = base64_encode(json_encode($this->add_item_form));
                    if ($empty_ware) {
                        $this->add_item_empty_ware_errors = $validator->get_errors();                        
                    }
                    else {
                        $this->add_item_errors = $validator->get_errors();
                    }
                }
                else {
                    $model->pridat_polozky($post['w_id'], $post['item_id'], $post['item_amount']);
                    $_SESSION['top_message'] = "Položka úspěšně přidána.";
                    $this->reload();                    
                }                
            }
            $polozky = new Polozky_model($this->env);
            $sklady = new Sklady_model($this->env);
            $this->pouze_volne = $this->params[0] == 'volne';
            $this->brief_list = $model->zobraz_brief($this->pouze_volne);
            $this->items = $polozky->get_prosty_seznam();
            $this->volne_sklady = $sklady->get_volne_sklady();
            $this->detailne = false;            
        }
        
        if (isset($_SESSION['top_message'])) {
            $this->top_message = $_SESSION['top_message'];
            unset ($_SESSION['top_message']);
        }
        
        require "{$this->folder}/polozky_ve_sklade.php";                
    }
    
    public function get_order_by_butts(string $field_nm) {
        $marked_asc = $this->order_by == "{$field_nm} asc"? ' marked':'';
        $marked_desc = $this->order_by == "{$field_nm} desc"? ' marked':'';        
        $html = "<button class='order_by_button{$marked_asc}' type='button' data-name='{$field_nm} asc'>▲</button>";
        $html .= "<button class='order_by_button{$marked_desc}' type='button' data-name='{$field_nm} desc'>▼</button>";        
        return $html;
    }
}
