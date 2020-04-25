<?php

namespace Sysel\Pages\Order_detail;
use Sysel\Conf\Controllers;
use Sysel\conf\Exceptions\Controller_exception;
use Sysel\Utils\Simple_validator;

class Order_detail_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Detail objednávky';
    
    /**
     *
     * @var int
     */
    protected $order_id;


    public function zobraz() {
        $validator = new Simple_validator($this->params[0]);        
        if ($validator->is_int()->greater_than(0, true)->get_result()) {
            $this->order_id = $this->params[0];
        }
        else {
            throw new Controller_exception('Neplatné číslo objednávky');            
        }
        
        
        require "{$this->folder}/order_detail.php";                
    }
}
