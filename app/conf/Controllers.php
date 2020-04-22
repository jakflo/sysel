<?php

namespace Sysel\Conf;

abstract class Controllers {
    
    /**
     *
     * @var string
     */
    protected $page_title = 'Syslův sklad';
    
    /**
     *
     * @var array
     */
    protected $params;
    
    /**
     *
     * @var Env
     */
    protected $env;
    
    /**
     *
     * @var string
     */
    protected $folder;


    public function __construct(Env $env, string $folder ,array $params) {
        $this->env = $env;
        $this->folder = $folder;
        $this->params = $params;        
    }

    public function get_page_title() {
        return $this->page_title;        
    }
}
