<?php

namespace Sysel\Pages\Homepage;
use Sysel\Conf\Controllers;

class Homepage_controller extends Controllers {
    
    public function zobraz() {
        require "{$this->folder}/homepage.php";                
    }
}
