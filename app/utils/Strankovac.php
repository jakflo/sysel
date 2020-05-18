<?php

namespace Sysel\Utils;
use Sysel\Utils\Simple_validator;

class Strankovac {    
    /**
     *
     * @var int
     */
    protected $page, $last_page, $rows_per_page;


    public function __construct(int $rows, int $rows_per_page, $page) {
        $page_valid = new Simple_validator($page);
        $this->last_page = ceil($rows / $rows_per_page );        
        $this->rows_per_page = $rows_per_page;
        if (!$page_valid->is_int()->greater_than(0, true)->less_than($this->last_page, false)->get_result()) {
            $this->page = 1;            
        }
        else {
            $this->page = $page;
        }
    }
    
    public function get_limit_string() {
        if ($this->page == 1) {
            return $this->rows_per_page;
        }
        else {
            $offset = $this->rows_per_page * ($this->page - 1);
            return "{$offset}, {$this->rows_per_page}";
        }
    }
    
    public function get_page() {
        return $this->page;        
    }
    
    //místo č. stránky je v $url_template holder :page:
    public function get_html(string $url_template) {
        $html = '<div class="strankovac">';
        if ($this->page == 1) {
            $rewind_disabled = true;
            $start = 1;            
        }
        elseif ($this->page == $this->last_page and $this->page > 2) {
            $start = $this->page - 2;                    
        }        
        else {            
            $start = $this->page - 1;
        }
        $end = $start + 2;
        if ($end > $this->last_page) {
            $end = $this->last_page;
        }
        if ($rewind_disabled) {
            $html .= "<span class='grey'>&lt;&lt;</span>";
        }
        else {
            $html .= "<a href='{$this->get_url($url_template, 1)}'>&lt;&lt;</a>";           
        }        
        
        for ($c = $start; $c <= $end; $c++) {
            if ($c == $this->page) {
                $html .= "<span class='curr_page'>{$c}</span>";
            }
            else {
                $html .= "<a href='{$this->get_url($url_template, $c)}'>{$c}</a>";
            }
        }
        
        if ($end < $this->last_page) {
            $html .= "<span>...</span>";
            $html .= "<a href='{$this->get_url($url_template, $this->last_page)}'>{$this->last_page}</a>";            
        }        
        
        $html .= '</div>';
        return $html;        
    }
    
    protected function get_url(string $url_template, int $page) {
        $url_template = str_replace('%3A', ':', $url_template);
        return str_replace(':page:', $page, $url_template);        
    }
}
