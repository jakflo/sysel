<?php

namespace Sysel\Pages\Error;
use Sysel\Conf\Controllers;
use Sysel\Utils\Xss_fix;

class Error_controller extends Controllers {
    
    /**
     *
     * @var string
     */
    protected $error_msg;

    public function zobraz() {
        require "{$this->folder}/error.php";                
    }
    
    public function set_msg(string $msg) {
        $xss = new Xss_fix;
        $this->error_msg = $xss->fix_string($msg);
        return $this;
    }
}
