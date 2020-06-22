<?php

namespace Sysel\Data_objects;
use Sysel\Utils\Xss_fix;
use Exception;

abstract class Data_object {
    
    /**
     * seznam hodnot, které mají být encodovány pomocí Xss_fix
     * @var array
     */
    protected $xss_protected_values = array();
    
    /**
     * všechny hodnoty encodovat pomocí Xss_fix 
     * @var bool
     */
    protected $xss_protect_all_values = false;
    
    /**     
     * @var array
     */
    protected $source_array;

    protected $skip_these = array(
        'xss_protected_values', 
        'xss_protect_all_values', 
        'skip_these', 
        'source_array'
        );    

    /*zkontroluje, zda hodnoty v xss_protected_values existují i v objektu
     *ochrana proti překlepu, co by moh nechat díru pro XSS
    */
    public function check_xss_protected_values() {
        $obj_props = array_keys(get_object_vars($this));
        if (count($this->xss_protected_values) > 0) {
            $neznaje = array_diff($this->xss_protected_values, $obj_props);
            if (count($neznaje) > 0) {
                throw new Exception('Nezname promenne k xss fixingu: ' . implode(', ', $neznaje));
            }
        }
    }

    public function load_array(array $array) {
        $this->check_xss_protected_values();
        $xss_fix = new Xss_fix;
        $obj_props = array_keys(get_object_vars($this));
        $this->source_array = $array;
        
        if ($this->xss_protect_all_values) {
            $array = $xss_fix->fix_array($array); 
        }
        elseif (count($this->xss_protected_values) != 0) {
            $array = $xss_fix->fix_array_key_specific($array, $this->xss_protected_values);                    
        }
        
        foreach ($obj_props as $prop) {
            if (isset($array[$prop])) {
                $this->$prop = $array[$prop];
            }
        }        
    }
    
    public function load_2d_array(array $array) {
        $result = array();
        foreach ($array as $row) {
            $class_nm = get_class($this);
            $row_obj = new $class_nm;
            $row_obj->load_array($row);
            $result[] = $row_obj;            
        }
        return $result;        
    }
    
    public function get_source_array() {
        return $this->source_array;        
    }
}
