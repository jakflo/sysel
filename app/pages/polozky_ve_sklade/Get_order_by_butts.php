<?php

namespace Sysel\Pages\Polozky_ve_sklade;

class Get_order_by_butts {
    /**
     * @var string
     */
    protected $order_by;
    
    public function __construct(string $order_by) {
        $this->order_by = $order_by;
    }
    
    public function get_html(string $field_nm) {
        $marked_asc = $this->order_by == "{$field_nm} asc"? ' marked':'';
        $marked_desc = $this->order_by == "{$field_nm} desc"? ' marked':'';        
        $html = "<button class='order_by_button{$marked_asc}' type='button' data-name='{$field_nm} asc'>▲</button>";
        $html .= "<button class='order_by_button{$marked_desc}' type='button' data-name='{$field_nm} desc'>▼</button>";        
        return $html;
    }
}
