<?php

namespace Sysel\Pages\Order_search_item;
use Sysel\Conf\Form_validate_common;

class Sanitize_ware_list extends Form_validate_common {
    public $ware_not_found = false;
    
    public function sanitizace_ware_list(array $ware_list) {
        $validated = array();
        foreach ($ware_list as $ware) {
            if ($this->validate_id($ware)) {
                $validated[] = $ware;
            }
            else {
                $this->ware_not_found = true;
            }
        }
        return $validated;
    }
}
