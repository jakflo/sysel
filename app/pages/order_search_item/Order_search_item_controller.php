<?php

namespace Sysel\Pages\Order_search_item;
use Sysel\Conf\Controllers;
use Sysel\Pages\Order_search_item\Order_search_item_model;
use Sysel\Utils\Xss_fix;
use Sysel\conf\Exceptions\Order_exception;
use Sysel\Pages\Najit_polozku\Najit_polozku_model;
use Sysel\Pages\Najit_polozku\Search_result;
use Sysel\Pages\Order_search_item\Sanitize_ware_list;
use Sysel\Pages\Sklady\Sklady_model;

class Order_search_item_controller extends Controllers {    
   protected $page_title = 'Syslův sklad | Položky objednávky';   
   
   public function zobraz() {
       $xss = new Xss_fix;
       $this->order_id = isset($this->params[0])? $xss->fix_string($this->params[0]) : '';
       $model = new Order_search_item_model($this->env, $this->order_id);       
       try {
           $items_needed = $model->get_items_needed();
           if (isset($_POST['sent'])) {
               $this->prirad_polozky($model);
           }
           else {
               $hledac = new Najit_polozku_model($this->env);
               $nalezeno = $hledac->search($items_needed, array());
               $this->nic_nebylo_nalezeno = $nalezeno->get_nic_nebylo_nalezeno();
               $this->search_result = new Search_result($nalezeno);
               $this->missing_items = $nalezeno->get_chybi_items();
               $this->sklady_kde_je_vse_hledane = $nalezeno->get_sklady_kde_je_vse_hledane();
               $this->sklady_kde_je_alespon_1_hledana_polozka = $nalezeno->get_sklady_kde_je_alespon_1_hledana_polozka();

               require_once "{$this->folder}/order_search_item.php";
           }
       }
        catch (Order_exception $e) {
           if ($e->getCode() == 1) {
               $error = "Objednávka č. {$this->order_id} nenelezena!";
           }
           elseif ($e->getCode() == 2) {
               $error = "Objednávka č. {$this->order_id} už má položky přiřazené.";           
           }
           $error_controller = $this->get_error_controller();
           $error_controller->set_msg($error);
           $error_controller->zobraz();
        }       
   }
   
   public function prirad_polozky(Order_search_item_model $model) {       
       if (isset($_POST['use_all_wares'])) {
           $ware_list = array();
       }
       elseif (!isset($_POST['use_ware'])) {
           $this->reload_with_error('Vyberte alespoň 1 sklad');           
       }
       else {
           $validator = new Sanitize_ware_list(new Sklady_model($this->env));
           $ware_list = $validator->sanitizace_ware_list(array_keys($_POST['use_ware']));
           if (count($ware_list) == 0) {
               $this->reload_with_error('Žádný z vybraných skladů nebyl nalezen.');               
           }           
       }
       try {
           $vse_prirazeno = $model->prirad_polozky_k_objednavce($ware_list);
       }
         catch (Order_exception $e) {
             if ($e->getCode() == 3) {
                 $this->reload_with_error('V žádném z vybraných skladů nebyla nalezena hledaná položka.');                               
             }
             else {
                 throw $e;
             }
         }
         if ($vse_prirazeno) {
             $_SESSION['top_msg'] = "Položky úspěšně přiřazeny k objednávce.";
             echo "<script>window.location.replace('{$this->webroot}/orders')</script>";
             exit();
         }
         else {
             $this->reload_with_error('Některé položky nebyly nalezeny.');                              
         }       
   }
   
   protected function reload_with_error(string $error) {
       $_SESSION['error_msg'] = $error;
       $this->reload();
   }
}
