<?php


namespace Sysel\Pages\Sklady;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Utils\Simple_validator;
use Sysel\Utils\Xss_fix;

class Sklady_validate {
    protected $model;
    
    public function __construct(Sklady_model $model) {
        $this->model = $model;        
    }
    public function validate_rename($id, $name) {                
        $errors = array();        
        if (!$this->validate_id($id)) {
            $errors[0] = "Sklad nenalezen!";            
        }
        $err_nm = $this->validate_name($name);
        if (!empty($err_nm)) {
            $errors[1] = $err_nm;
        }        
        return $errors;        
    }
    
    public function validate_delete($id) {
        $id = trim($id);        
        $errors = '';
        if (!$this->validate_id($id)) {
            $errors = "Sklad nenalezen!";            
        }
        elseif (in_array($id, $this->model->pouzite_sklady())) {
            $errors = "Sklad {$this->model->jmeno_skladu($id)} již obsahuje položky";        
        }
        return $errors;        
    }
    
    public function validate_new($name, $area) {
        $area = new Simple_validator($area);
        $err_nm = $this->validate_name($name);
        if (!empty($err_nm)) {
            $errors[0] = $err_nm;
        }
        if ($area->not_empty()->get_result()) {
            if (!$area->is_int()->greater_than(0, true)->get_result()) {
                $errors[1] = "Plocha musí být kladné celé číslo.";
            }
        }
        else {
            $errors[1] = "Musíte zadat plochu skladu.";
        }
        return $errors;        
    }
    
    public function validate_id($id) {
        $id = new Simple_validator($id);
        $valid = true;
        if ($id->is_int()->greater_than(0, true)->get_result()) {            
            if (!$this->model->existuje_sklad_dle_id($id->get_value())) {
                $valid = false;
            }
        }
        else {
            $valid = false;            
        }
        return $valid;        
    }
    
    public function validate_name($name) {
        $name = new Simple_validator($name);
        $xss = new Xss_fix;
        $error = '';
        if ($name->not_empty()->get_result()) {            
            if ($this->model->existuje_sklad_dle_jmena($name->get_value())) {
                $error = "Sklad jménem {$xss->fix_string($name->get_value())} již existuje.";
            }            
        }
        else {
            $error = "Nové jméno skladu nesmí zůstat prázdné.";
        }
        return $error;        
    }
}
