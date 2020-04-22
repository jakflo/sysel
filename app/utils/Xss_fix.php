<?php

namespace Sysel\Utils;

/*
 * převádí věci na html entity jako ochrana proti XSS
 */
class Xss_fix {
    public function fix_string(string $string) {
        return htmlspecialchars($string, ENT_QUOTES);        
    }
    
    public function fix_array(array $input) {
        foreach ($input as &$v) {
            $v = $this->fix_string($v);
        }
        return $input;        
    }
    
    public function fix_array_key_specific(array $input, array $only_these_columns) {
        foreach ($input as $k => &$v) {
            if (in_array($k, $only_these_columns)) {
                $v = $this->fix_string($v);
            }
        }
        return $input;                
    }


    public function fix_2d_array(array $input, array $only_these_columns = array()) {
        foreach ($input as &$row) {
            if (count($only_these_columns) == 0) {
                $row = $this->fix_array($row);
            }
            else {
                $row = $this->fix_array_key_specific($row, $only_these_columns);
            }
        }
        return $input;
    }
    
}
