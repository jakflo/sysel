<?php

namespace Sysel\Data_objects;

class Polozky_brief extends Data_object {
    /**
     *
     * @var int
     */
    public $w_id, $it_id, $pocet;
    
    /**
     *
     * @var string
     */
    public $it_name;
    
    protected $xss_protected_values = array('it_name');    
}
