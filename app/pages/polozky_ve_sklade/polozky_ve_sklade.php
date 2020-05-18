<h1>Položky ve skladě</h1>
<?if (!$this->detailne):?>
    <a href="<?=$this->webroot?>/polozky_ve_sklade/detailne"><button type="button">Detailně</button></a>
    <?if ($this->brief_list):?>
        <div id="brief_item_list">
            <?foreach ($this->brief_list as $sklad_nm => $sklad_items):?>
            <div class="ware_list_brief">
                <h4><?=$sklad_nm?></h4>
                <table class="w3_table ware_list_brief_table">
                    <tr>
                        <th>Název položky</th><th>Celkový počet</th>                        
                    </tr>
                    <?foreach ($sklad_items as $row):?>
                        <tr>
                            <td><?=$row->it_name?></td>
                            <td><?=$row->pocet?></td>
                        </tr>
                    <?endforeach?>
                </table>
            </div>
            <?endforeach?>
        </div>
    <?else:?>
        <h4>Všechny sklady jsou prázdné.</h4>
    <? endif?>
<?else:?>
    <h4>Nalezeno <?=$this->pocet_zaznamu?> záznamů.</h4>
    <div id="item_full_list">
        <?if ($this->pocet_zaznamu > 0):?>
            <form method="get" id="filter_form">
                <table class="w3_table">
                    <tr>
                        <th>Položka č.</th>
                        <th>Název skladu <?=$this->get_order_by_butts('w.name')?></th>
                        <th>Název položky <?=$this->get_order_by_butts('ide.name')?></th>
                        <th>Přidáno <?=$this->get_order_by_butts('i.added')?></th>
                        <th>Status položky</th>
                        <th>Č. objednávky <?=$this->get_order_by_butts('i.order_id')?></th>
                        <th>Zákazník <?=$this->get_order_by_butts('comb_name')?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th><input class="itemlist_filter" type="text" name="w_name"></th>
                        <th><input class="itemlist_filter" type="text" name="item_name"></th>
                    <th>
                        <select class="itemlist_filter" name="added_znak">
                            <option value="less"><=</option>
                            <option value="equal">=</option>
                            <option value="more">>=</option>
                        </select>
                        <input class="itemlist_filter" type="date" name="added">
                    </th>
                    <th>
                        <select class="itemlist_filter" name="status">
                            <option value="0" selected>------------</option>
                            <option value="1">volná</option>
                            <option value="2">rezervovaná</option>
                            <option value="3">expedovaná</option>
                            <option value="4">reklamace</option>
                        </select>
                    </th>
                    <th><input class="itemlist_filter" type="text" name="o_id"></th>
                    <th><input class="itemlist_filter" type="text" name="client_name"></th>
                    <th>
                        <input type="submit" name="find" value="najdi">
                        <a href="<?=$this->webroot?>/polozky_ve_sklade/detailne"><button type="button">Reset</button></a>
                    </th>
                    </tr>
                    <input type="hidden" name="page" id="curr_page" value="<?=$this->page?>">
                    <input type="hidden" name="order_by" value="<?=$this->order_by?>">                    

                    <?foreach ($this->full_list as $row):?>
                        <tr>
                            <td><?=$row->item_id?></td>
                            <td><?=$row->war_name?></td>
                            <td><?=$row->item_name?></td>
                            <td><?=$row->added?></td>
                            <td><?=$row->status?></td>
                            <td><?=$row->order_id?></td>
                            <td><?=$row->comb_name?></td>
                            <td></td>
                        </tr>
                    <?endforeach?>
                </table>
            </form>
            <?=$this->strankovac?>
        <?endif?>
        <span class="hidden" id="form_save"><?=$this->form_save?></span>
    </div>
    <a href="<?=$this->webroot?>/polozky_ve_sklade"><button type="button">Pouze součty</button></a>
<?endif?>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>
    
<script>var forms_fce = new Forms_fce();</script>
<script src="<?=$this->webroot?>/js/polozky_ve_sklade.js"></script>



