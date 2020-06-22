<?php

namespace Sysel\Data_objects;
use Sysel\Data_objects\Data_object;
use Sysel\Utils\String_tools;

class Client extends Data_object {
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $company_name, $forname, $surname, $middlename, $title, $comb_name, $email, $phone, $street, $city, $country, $comb_address;
    
    protected $xss_protected_values = array(
        'company_name', 'forname', 'surname', 'middlename', 'title', 'comb_name', 
        'email', 'phone', 'street', 'city', 'country', 'comb_address'
        );
    
        public function load_array(array $array) {
            $string_tools = new String_tools();
            $array['comb_name'] = $string_tools->implode_array(array(
                $array['title'], $array['forname'], $array['middlename'], $array['surname']
            ));
            $array['comb_address'] = $string_tools->implode_array(array(
                $array['street'], $array['city'], $array['country']
            ), ', ');
            parent::load_array($array);
        }
}
