<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;

class Orders extends Data_object{
    /**
     * @var int
     */
    public $id, $status;
    
    /**
     * @var bool
     */
    public $status_is_select;
    
    /**
     * @var array
     */
    public $status_select;

    /**
     * date
     */
    public $added, $last_edited;
    
    /**
     * @var sting
     */
    public $note, $comb_name, $status_name;
    
    protected $xss_protected_values = array('note', 'comb_name');    
}
