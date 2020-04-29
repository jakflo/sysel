<?php

namespace Sysel\Pages\Polozky;
use Sysel\Conf\Controllers;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\Pages\Vyrobec\Vyrobec_model;
use Sysel\Pages\Polozky\Polozky_validate;
use Sysel\Utils\Xss_fix;

class Polozky_controller extends Controllers {
    protected $page_title = 'Syslův sklad | Seznam položek';   
    
    public function zobraz() {
        $model = new Polozky_model($this->env);
        $vyrobci = new Vyrobec_model($this->env);
        $xss = new Xss_fix;
        
        if (isset($_POST['action'])) {
            $validate = new Polozky_validate($model);
            $action = array_keys($_POST['action'])[0];
            switch($action) {
                case 'rename':
                    $id = array_keys($_POST['rename'])[0];
                    $new_name = $_POST['rename'][$id];
                    $this->errors_top = $validate->validate_rename($id, $new_name);
                    if (count($this->errors_top) == 0) {
                        $model->rename($id, $new_name);
                        $_SESSION['top_message'] = "Položka přejmenována.";
                        $this->reload();
                    }
                    else {
                        $this->errors_top = implode(' ', $this->errors_top);                        
                    }
                    break;
                case 'delete':
                    $id = array_keys($_POST['action']['delete'])[0];
                    $this->errors_top = $validate->validate_delete($id);
                    if (empty($this->errors_top)) {
                        $_SESSION['top_message'] = "Položka {$model->jmeno_polozky($id)} byla smazána.";
                        $model->delete($id);
                        $this->reload();                        
                    }
                    break;
                case 'new':
                    $name = trim($_POST['new_name']);
                    $area = str_replace(',', '.', trim($_POST['new_area']));
                    $vyrobce = trim($_POST['new_vyrobce']);
                    $this->errors_bott = $validate->validate_new($name, $area, $vyrobce, $vyrobci);
                    if (count($this->errors_bott) == 0) {
                        $_SESSION['top_message'] = "Položka {$xss->fix_string($name)} byla vytvořena.";
                        $model->new($name, $area, $vyrobce);
                        $this->reload();
                    }
                    break;
                case 'change_area':
                    $id = array_keys($_POST['change_area'])[0];
                    $area = str_replace(',', '.', trim($_POST['change_area'][$id]));
                    $this->errors_top = $validate->validate_change_area($id, $area);
                    if (empty($this->errors_top)) {
                        $_SESSION['top_message'] = "Plocha položky {$xss->fix_string($model->jmeno_polozky($id))} byla aktualizována";
                        $model->change_area($id, $area);
                        $this->reload();
                    }                    
            }
        }
        if (isset($_SESSION['top_message'])) {
            $this->top_message = $_SESSION['top_message'];
            unset($_SESSION['top_message']);
        }
        $this->seznam = $model->zobraz_seznam();
        $this->vyrobci = $vyrobci->zobraz_seznam();
        require "{$this->folder}/polozky.php";
    }
}
