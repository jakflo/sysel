<?php

namespace Sysel\Data_objects;

class Seznam_skladu extends Data_object {
    /**
     *
     * @var int
     */
    public $id;
    
    /**
     *
     * @var float
     */
    public $area, $area_left;
    
    /**
     *
     * @var string
     */
    public $name, $created;
    
    /**
     *
     * @var bool
     */
    public $is_empty;
    
    protected $xss_protected_values = array('name');
}
