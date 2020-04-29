<?php

namespace Sysel\Pages\Sklady;
use Sysel\Utils\Simple_validator;
use Sysel\Conf\Form_validate_common;

class Sklady_validate extends Form_validate_common {
    
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
        if (!$area->not_empty()->get_result()) {
            $errors[1] = "Musíte zadat plochu skladu.";            
        }
        elseif (!$area->is_int()->greater_than(0, true)->get_result()) {
            $errors[1] = "Plocha musí být kladné celé číslo.";        
        }
        return $errors;        
    }    
}
