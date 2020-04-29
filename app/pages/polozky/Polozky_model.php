<?php

namespace Sysel\Pages\Polozky;
use Sysel\Conf\Models;
use Sysel\Data_objects\Seznam_polozek;

class Polozky_model extends Models {
    public function zobraz_seznam() {
        $seznam = $this->env->db->dotaz_vse("select ide.id, ide.name as name, area, m.name as vyrobce, a.country 
                                    from item_detail ide join manufacturer m on ide.manufacturer_id=m.id 
                                    join address a on m.address_id=a.id");
        $pouzite_polozky = $this->get_pouzite_polozky();
        foreach ($seznam as &$row) {
            if (in_array($row['id'], $pouzite_polozky)) {
                $row['used'] = true;
            }
            else {
                $row['used'] = false;                
            }            
        }
        $data_obj = new Seznam_polozek;
        return $data_obj->load_2d_array($seznam);        
    }
    
    public function rename(int $id, string $name) {
        $this->env->db->sendSQL(
                "update item_detail set name=:name where id=:id", 
                array(':name' => $name, ':id' => $id)
                );
    }
    
    public function delete(int $id) {
        $this->env->db->sendSQL("delete from item_detail where id=?", array($id));        
    }
    
    public function new(string $name, float $area, int $vyrobce) {
        $this->env->db->sendSQL(
                "insert into item_detail(name, area, manufacturer_id) value (?,?,?)", 
                array($name, $area, $vyrobce)
                );
    }
    
    public function change_area(int $id, float $area) {
        $this->env->db->sendSQL(
                "update item_detail set area=:area where id=:id", 
                array(':area' => $area, ':id' => $id)
                );
    }
    
    public function jmeno_polozky(int $id) {
        return $this->env->db->dotaz_hodnota("select name from item_detail where id=?", array($id));        
    }
    
    public function get_pouzite_polozky() {
        return $this->env->db->dotaz_sloupec("select distinct item_detail_id from item", 'item_detail_id');        
    }
    
    public function existuje_dle_id(int $id) {
        $res = $this->env->db->dotaz_hodnota("select count(*) from item_detail where id=?", array($id));
        return $res != 0;                
    }
    
    public function existuje_dle_jmena(string $name) {
        $res = $this->env->db->dotaz_hodnota("select count(*) from item_detail where name=?", array($name));
        return $res != 0;                
    }
        
}
