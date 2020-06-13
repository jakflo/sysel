<?php

namespace Sysel\Pages\Polozky_ve_sklade;

class Validate_order_by {
    /**
     * seznam povolených termů (bez asc, desc)
     * @var array
     */
    protected $allowed_oby;
    
    public function __construct(array $allowed_oby) {
        $this->allowed_oby = $allowed_oby;
    }
    
    public function validate(string $order_by) {
        $order_by = trim($order_by);
        $order_by_arr = explode(' ', $order_by);
        if (!in_array($order_by_arr[1], array('asc', 'desc'))) {
            return false;
        }
        elseif (!in_array($order_by_arr[0], $this->allowed_oby)) {
            return false;        
        }
        else {
            return true;
        }
    }
}
