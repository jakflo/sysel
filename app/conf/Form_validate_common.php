<?php

namespace Sysel\Conf;
use Sysel\Utils\Simple_validator;
use Sysel\Utils\Xss_fix;


/**
 * obsahuje pár společných metod pro jednotlivé validátory formulářů
 * $model je model daného MVC obsahující 
 */
abstract class Form_validate_common {    
    protected $model;
    
    public function __construct($model) {
        $this->model = $model;        
    }
    
    public function validate_id($id) {
        $id = new Simple_validator($id);
        $valid = true;        
        if (!$id->is_int()->greater_than(0, true)->get_result() or 
            !$this->model->existuje_dle_id($id->get_value())) {
                $valid = false;            
        }
        return $valid;        
    }
    
    public function validate_name($name) {
        $name = new Simple_validator($name);
        $xss = new Xss_fix;
        $error = '';
        if (!$name->not_empty()->get_result()) {
            $error = "Jméno nesmí zůstat prázdné.";
        }
        elseif ($this->model->existuje_dle_jmena($name->get_value())) {
            $error = "Jméno {$xss->fix_string($name->get_value())} je již využito.";        
        }
        return $error;
    }
    
    public function validate_area_float($area) {
        $area_valid = new Simple_validator($area);
        $error = '';
        if (empty($area)) {
            $error = 'Zadejte využitou plochu položky.';
        }
        elseif (!$area_valid->is_numeric()->greater_than(0, true)->get_result()) {
            $error = 'Plocha musí být kladné číslo.';
        }
        return $error;
    }
    
}
