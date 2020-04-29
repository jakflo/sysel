<?php


namespace Sysel\Pages\Sklady;
use Sysel\Conf\Models;
use Sysel\Utils\Array_tools;
use Sysel\Utils\Date_tools;
use Sysel\Data_objects\Seznam_skladu;
use Sysel\Utils\Xss_fix;


class Sklady_model extends Models {
        
    public function zobraz_sklady() {
        $vsechny_sklady = $this->env->db->dotaz_vse("SELECT * FROM sysel.warehouse");
        if ($vsechny_sklady) {
            $seznam_data_obj = new Seznam_skladu;
            $array_tools = new Array_tools;
            $date_tools = new Date_tools;
            $zabrany_plochy = $this->zabrane_plochy_skladu();
            $pouzite_sklady = $this->pouzite_sklady();
            foreach ($vsechny_sklady as &$sklad) {
                $sklad['created'] = $date_tools->en_date_na_cz($sklad['created']);
                $zabrana_plocha_skladu = $array_tools->hledej_ve_vicepoli($zabrany_plochy, $sklad['id'], 'warehouse_id');
                if (count($zabrana_plocha_skladu) != 0) {
                    $sklad['area_left'] = $sklad['area'] - $zabrana_plocha_skladu[0]['zabrano'];                    
                }
                else {
                    $sklad['area_left'] = $sklad['area'];                    
                }
                if (in_array($sklad['id'], $pouzite_sklady)) {
                    $sklad['is_empty'] = false;
                }
                else {
                    $sklad['is_empty'] = true;                    
                }
            }
            $vsechny_sklady = $seznam_data_obj->load_2d_array($vsechny_sklady);
        }
        return $vsechny_sklady;        
    }
    
    public function zabrane_plochy_skladu(int $id = 0) {
        if ($id == 0) {
            $id_term = '';
            $id_param = array();
        }
        else {
            $id_term = "and i.warehouse_id=:wid";
            $id_param = array(':wid' => $id);
        }
        return $this->env->db->dotaz_vse("select warehouse_id, sum(area) as zabrano from item i 
                            join item_detail ide on i.item_detail_id=ide.id 
                            where i.status in(1,2) {$id_term} group by warehouse_id", $id_param);        
    }
    
    public function pouzite_sklady() {
        return $this->env->db->dotaz_sloupec("select distinct warehouse_id from item", 'warehouse_id');        
    }
    
    public function existuje_dle_id(int $id) {
        $warehose_exists = $this->env->db->dotaz_hodnota(
                    "select count(*) from warehouse where id=:id", 
                    array(':id' => $id));
        return $warehose_exists != 0;
    }
    
    public function existuje_dle_jmena(string $name) {
        $warehose_exists = $this->env->db->dotaz_hodnota(
                    "select count(*) from warehouse where name=:name", 
                    array(':name' => $name));
        return $warehose_exists != 0;
    }
    
    public function jmeno_skladu(int $id) {
        $xss = new Xss_fix;
        $name = $this->env->db->dotaz_hodnota(
                "SELECT name FROM sysel.warehouse where id=:id", 
                array(':id' => $id)
        );
        return $xss->fix_string($name);        
    }
    
    public function rename(int $id, string $name) {
        $this->env->db->sendSQL("update warehouse set name=:name where id=:id", 
                array(':id' => trim($id), ':name' => trim($name)));                
    }
    
    public function delete(int $id) {
        $this->env->db->sendSQL("delete FROM warehouse where id=:id", 
                array(':id' => trim($id)));
    }
    
    public function new($name, $area) {
        $dnes = date('Y-m-d');
        $this->env->db->sendSQL(
                "insert into warehouse(name, area, created) value(?,?,?);", 
                array($name, $area, $dnes));        
    }
}
