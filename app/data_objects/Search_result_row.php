<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;

class Search_result_row extends Data_object {
    /**     
     * @var string
     */
    public $item_name;
    
    /**
     * pole instancí Search_result_cell     
     * @var array
     */
    public $cells;
    
    protected $xss_protected_values = array('item_name');
}
