<?php

namespace Sysel\Conf;
use Exception;

class Router {
    
    /**
     *
     * @var Env
     */
    protected $env;
    
    /**
     *
     * @var Page_not_found_controller
     */
    protected $not_found;


    public function __construct(Env $env) {
        $this->env = $env;
        $this->not_found = $this->load('page_not_found');
    }


    public function parse(string $uri) {
        $uri = trim($uri, ' /');
        if (strpos($uri, '?') === 0) {
            $uri = '';
        }
        else {
            $uri_arr = explode('?', $uri);
            $uri = $uri_arr[0];
        }
        return explode('/', $uri);        
    }
    
    public function load(string $uri) {
        $parsed = $this->parse($uri);
        $page_name = trim($parsed[0]);
        $page_name = empty($page_name)? 'homepage':$page_name;
        $folder = "{$this->env->get_param('root')}/pages/{$page_name}";
        $controller_path = "Sysel\\pages\\{$page_name}\\{$page_name}_controller";
        unset($parsed[0]);
        
        try {
            $controller = new $controller_path($this->env, $folder, $parsed);
            return $controller;
        }
        catch (Exception $e) {
            return $this->not_found;
        }        
    }
}
