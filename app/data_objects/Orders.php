<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;

class Orders extends Data_object{
    /**
     * @var int
     */
    public $id, $status;
    
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
