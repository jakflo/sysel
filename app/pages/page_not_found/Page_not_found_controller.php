<?php

namespace Sysel\Pages\Page_not_found;
use Sysel\Conf\Controllers;

class Page_not_found_controller extends Controllers {
    
    public function zobraz() {
        require "{$this->folder}/page_not_found.php";                
    }
}
