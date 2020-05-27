<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Conf\Env;
use Sysel\Pages\Polozky\Polozky_model;
use Sysel\Data_objects\Search_missing_items;

class Missing_items {
    protected $jmena_polozek;
    protected $chybejici_polozky = array();

    public function __construct(Env $env) {
        $polozky = new Polozky_model($env);
        $this->jmena_polozek = $polozky->get_prosty_seznam(true);        
    }
    
    public function add_item(int $id, int $amount) {
        $data_obj = new Search_missing_items;
        $data_obj->load_array(array(
            'id' => $id, 
            'name' => $this->jmena_polozek[$id], 
            'kolik' => $amount
        ));
        $this->chybejici_polozky[] = $data_obj;        
    }
    
    public function get_chybejici_polozky() {
        return $this->chybejici_polozky;
    }    
}
