<?php

namespace Sysel\Pages\Najit_polozku;
use Sysel\Conf\Env;
use Sysel\Utils\Protected_in;
use Sysel\Utils\Array_tools;
use Sysel\Pages\Najit_polozku\Ware_has_all_items;
use Sysel\Pages\Najit_polozku\Missing_items;

class Items_search {
    /**     
     * @var Env
     */
    protected $env;
    
    /**
     * 2D array z DB dotazu     
     * @var array
     */
    protected $polozky_ve_skladech;
    
    protected $pozadovane_items = array();
    
    /**     
     * @var Ware_has_all_items
     */
    protected $ware_has_all_items, $ware_has_some_items;
    
    /**
     * @var Missing_item 
     */
    protected $missing_items;

    /**
     * @param Env $env
     * @param array $item_list = 1D array s ID položek, které se budou hledat
     * @param array $ware_list = 1D array s ID skladů, které se budou prohledávat, 
     * nebo prázdný array pro všechny sklady
     */
    public function __construct(Env $env, array $item_list, array $ware_list) {
        $this->env = $env;      
        $this->init_polozky_ve_skladech($item_list, $ware_list);
        $this->missing_items = new Missing_items($this->env);       
    }
    
    protected function init_polozky_ve_skladech(array $item_list, array $ware_list) {
        $in_terms = new Protected_in;
        $in_terms->add_array('itl', $item_list);
        $ide_term = "and ide.id in ({$in_terms->get_tokens('itl')})";
        if (count($ware_list) > 0) {
            $in_terms->add_array('wl', $ware_list);
            $w_term = "and w.id in({$in_terms->get_tokens('wl')})";
        }
        else {
            $w_term = '';
        }
        $this->polozky_ve_skladech = $this->env->db->dotaz_vse(
                "select w.id as w_id, w.name as w_name, ide.id as it_id, ide.name as it_name, count(ide.id) as pocet 
                from item i join item_detail ide on i.item_detail_id=ide.id {$ide_term} 
                join warehouse w on i.warehouse_id=w.id {$w_term}
                where i.status = 1 group by i.item_detail_id, i.warehouse_id 
                order by warehouse_id, i.item_detail_id", 
                $in_terms->get_data()
                );
        $this->ware_has_all_items = new Ware_has_all_items($this);
        $this->ware_has_some_items = new Ware_has_all_items($this);
    }
    
    public function search_item(int $id, int $amount) {
        $this->pozadovane_items[$id] += $amount;
        $array_tools = new Array_tools;
        $found = $array_tools->hledej_ve_vicepoli($this->polozky_ve_skladech, $id, 'it_id', true);
        $this->ware_has_all_items->smaz_pokud_neni_v_array(array_column($found, 'w_id'));
        if (count($found) > 0) {
            $pocty = array_sum(array_column($found, 'pocet'));            
            $staci_celkove = $amount <= $pocty;
        }
        else {
            $staci_celkove = false;
            $pocty = 0;
        }
        
        if (!$staci_celkove) {
            $this->missing_items->add_item($id, $amount - $pocty);            
        }
        $sklady_s_dostatkem_zbozi = array();
        foreach (array_keys($found) as $key) {
            $this->polozky_ve_skladech[$key]['pozadovano'] = $amount;
            $kolik_ve_sklade = $this->polozky_ve_skladech[$key]['pocet'];
            $staci = $kolik_ve_sklade >= $amount;
            $this->polozky_ve_skladech[$key]['kolik_z'] = "{$kolik_ve_sklade} / {$amount}";            
            $this->polozky_ve_skladech[$key]['staci'] = $staci;
            if ($staci) {
                $sklady_s_dostatkem_zbozi[] = $this->polozky_ve_skladech[$key]['w_id'];
            }
        }
        $this->ware_has_all_items->smaz_pokud_neni_v_array($sklady_s_dostatkem_zbozi);
    }
    
    public function get_polozky_ve_skladech() {
        return $this->polozky_ve_skladech;        
    }
    
    public function get_nic_nebylo_nalezeno() {
        return !is_array($this->polozky_ve_skladech);
    }
    
    public function kolik_je_polozky_pozadovano(int $item_id) {
        return $this->pozadovane_items[$item_id];
    }
    
    public function get_chybi_items() {
        return $this->missing_items->get_chybejici_polozky();
    }
    
    public function get_sklady_kde_je_vse_hledane() {
        return $this->ware_has_all_items->get_ware_list();        
    }
    
    public function get_sklady_kde_je_alespon_1_hledana_polozka() {
        return $this->ware_has_some_items->get_ware_list();
    }
}
