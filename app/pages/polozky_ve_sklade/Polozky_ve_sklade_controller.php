<?php

namespace Sysel\Pages\Polozky_ve_sklade;
use Sysel\Conf\Controllers;

class Polozky_ve_sklade_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Položky ve skladě';
    
    public function zobraz() {        
        require "{$this->folder}/polozky_ve_sklade.php";                
    }
}
