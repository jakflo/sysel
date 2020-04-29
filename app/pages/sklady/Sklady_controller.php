<?php

namespace Sysel\Pages\Sklady;
use Sysel\Conf\Controllers;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Pages\Sklady\Sklady_validate;
use Sysel\Utils\Xss_fix;

class Sklady_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Seznam skladů';

    public function zobraz() {
        $sklady = new Sklady_model($this->env);
        $xss = new Xss_fix;
        
        if (isset($_POST['action'])) {
            $validate = new Sklady_validate($sklady);
            $action = array_keys($_POST['action'])[0];
            switch($action) {
                case 'rename':
                    $war_id = array_keys($_POST['rename'])[0];
                    $new_name = $_POST['rename'][$war_id];
                    $this->errors_top = $validate->validate_rename($war_id, $new_name);
                    if (count($this->errors_top) == 0) {
                        $sklady->rename($war_id, $new_name);
                        $_SESSION['top_message'] = "Sklad přejmenován.";
                        $this->reload();
                    }
                    else {
                        $this->errors_top = implode(' ', $this->errors_top);                        
                    }
                    break;
                case 'delete':
                    $war_id = array_keys($_POST['action']['delete'])[0];
                    $this->errors_top = $validate->validate_delete($war_id);
                    if (empty($this->errors_top)) {
                        $_SESSION['top_message'] = "Sklad {$sklady->jmeno_skladu($war_id)} byl smazán.";
                        $sklady->delete($war_id);
                        $this->reload();                        
                    }
                    break;
                case 'new':
                    $name = trim($_POST['new_name']);
                    $area = trim($_POST['new_area']);
                    $this->errors_bott = $validate->validate_new($name, $area);
                    if (count($this->errors_bott) == 0) {
                        $_SESSION['top_message'] = "Sklad {$xss->fix_string($name)} vytvořen.";
                        $sklady->new($name, $area);
                        $this->reload();
                    }                    
            }
        }
        if (isset($_SESSION['top_message'])) {
            $this->top_message = $_SESSION['top_message'];
            unset($_SESSION['top_message']);
        }
        $this->seznam_skladu = $sklady->zobraz_sklady();                
        require "{$this->folder}/sklady.php";     
    }    
}
