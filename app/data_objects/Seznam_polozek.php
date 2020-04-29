<?php

namespace Sysel\Data_objects;

class Seznam_polozek extends Data_object {
    /**
     *
     * @var int
     */
    public $id;
    
    /**
     *
     * @var float
     */
    public $area;


    /**
     *
     * @var string
     */
    public $name, $vyrobce, $country;
    
    /**
     *
     * @var bool
     */
    public $used;
    
    protected $xss_protected_values = array('name', 'vyrobce', 'country');
}
