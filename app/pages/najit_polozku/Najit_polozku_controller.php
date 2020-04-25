<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Conf\Controllers;

class Najit_polozku_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Hledat položky';
    
    public function zobraz() {        
        require "{$this->folder}/najit_polozku.php";                
    }
}
