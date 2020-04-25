<?php

namespace Sysel\Pages\Polozky;
use Sysel\Conf\Controllers;

class Polozky_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Seznam položek';
    
    public function zobraz() {        
        require "{$this->folder}/polozky.php";                
    }
}
