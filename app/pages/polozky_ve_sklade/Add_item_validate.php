<?php

namespace Sysel\Pages\Polozky_ve_sklade;
use Sysel\Conf\Form_validate_common;
use Sysel\Conf\Env;
use Sysel\Pages\Sklady\Sklady_model;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\Pages\Polozky_ve_sklade\Polozky_ve_sklade_model;
use Sysel\conf\Exceptions\Polozky_ve_sklade_exception;

class Add_item_validate extends Form_validate_common {
    /**     
     * @var Env
     */
    protected $env;
    protected $errors = array();    
    
    /**
     * @var int
     */
    protected $w_id;    
    protected $it_id;

    public function __construct(Env $env) {
        $this->env = $env;
    }
    
    public function validate(array $post) {
        $this->validate_w_id($post['w_id']);
        $this->validate_it_id($post['item_id']);
        $this->validate_amount($post['item_amount']);
        return !$this->got_error;
    }
    
    public function validate_w_id($w_id) {
        if ($w_id == 0) {
            $this->add_error('Vyberte sklad');
            return false;
        }
        $this->set_model(new Sklady_model($this->env));
        if (!$this->validate_id($w_id)) {
            $this->add_error('Neznámý sklad');
            return false;
        }
        $this->w_id = trim($w_id);
        return true;        
    }
    
    public function validate_it_id($item_id) {
        if ($item_id == 0) {
            $this->add_error('Vyberte položku');
            return false;
        }
        $this->set_model(new Polozky_model($this->env));
        if (!$this->validate_id($item_id)) {
            $this->add_error('Neznámá položka');
            return false;
        }
        $this->it_id = $item_id;
        return true;        
    }
    
    public function validate_amount($amount) {
        if (empty($amount)) {
            $this->add_error('Vyberte množství položky');
            return false;
        }
        if (!$this->validate_positive_int($amount)) {
            $this->add_error('Množství musí být celé kladné číslo');
            return false;
        }
        if (!$this->got_error) {
            $model = new Polozky_ve_sklade_model($this->env);
            try {
                $max_mnozstvi = $model->maximalne_polozek_na_sklad($this->w_id, $this->it_id);
                if (trim($amount) > $max_mnozstvi) {
                    $this->add_error('Překročena kapacita skladu');
                    return false;
                }
            }
            catch (Polozky_ve_sklade_exception $e) {
                $this->add_error('Neznámá chyba');
                return false;
            }
        }
        return true;        
    }
    
    public function add_error(string $error) {
        $this->errors[] = $error;
        $this->got_error = true;        
    }
    
    public function get_errors() {
        return $this->errors;        
    }    
}
