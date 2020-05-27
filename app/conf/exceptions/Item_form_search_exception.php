<?php

namespace Sysel\conf\Exceptions;
use Exception;

class Item_form_search_exception extends Exception {
    protected $post;
    
    public function set_message(string $message) {
        $this->message = $message;
        return $this;
    }
    public function set_post(array $post) {
        $this->post = $post;
        return $this;
    }
    public function get_post() {
        return $this->post;
    }    
}
