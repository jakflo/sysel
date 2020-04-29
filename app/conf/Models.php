<?php

namespace Sysel\Conf;

class Models {
    protected $env;
    
    public function __construct(Env $env) {
        $this->env = $env;        
    }
}
