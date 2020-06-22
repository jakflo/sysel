<?php

namespace Sysel\Utils;

class String_tools {
    //je-li v textu vícenásobná mezera, nahradí ji normální mezerou
    public function remove_multiple_spaces(string $input) {
        $old_len = strlen($input);
        $input = str_replace('  ', ' ', $input);
        if (strlen($input) < $old_len) {
            $input = $this->remove_multiple_spaces($input);
        }
        return $input;
    }
    
    //imploduje array do řetězce pomocí mezer, odstraní nadbytečné mezery
    public function implode_array(array $array, string $glue = ' ') {
        $string = implode($glue, $array);
        return $this->remove_multiple_spaces(trim($string));
    }
}
