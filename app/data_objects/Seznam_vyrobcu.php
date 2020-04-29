<?php

namespace Sysel\Data_objects;

class Seznam_vyrobcu extends Data_object {
    /**
     *
     * @var int
     */
    public $id;
    
    /**
     *
     * @var string
     */
    public $name, $email, $phone, $street, $city, $country, $adresa_radek;
    
    protected $xss_protect_all_values = true;
}
