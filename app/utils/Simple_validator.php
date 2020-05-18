<?php

namespace Sysel\Utils;

class Simple_validator {
    
    /**
     * pokud selže validační metoda nastaví se na false
     * všechny validační metody zde vrací instanci objektu, takže to lze řetězit
     * a výsledek se získá z get_result()
     * @var bool
     */
    protected $all_good = true;
    
    protected $val;
    
    public function __construct($val) {
        $this->val = trim($val);        
    }
    
    public function reset() {
        $this->all_good = true;        
    }
    
    public function not_empty() {
        if ($this->all_good) {
            $this->all_good = !empty(($this->val));
        }
        return $this;        
    }
    
    public function is_numeric() {
        if ($this->all_good) {
            $this->all_good = is_numeric($this->val);
        }
        return $this;
    }
    
    public function is_int() {
        if ($this->all_good) {
            if (!is_numeric($this->val)) {
                $this->all_good = false;            
            }
            else {
                $this->all_good = floatval($this->val) == intval($this->val);
            }
        }
        return $this;
    }
    
    public function greater_than(float $greater_than, bool $sharp) {
        if ($this->all_good) {
            if (!is_numeric($this->val)) {
                $this->all_good = false;            
            }
            else if ($sharp) {
                $val = floatval($this->val);
                $this->all_good = $val > $greater_than;
            }
            else {
                $val = floatval($this->val);
                $this->all_good = $val >= $greater_than;
            }
        }
        return $this;                
    }
    
    public function less_than(float $less_than, bool $sharp) {
        if ($this->all_good) {
            if (!is_numeric($this->val)) {
                $this->all_good = false;            
            }
            else if ($sharp) {
                $val = floatval($this->val);
                $this->all_good = $val < $less_than;
            }
            else {
                $val = floatval($this->val);
                $this->all_good = $val <= $less_than;
            }
        }
        return $this;                
    }
    
    public function is_date_valid() {
        if ($this->all_good) {
            $tempDate = explode('-', $this->val);
            $this->all_good = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);            
        }
        return $this;        
    }
    
    // ověří, zda ve vstupním array jsou všechny hodnoty v klíčích $array_keys neprázdné
    // je-li $array_keys prázdný, ověří všechny hodnoty
    public function not_empty_in_array(array $array_keys = array()) {
        if ($this->all_good) {
            if (count($array_keys) == 0) {
                $array_keys = array_keys($this->val);
            }
            foreach ($array_keys as $key) {
                if (!isset($this->val[$key]) or empty(trim($this->val[$key]))) {
                    $this->all_good = false;
                    break;
                }
            }
        }
        return $this;
    }
    
    
    public function get_result() {
        return $this->all_good;
    }
    
    public function get_value() {
        return $this->val;        
    }
}
