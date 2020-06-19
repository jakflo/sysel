<?php

namespace Sysel\Conf;
use Sysel\Conf\Router;
use Sysel\Pages\Error\Error_controller;

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
    
    public function __construct(Env $env, string $folder, array $params) {
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
    
    public function get_error_controller() {
        return new Error_controller($this->env, $this->env->get_param('root').'/pages/error', array());
    }
        
    public function get_session_msg(string $msg_name, string $class_nm) {
        if (isset($_SESSION[$msg_name])) {
            $msg = "<p class='{$class_nm}'>{$_SESSION[$msg_name]}</p>";
            unset($_SESSION[$msg_name]);
        }
        else {
            $msg = '';
        }
        return $msg;
    }
    
    public function reload() {
        $router = new Router($this->env);
        $parsed = $router->parse($_SERVER['REQUEST_URI']);
        $page_nm = implode('/', $parsed);
        echo "<script>window.location.replace('{$this->webroot}/{$page_nm}')</script>";
        exit();
    }
}
