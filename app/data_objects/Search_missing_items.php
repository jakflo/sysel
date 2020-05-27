<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;

class Search_missing_items extends Data_object {
    /**     
     * @var int
     */
    public $id, $kolik;
    
    /**     
     * @var string
     */
    public $name;
    
    protected $xss_protected_values = array('name');
}
