<?php

namespace Sysel\Pages\Order_detail;
use Sysel\Conf\Controllers;
use Sysel\Pages\Order_detail\Order_detail_model;
use Sysel\Utils\Xss_fix;
use Sysel\conf\Exceptions\Order_exception;

class Order_detail_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Detail objednávky';
    
    /**
     * @var int
     */
    protected $order_id;


    public function zobraz() {
        $xss = new Xss_fix;
        $order_id = trim($this->params[0]);        
        $this->order_id = $xss->fix_string($order_id);
        try {
            $model = new Order_detail_model($this->env, $order_id);
        }
         catch (Order_exception $e) {
             if ($e->getCode() == 1) {
                 $error = $this->get_error_controller()->set_msg('Objednávka nenalezena')->zobraz();
                 exit();
             }
             else {
                 throw $e;
             }
         }
         
         if (isset($_POST['change_stat'])) {
             $new_stat = trim($_POST['status']);
             if (!$model->validate_status_change($new_stat)) {
                 $_SESSION['error'] = 'Neplatná změna objednávky';
                 $this->reload();
             }
             else {
                 if ($new_stat != $model->get_status()) {
                     $model->change_status($new_stat);
                     $_SESSION['message'] = 'Změny uloženy';
                     $this->reload();
                 }
             }
         }
         
         $this->basic_nfo = $model->get_order_detail();
         $this->client_nfo = $model->get_client_detail();
         $this->items = $model->get_items();        
        require "{$this->folder}/order_detail.php";                
    }
}
