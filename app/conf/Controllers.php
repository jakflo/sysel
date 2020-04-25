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
    
    /**
     *
     * @var string
     */
    protected $webroot, $error_page;
    
    public function __construct(Env $env, string $folder ,array $params) {
        $this->env = $env;
        $this->folder = $folder;
        $this->params = $params;
        $this->webroot = $env->get_param('webroot');
        $this->error_page = $env->get_param('root').'/pages/error_page.php';      
    }

    public function get_page_title() {
        return $this->page_title;        
    }
    
    public function get_webroot() {
        return $this->webroot;        
    }
}
