<?php

namespace Sysel\Pages\Orders;
use Sysel\Utils\Filtered_list;
use Sysel\Utils\Strankovac;

class Objednavky_s_filtry extends Filtered_list {
    protected $select_cols = 'o.id, o.added, last_edited, o.note, status, cn.comb_name';
    protected $comb_name_term = "join (select id, if(company_name is null,concat(forname,' ',surname),concat(company_name,' (',forname,' ',surname,')')) as comb_name from client) as cn on o.client_id=cn.id ";
    protected $where_term = '';
    protected $order_by = '';
    
    protected $comb_name_filter = ' and cn.comb_name like :client';
    
    public function init(array $get) {
        $this->count_sql = "select count(*) from `order` o";
        $this->get_ids_sql = "select o.id from `order` o";
        $this->final_sql = "select {$this->select_cols} from `order` o";
        $client_filter = '';
        $znaky_nerovnosti = array('less' => '<=', 'more' => '>=', 'equal' => '=');
        
        foreach ($get as $k => $v) {
            switch($k) {
                case 'o_id':
                    $this->add_where('o.id=:o_id');
                    $this->params[':o_id'] = $v;
                    $this->params_where_keys[] = ':o_id';
                    break;
                case 'add_date':
                    if (!empty($get['add_sign'])) {
                        $znak = $znaky_nerovnosti[$get['add_sign']];
                        $this->add_where("DATE(o.added){$znak}:add_date");
                        $this->params[':add_date'] = $v;
                        $this->params_where_keys[] = ':add_date';
                    }
                    break;
                case 'edit_date':
                    if (!empty($get['edit_sign'])) {
                        $znak = $znaky_nerovnosti[$get['edit_sign']];
                        $this->add_where("DATE(o.last_edited){$znak}:edit_date");
                        $this->params[':edit_date'] = $v;
                        $this->params_where_keys[] = ':edit_date';
                    }
                    break;
                case 'add_sign':
                case 'edit_sign':
                    break;
                case 'note':
                    $this->add_where('o.note like :note');
                    $this->params[':note'] = "%{$v}%";
                    $this->params_where_keys[] = ':note';
                    break;
                case 'client':
                    $this->add_terms($this->comb_name_term, $this->comb_name_filter, $client_filter);
                    $this->params[':client'] = "%{$v}%";
                    break;
                case 'status':
                    $this->add_where('o.status=:status');
                    $this->params[':status'] = $v;
                    $this->params_where_keys[] = ':status';
                    break;
                case 'order_by':
                    $this->order_by = "order by {$v}";
                    if (strpos($v, 'cn.comb_name') !== false) {
                        $this->join_if_not_joined($this->comb_name_term);
                    }
                    break;                    
            }            
        }
        $this->count_sql .= " $this->where_term";
        $this->get_ids_sql .= " $this->where_term";
        $this->final_sql .=" {$this->comb_name_term} {$client_filter}";
        
        //echo "<br>{$this->count_sql}<br>{$this->get_ids_sql}<br>{$this->final_sql}<br>";        print_r($this->params);
    }
}
