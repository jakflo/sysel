<?php

namespace Sysel\Utils;
use Sysel\Conf\Models;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Strankovac;

abstract class Filtered_list extends Models {    
    /**
     * count_sql = dotaz pouze zjištění počtu vyhovujících záznamů
     * get_ids_sql = dotaz pro zjištění id první tablulky kde jsou filtry, řazení a limit
     * finale_sql = konečný dotaz
     * @var string
     */
    protected $count_sql, $get_ids_sql, $final_sql;
    
    /**
     * params = parametry pro prepared statement
     * params_where_keys = seznam params použitý ve where termu (nepoužívá se u final dotazu)
     * @var array
     */
    protected $params = array();
    protected $params_where_keys = array();
    
    /*
     * není-li v ids dotazu daný term, přidá term do dotazů (bez filtračníjo termu)
     * třebas je třeba pro orded by
     */
    protected function join_if_not_joined($term) {
        if (strpos($this->get_ids_sql, $term) === false) {
            $nic = '';
            $this->add_terms($term, ' ', $nic);            
        }
    }
    
    public function get_count() {
        return $this->env->db->dotaz_hodnota($this->count_sql, $this->params);       
    }
    
    public function get_rows(Strankovac $page, string $key_id_term) {
        $params = $this->params;
        foreach ($this->params_where_keys as $k) {
            unset($params[$k]);            
        }
        $array_tools = new Array_tools;
        $ids = $this->get_ids($page);
        $sql = "{$this->final_sql} where {$key_id_term} in({$array_tools->implode_pro_in($ids)}) {$this->order_by}";
        return $this->env->db->dotaz_vse($sql, $params);        
    }
    
    public function get_ids(Strankovac $page) {
        $sql = "{$this->get_ids_sql} {$this->order_by} limit {$page->get_limit_string()}";
        return $this->env->db->dotaz_sloupec($sql, 'id', $this->params);        
    }
    
    /*
     * přidá join a filtrační term k dotazům, filtrační dotaz zkopíruje do $extras tring v referenci
     */
    protected function add_terms(string $term, string $filter_term, &$extra_string) {
        $this->count_sql .= " {$term} {$filter_term}";
        $this->get_ids_sql .= " {$term} {$filter_term}";
        $extra_string = $filter_term;        
    }
    
    protected function add_where(string $term) {
        if (empty($this->where_term)) {
            $this->where_term = " where {$term}";
        }
        else {
            $this->where_term .= " and {$term}";
        }        
    }    

    //nahradí left join za join u řetězce v referenci
    protected function unleft_join(&$target) {
        $target = str_replace('left join', 'join', $target);        
    }
    
    
}
