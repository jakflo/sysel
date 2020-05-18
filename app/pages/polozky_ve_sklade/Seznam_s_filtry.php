<?php

namespace Sysel\Pages\Polozky_ve_sklade;
use Sysel\Utils\Filtered_list;

class Seznam_s_filtry extends Filtered_list {
    /**
     *
     * @var string
     */
    protected $warehouse_term = 'join warehouse w on i.warehouse_id=w.id';
    protected $item_detail = 'join item_detail ide on i.item_detail_id=ide.id';
    protected $order_term = 'left join `order` o on i.order_id=o.id';
    protected $client_term = 'left join client c on o.client_id=c.id ';
    protected $where_term = '';
    protected $order_by = '';
    protected $customer_name_term = "if(c.company_name is null,concat(c.forname,' ',c.surname),concat(c.company_name,' (',c.forname,' ',c.surname,')'))";

    /**
     *
     * @var string
     */
    protected $warehouse_term_filter = 'and w.name like :w_name';
    protected $item_detail_filter = 'and ide.name like :item_name';
    protected $order_term_filter = 'and o.id=:o_id';
    protected $client_term_filter = 'and :customer_name_term: like :cl_name';
    
    protected $select_cols = "i.id as item_id, w.name as war_name, ide.name as item_name, i.added, i.order_id, i.status, 
:customer_name_term: as comb_name";
  
    public function init(array $get) {
        $this->client_term_filter = str_replace(':customer_name_term:', $this->customer_name_term, $this->client_term_filter);
        $this->select_cols = str_replace(':customer_name_term:', $this->customer_name_term, $this->select_cols);
        $this->count_sql = "select count(*) from item i";
        $this->get_ids_sql = "select i.id from item i";
        $this->final_sql = "select {$this->select_cols} from item i";
        $warehouse_term_filter = $item_detail_filter = $order_term_filter = $client_term_filter = '';
        
        foreach ($get as $k => $v) {
            switch($k) {
                case 'w_name':
                    $this->add_terms($this->warehouse_term, $this->warehouse_term_filter, $warehouse_term_filter);
                    $this->params[':w_name'] = "%{$v}%";
                    break;
                case 'item_name':
                    $this->add_terms($this->item_detail, $this->item_detail_filter, $item_detail_filter);
                    $this->params[':item_name'] = "%{$v}%";
                    break;
                case 'added':
                    $znaky_nerovnosti = array('less' => '<=', 'more' => '>=', 'equal' => '=');
                    $this->add_where("i.added {$znaky_nerovnosti[$get['added_znak']]} :added");
                    $this->params[':added'] = $v;
                    $this->params_where_keys[] = ':added';
                    break;
                case 'added_znak':
                    break;
                case 'status':
                    $this->add_where('i.status=:status');
                    $this->params[':status'] = $v;
                    $this->params_where_keys[] = ':status';
                    break;
                case 'o_id':
                    $this->unleft_join($this->order_term);
                    $this->unleft_join($this->client_term);
                    $this->add_terms($this->order_term, $this->order_term_filter, $order_term_filter);
                    $this->params[':o_id'] = $v;
                    break;
                case 'client_name':
                    $this->unleft_join($this->order_term);
                    $this->unleft_join($this->client_term);
                    if (empty($get['o_id'])) {
                        $dont_use = '';
                        $this->add_terms($this->order_term, ' ', $dont_use);
                    }
                    $this->add_terms($this->client_term, $this->client_term_filter, $client_term_filter);
                    $this->params[':cl_name'] = "%$v%";
                    break;
                case 'order_by':
                    if ($v == 'ide.name') {
                        $this->order_by = "order by ide.name";                        
                    }
                    else {
                        $this->order_by = "order by {$v}, ide.name";
                        $this->join_order_by($v);                        
                    }                    
                    $this->join_order_by('ide.name');
                    break;                
            }
        }
        
        if (empty($this->order_by)) {
            $this->order_by = "order by ide.name";
            $this->join_order_by('ide.name asc');            
        }
        $this->order_by .= ', i.id';
        
        $this->count_sql .= " $this->where_term";
        $this->get_ids_sql .= " $this->where_term";
        $this->final_sql .=" {$this->warehouse_term} {$warehouse_term_filter}";
        $this->final_sql .=" {$this->item_detail} {$item_detail_filter}";
        $this->final_sql .=" {$this->order_term} {$order_term_filter}";
        $this->final_sql .=" {$this->client_term} {$client_term_filter}";
    }
    
    protected function join_order_by($order_by) {
        $order_by = explode(' ', trim($order_by))[0];
        switch($order_by) {
            case 'w.name':
                $this->join_if_not_joined($this->warehouse_term);
                break;
            case 'ide.name':
                $this->join_if_not_joined($this->item_detail);
                break;
            case 'i.added':
                break;
            case 'i.order_id':
                $this->join_if_not_joined($this->order_term);
                break;
            case 'comb_name':
                $this->join_if_not_joined($this->order_term);
                if (strpos($this->get_ids_sql, $term) === false) {
                    $this->get_ids_sql .= " left join (SELECT id, {$this->customer_name_term} as comb_name FROM client c) as c on o.client_id=c.id";
                }                
        }        
    }       
}
