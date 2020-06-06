<h1>Položky ve skladě</h1>
<?if (!$this->detailne):?>
    <a href="<?=$this->webroot?>/polozky_ve_sklade/detailne"><button type="button">Detailně</button></a>
    <?if ($this->pouze_volne):?>
        <a href="<?=$this->webroot?>/polozky_ve_sklade"><button type="button">Zobrazit vše</button></a>
    <?else:?>
        <a href="<?=$this->webroot?>/polozky_ve_sklade/volne"><button type="button">Pouze volné položky</button></a>
    <?endif?>
    <?if (isset($this->top_message)):?>
        <p class="message"><?=$this->top_message?></p>
    <?endif?>
    <?if ($this->brief_list):?>
        <?if (isset($this->add_item_errors)):?>
            <ul class="error">
                <?foreach ($this->add_item_errors as $error):?>
                    <li><?=$error?></li>
                <?endforeach?>
            </ul>
        <?endif?>        
        <div id="brief_item_list">
            <?foreach ($this->brief_list as $sklad_nm => $sklad_items):?>
            <div class="ware_list_brief">
                <h4><?=$sklad_nm?></h4>
                <form method="post" class="add_item">
                    <table class="w3_table ware_list_brief_table">
                        <tr>
                            <th>Název položky</th><th>Celkový počet<?=$this->pouze_volne? ' (pouze volné položky)' : ''?></th>                        
                        </tr>
                        <?foreach ($sklad_items as $row):?>
                            <tr>
                                <td><?=$row->it_name?></td>
                                <td><?=$row->pocet?></td>
                            </tr>
                        <?endforeach?>
                        <tr class="add_item_row" data-w_id="<?=$row->w_id?>">
                            <td>
                                <select class="item_id" name="item_id" data-w_id="<?=$row->w_id?>">
                                    <option value="0">-----</option>
                                    <?foreach ($this->items as $item):?>
                                        <option value="<?=$item->id?>"><?=$item->name?></option>
                                    <?endforeach?>
                                </select>
                            </td>
                            <td>
                                <label class="it_amount_label" for="item_amount_w_<?=$row->w_id?>" >množství (maximálně <span class="item_max_amount">??</span> ks)</label>
                                <input class="item_amount" id="item_amount_w_<?=$row->w_id?>" type="text" name="item_amount">
                                <input type="hidden" name="w_id" value="<?=$row->w_id?>">
                                <input type="submit" name="add_item_sent" value="přidat">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?endforeach?>
        </div>
    <?else:?>
        <h4>Všechny sklady jsou prázdné.</h4>        
    <?endif?>
    <?if ($this->volne_sklady):?>
        <h4>Přidat položku do prázdného skladu</h4>
        <?if (isset($this->add_item_empty_ware_errors)):?>
            <ul class="error">
                <?foreach ($this->add_item_empty_ware_errors as $error):?>
                    <li><?=$error?></li>
                <?endforeach?>
            </ul>
        <?endif?>
        <form method="post" class="add_item_to_empty">
            <table class="w3_table" id="add_item_to_empty_tab">
                <tr>
                    <th>Sklad</th><th>Název položky</th><th>Množství</th>
                </tr>
                <tr id="add_item_to_empty_row">
                    <td>
                        <select id="w_id_empty" name="w_id">
                            <option value="0">-----</option>
                            <?foreach ($this->volne_sklady as $sklad):?>
                                <option value="<?=$sklad->id?>"><?=$sklad->name?></option>
                            <?endforeach?>
                        </select>
                    </td>
                    <td>
                        <select class="item_id" name="item_id" data-w_id="empty">
                            <option value="0">-----</option>
                            <?foreach ($this->items as $item):?>
                                <option value="<?=$item->id?>"><?=$item->name?></option>
                            <?endforeach?>
                        </select>                        
                    </td>
                    <td>
                        <label class="it_amount_label" for="item_amount_w_empty">maximálně <span class="item_max_amount">??</span> ks</label>
                        <input class="item_amount" id="item_amount_w_empty" type="text" name="item_amount">
                        <input type="hidden" name="w_empty" value="1">
                        <input type="submit" name="add_item_sent" value="přidat">
                    </td>
                </tr>
            </table>
        </form>
    <?endif?>
    <span class="hidden" id="add_item_saved_form"><?=$this->add_item_form?></span>        
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
<span class="hidden" id="ajax_dir"><?=$this->webroot?>/ajax</span>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>
    
<script>var forms_fce = new Forms_fce();</script>
<script src="<?=$this->webroot?>/js/polozky_ve_sklade.js"></script>



