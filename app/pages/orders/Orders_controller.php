<?php

namespace Sysel\Pages\Orders;
use Sysel\Conf\Controllers;

class Orders_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Objednávky';
    
    public function zobraz() {        
        require "{$this->folder}/orders.php";                
    }
}
