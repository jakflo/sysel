<?php

namespace Sysel\Data_objects;

class Astro extends Data_object {
    public $id, $f_name, $l_name, $DOB, $skill;
    protected $xss_protected_values = array('l_name', 'f_name');
}
