<h1>Seznam objednávek</h1>
<h4>Nalezeno <?=$this->pocet_zaznamu?> objednávek.</h4>

<form method="get" id="orders_list_filter">
    <table id="orders_list" class="w3_table">
        <tr>
            <th>Objednávka č. <?=$this->get_order_by_butts->get_html('o.id')?></th>
            <th>Přidáno <?=$this->get_order_by_butts->get_html('o.added')?></th>
            <th>Naposled upraveno <?=$this->get_order_by_butts->get_html('o.last_edited')?></th>
            <th>Poznámka <?=$this->get_order_by_butts->get_html('o.note')?></th>
            <th>Klient <?=$this->get_order_by_butts->get_html('cn.comb_name')?></th>
            <th>Status</th>
            <th></th>
        </tr>
        <tr>
            <th><input type="text" name="o_id"></th>
            <th>
                <select name="add_sign">
                    <option value="less"><=</option>
                    <option value="equal">=</option>
                    <option value="more">=></option>
                </select>
                <input type="date" name="add_date">
            </th>
            <th>
                <select name="edit_sign">
                    <option value="less"><=</option>
                    <option value="equal">=</option>
                    <option value="more">=></option>
                </select>
                <input type="date" name="edit_date">
            </th>
            <th><input type="text" name="note"></th>
            <th><input type="text" name="client"></th>
            <th>
                <select name="status">
                    <option value="0">-----</option>
                    <?foreach ($this->status_texts as $k => $v):?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?endforeach?>
                </select>
                <input type="hidden" name="order_by" value="<?=$this->order_by?>">
                <input type="hidden" name="page" id="curr_page" value="<?=$this->page?>">                
            </th>
            <th>
                <input type="submit" name="sent" value="Najít">
                <a href="<?=$this->webroot?>/orders"><button type="button">Reset</button></a>
            </th>
        </tr>
        <?foreach ($this->seznam as $row):?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->added?></td>
                <td><?=$row->last_edited?></td>
                <td><?=$row->note?></td>
                <td><?=$row->comb_name?></td>
                <td>
                    <?=$row->status_name?>
                    <?if ($row->status == 1):?>
                        <a href="<?=$this->webroot?>/najit_polozku?ord_id=<?=$row->id?>"><button type="button">Najít položky</button></a>
                    <?endif?>
                </td>
                <td></td>
            </tr>                
        <?endforeach?>
    </table>
</form>
<?=$this->strankovac?>

<span id="form_save" class="hidden"><?=$this->form_save?></span>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>
    
<script>var forms_fce = new Forms_fce();</script>
<script src="<?=$this->webroot?>/js/orders.js"></script>