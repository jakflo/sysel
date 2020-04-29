<?php

namespace Sysel\Pages\Polozky;

use Sysel\Conf\Form_validate_common;
use Sysel\Utils\Simple_validator;
use Sysel\Utils\Xss_fix;
use Sysel\Pages\Vyrobec\Vyrobec_model;


class Polozky_validate extends Form_validate_common {
    public function validate_rename($id, $name) {
        $errors = array();        
        if (!$this->validate_id($id)) {
            $errors[0] = "Položka nenalezena!";            
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
            $errors = "Položka nenalezena!";            
        }
        elseif (in_array($id, $this->model->get_pouzite_polozky())) {
            $errors = "Položka {$this->model->jmeno_polozky($id)} již byla použita";        
        }
        return $errors;
    }
    
    public function validate_new($name, $area, $vyrobce, Vyrobec_model $vyrobce_model) {
        $err_nm = $this->validate_name($name);
        $area_valid = new Simple_validator($area);
        if (!empty($err_nm)) {
            $errors[0] = $err_nm;
        }
        $area_err = $this->validate_area_float($area);
        if (!empty($area_err)) {
            $errors[1] = $area_err;
        }        
        if (empty($vyrobce) or $vyrobce == 0) {
            $errors[2] = 'Vyberte výrobce položky';
        }
        elseif (!$vyrobce_model->existuje_dle_id($vyrobce)) {
            $errors[2] = 'Výrobce nenalezen';        
        }
        return $errors;        
    }
    
    public function validate_change_area($id, $area) {
        $id = trim($id);        
        $errors = array();
        if (!$this->validate_id($id)) {
            $errors[] = "Položka nenalezena!";            
        }
        elseif (in_array($id, $this->model->get_pouzite_polozky())) {
            $errors[] = "Položka již byla použita.";
        }
        $area_err = $this->validate_area_float($area);
        if (!empty($area_err)) {
            $errors[] = $area_err;
        }
        return implode(' ', $errors);        
    }
}
