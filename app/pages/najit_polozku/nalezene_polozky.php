<?if (!$this->search_result->is_empty()):?>
    <div id="search_result">
        <h2>Nalezené položky</h2>
        <table class="w3_table">
            <tr>
                <td></td>
                <?foreach ($this->search_result->get_ware_list() as $w_name):?>
                    <th><?=$w_name?></th>
                <?endforeach?>                
            </tr>
            <?$item_ids = $this->search_result->get_item_ids()?>
            <?foreach ($item_ids as $item_id):?>
            <tr>
                <?$row = $this->search_result->get_row($item_id)?>
                <th><?=$row->item_name?></th>
                <?foreach ($row->cells as $cell):?>
                    <td class="<?=$cell->color?>"><?=$cell->kolik_z?></td>
                <?endforeach?>
            </tr>
            <?endforeach?>
        </table>        
    </div>
<?endif?>

<?if (count($this->sklady_kde_je_vse_hledane) > 0):?>
    <h2>Sklady, kde jsou všechny položky v žádaném množství:</h2>
    <ul id="ware_has_all_items">
    <?foreach ($this->sklady_kde_je_vse_hledane as $ware):?>
        <li><?=$ware->name?></li>
    <?endforeach?>        
    </ul>
<?else:?>
    <h2>Všechny položky nelze získat z jediného skladu.</h2>
<?endif?>
    
<?if (count($this->missing_items) > 0):?>
    <div id="search_result_missing_items">
        <h2>Chybějící položky</h2>
        <table class="simple_borderless">
            <?foreach ($this->missing_items as $item):?>
                <tr>
                    <th><?=$item->name?></th>
                    <td><?=$item->kolik?></td>
                </tr>
            <?endforeach?>
        </table>        
    </div>
<?endif?>
