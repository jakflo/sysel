<?php

namespace Sysel\Pages\Vyrobec;
use Sysel\Conf\Models;
use Sysel\Data_objects\Seznam_vyrobcu;

class Vyrobec_model extends Models {
    public function zobraz_seznam() {
        $seznam = $this->env->db->dotaz_vse("select m.id, m.name, m.email, m.phone, a.street, a.city, a.country 
                        from manufacturer m join address a on m.address_id=a.id");
        foreach ($seznam as &$row) {
            $row['adresa_radek'] = "{$row['street']}, {$row['city']}, {$row['country']}";
        }
        $data_obj = new Seznam_vyrobcu;
        return $data_obj->load_2d_array($seznam);
    }
    
    public function existuje_dle_id($id) {
        $res = $this->env->db->dotaz_hodnota("select count(*) from manufacturer where id=?", array($id));
        return $res != 0;        
    }
}
