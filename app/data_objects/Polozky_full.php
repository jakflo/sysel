<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;

class Polozky_full extends Data_object {
    /**
     *
     * @var int
     */    
    public $item_id, $order_id;
    
    /**
     *
     * @var string
     */
    public $war_name, $item_name, $comb_name, $status;
    
    /**
     *
     * @var cz_date
     */
    public $added;
    
    protected $xss_protected_values = array('war_name', 'item_name', 'comb_name');
}
